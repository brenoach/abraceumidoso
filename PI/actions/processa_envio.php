<?php
// 1. Conecta com o Banco de Dados (O ../ faz voltar para a pasta PI)
require_once '../includes/db.php'; 

// 2. Chama os arquivos do PHPMailer que estão na sua pasta Bibliotecas
require '../Bibliotecas/PHPMailer/Exception.php';
require '../Bibliotecas/PHPMailer/PHPMailer.php';
require '../Bibliotecas/PHPMailer/SMTP.php';
require '../Bibliotecas/PHPMailer/OAuth.php';
require '../Bibliotecas/PHPMailer/POP3.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $tipo = $_POST['tipo_usuario'];

    try {
        // --- PARTE 1: VERIFICA O BANCO E GERA O TOKEN ---
        if ($tipo == 'voluntario') {
            $sqlBusca = "SELECT v.idVoluntario as id FROM voluntario v JOIN contatos c ON v.contatos_idcontatos = c.idcontatos WHERE c.email = ?";
        } else {
            $sqlBusca = "SELECT f.idFuncionario as id FROM funcionario f JOIN contatos c ON f.contatos_idcontatos = c.idcontatos WHERE c.email = ?";
        }

        $stmtBusca = $pdo->prepare($sqlBusca);
        $stmtBusca->execute([$email]);
        $usuario = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $idUsuario = $usuario['id'];
            $token = bin2hex(random_bytes(25)); // Gera o código secreto
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Salva o token no banco
            if ($tipo == 'voluntario') {
                $sqlUpdate = "UPDATE voluntario SET reset_token = ?, token_expira = ? WHERE idVoluntario = ?";
            } else {
                $sqlUpdate = "UPDATE funcionario SET reset_token = ?, token_expira = ? WHERE idFuncionario = ?";
            }
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([$token, $expira, $idUsuario]);

            // Link que vai no e-mail
            $linkRecuperacao = "http://" . $_SERVER['HTTP_HOST'] . "/abraceumidoso/PI/redefinir_senha.php?token=" . $token . "&tipo=" . $tipo;

            // --- PARTE 2: PREPARA E ENVIA O E-MAIL COM PHPMAILER ---
            $mail = new PHPMailer(true);

            // Configurações do Servidor
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            
            // COLOQUE SEUS DADOS REAIS AQUI ABAIXO:
            $mail->Username   = 'brenoach@gmail.com'; 
            $mail->Password   = 'brgs qiew zpmq zdrx'; 
            
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
            $mail->Port       = 587;                                    

            $mail->setFrom('brenoach@gmail.com', 'Abrace um Idoso');
            $mail->addAddress($email); 

            // Corpo do E-mail
            $mail->isHTML(true);                                  
            $mail->Subject = 'Recuperacao de Senha - Abrace um Idoso';
            $mail->Body    = "
                <h2>Olá!</h2>
                <p>Você solicitou a recuperação da sua senha no sistema <b>Abrace um Idoso</b>.</p>
                <p>Clique no link abaixo para criar uma nova senha. Este link é válido por 1 hora.</p>
                <br>
                <a href='{$linkRecuperacao}' style='background: #5b3a26; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Criar Nova Senha</a>
                <br><br>
                <p>Se você não solicitou isso, apenas ignore este e-mail.</p>
            ";

            $mail->send();
            
            echo "<script>
                    alert('Link de recuperação enviado com sucesso! Verifique seu e-mail.'); 
                    window.location.href='../login.php';
                  </script>";

        } else {
            // Se o e-mail não existir no banco, finge que enviou por segurança contra hackers
            echo "<script>alert('Se o e-mail estiver cadastrado, você receberá um link de recuperação.'); window.location.href='../login.php';</script>";
        }

    } catch (Exception $e) {
        echo "Erro: A mensagem não pôde ser enviada. Detalhe: {$e->getMessage()}";
    }
}
?>