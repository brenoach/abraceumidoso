<?php
// 1. CONFIGURAÇÕES E BANCO
require_once __DIR__ . '/../connection/config.php'; 
require_once __DIR__ . '/../includes/db.php'; 

// 2. PHPMAILER (Pasta 'bibliotecas' com "b" minúsculo)
require_once __DIR__ . '/../bibliotecas/PHPMailer/Exception.php';
require_once __DIR__ . '/../bibliotecas/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../bibliotecas/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $tipo = $_POST['tipo_usuario'];

    try {
        // --- PARTE 1: BUSCA O USUÁRIO (Ajustado para idContato conforme seu CREATE TABLE) ---
        if ($tipo == 'voluntario') {
            // v.idContato (chave estrangeira) liga com c.idContato (chave primária)
            $sqlBusca = "SELECT v.idVoluntario as id FROM voluntario v 
                         JOIN contato c ON v.idContato = c.idContato 
                         WHERE c.email = ?";
        } else {
            $sqlBusca = "SELECT f.idFuncionario as id FROM funcionario f 
                         JOIN contato c ON f.idContato = c.idContato 
                         WHERE c.email = ?";
        }

        $stmtBusca = $pdo->prepare($sqlBusca);
        $stmtBusca->execute([$email]);
        $usuario = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            $idUsuario = $usuario['id'];
            $token = bin2hex(random_bytes(25)); 
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // SALVA O TOKEN NO BANCO
            $tabela = ($tipo == 'voluntario') ? 'voluntario' : 'funcionario';
            $idCampo = ($tipo == 'voluntario') ? 'idVoluntario' : 'idFuncionario';
            
            $stmtUpdate = $pdo->prepare("UPDATE $tabela SET resetToken = ?, tokenExpira = ? WHERE $idCampo = ?");
            $stmtUpdate->execute([$token, $expira, $idUsuario]);

            // LINK PARA O E-MAIL
            $linkRecuperacao = BASE_URL . "pages/redefinir_senha.php?token=" . $token . "&tipo=" . $tipo;

            // --- PARTE 2: ENVIO COM GMAIL ---
            $mail = new PHPMailer(true);
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'brenoach@gmail.com'; 
            $mail->Password   = 'brgs qiew zpmq zdrx'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
            $mail->Port       = 587;                                    

            $mail->setFrom('brenoach@gmail.com', 'Abrace um Idoso');
            $mail->addAddress($email); 

            $mail->isHTML(true);                                  
            $mail->Subject = 'Recuperacao de Senha - Abrace um Idoso';
            $mail->Body    = "
                <div style='font-family: sans-serif; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                    <h2 style='color: #673AB7;'>Recuperação de Senha</h2>
                    <p>Olá! Recebemos seu pedido de nova senha.</p>
                    <p>Clique no botão abaixo para prosseguir:</p>
                    <br>
                    <a href='{$linkRecuperacao}' style='background: #673AB7; color: white; padding: 12px 25px; text-decoration: none; border-radius: 8px; font-weight: bold;'>REDEFINIR SENHA</a>
                    <p style='font-size: 0.8rem; color: #888; margin-top: 20px;'>Link válido por 1 hora.</p>
                </div>
            ";

            $mail->send();
            echo "<script>alert('Sucesso! Link enviado para o seu e-mail.'); window.location.href='../pages/login.php';</script>";

        } else {
            echo "<script>alert('Se o e-mail estiver correto, você receberá as instruções.'); window.location.href='../pages/login.php';</script>";
        }

    } catch (Exception $e) {
        echo "Erro no envio: {$mail->ErrorInfo}";
    }
}