<?php
// 1. CONFIGURAÇÕES E BANCO (O index.php já traz o pdo se configurado, mas mantemos por segurança)
require_once __DIR__ . '/../connection/config.php'; 
require_once __DIR__ . '/../includes/db.php'; 

// 2. PHPMAILER - Ajustado para caminhos seguros
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
        // --- BUSCA O USUÁRIO PELO E-MAIL NA TABELA CONTATO ---
        $sqlBusca = "SELECT idcontato FROM contato WHERE email = ?";
        $stmtBusca = $pdo->prepare($sqlBusca);
        $stmtBusca->execute([$email]);
        $contato = $stmtBusca->fetch(PDO::FETCH_ASSOC);

        if ($contato) {
            $idContato = $contato['idcontato'];
            $token = bin2hex(random_bytes(25)); 
            $expira = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // SALVA O TOKEN NA TABELA CONTATOS (Conforme o ALTER TABLE que fizemos)
            // Note que usei os nomes das colunas que sugeri no passo anterior
            $stmtUpdate = $pdo->prepare("UPDATE contato SET resetToken = ?, data_expiracao = ? WHERE idcontato = ?");
            $stmtUpdate->execute([$token, $expira, $idContato]);

            // LINK PARA O E-MAIL (Usando a ROTA AMIGÁVEL)
            $linkRecuperacao = BASE_URL . "/redefinir-senha?token=" . $token . "&tipo=" . $tipo;

            // --- ENVIO COM GMAIL ---
            $mail = new PHPMailer(true);
            $mail->isSMTP();                                            
            $mail->Host       = 'smtp.gmail.com';                     
            $mail->SMTPAuth   = true;                                   
            $mail->Username   = 'brenoach@gmail.com'; 
            $mail->Password   = 'brgs qiew zpmq zdrx'; // Senha de App do Google
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
            echo "<script>alert('Sucesso! Link enviado para o seu e-mail.'); window.location.href='" . BASE_URL . "/login';</script>";

        } else {
            // Mesmo que não ache, damos a mesma mensagem por segurança (evita descobrir e-mails válidos)
            echo "<script>alert('Se o e-mail estiver correto, você receberá as instruções.'); window.location.href='" . BASE_URL . "/login';</script>";
        }

    } catch (Exception $e) {
        echo "Erro no envio: {$mail->ErrorInfo}";
    }
}