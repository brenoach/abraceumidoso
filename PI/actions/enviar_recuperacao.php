<?php
require_once 'includes/db.php';


    require '../Bibliotecas/PHPMailer/Exception.php';
    require '../Bibliotecas/PHPMailer/OAuth.php';
    require '../Bibliotecas/PHPMailer/PHPMailer.php';
    require '../Bibliotecas/PHPMailer/POP3.php';
    require '../Bibliotecas/PHPMailer/SMTP.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

   // print_r($_POST);
date_default_timezone_set('America/Sao_Paulo');

// Verifica se a URL tem o token e o tipo
if (!isset($_GET['token']) || !isset($_GET['tipo'])) {
    die("<script>alert('Link de recuperação inválido ou quebrado.'); window.location.href='login.php';</script>");
}

$token = $_GET['token'];
$tipo = $_GET['tipo'];
$tokenValido = false;

try {
    // Busca o usuário que tem esse token E verifica se o token não venceu
    if ($tipo == 'voluntario') {
        $sql = "SELECT idVoluntario FROM voluntario WHERE reset_token = ? AND token_expira > NOW()";
    } else {
        $sql = "SELECT idFuncionario FROM funcionario WHERE reset_token = ? AND token_expira > NOW()";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$token]);
    $usuario = $stmt->fetch();

    if ($usuario) {
        $tokenValido = true;
    }

} catch (Exception $e) {
    die("Erro no sistema: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Criar Nova Senha - Abrace um Idoso</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <header class="cabecalho">
        <div class="logo">Abrace um Idoso</div>
    </header>

    <main class="container-principal">
        <div class="card-formulario" style="max-width: 400px;">

            <?php if ($tokenValido): ?>
            <h2>Criar Nova Senha 🔐</h2>
            <p>O seu link foi validado. Digite a sua nova senha abaixo.</p>

            <form action="actions/salvar_nova_senha.php" method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <input type="hidden" name="tipo" value="<?= htmlspecialchars($tipo) ?>">

                <div class="grupo-input">
                    <label>Nova Senha:</label>
                    <input type="password" name="nova_senha" required placeholder="Digite a nova senha">
                </div>

                <div class="grupo-input">
                    <label>Confirme a Senha:</label>
                    <input type="password" name="confirma_senha" required placeholder="Repita a nova senha">
                </div>

                <button type="submit" class="btn-marrom" style="width: 100%; margin-top: 15px;">Salvar e Entrar</button>
            </form>
            <?php else: ?>
            <div class="erro">
                <strong>Ops! Link expirado ou inválido.</strong><br>
                Por motivos de segurança, este link não é mais válido. Por favor, solicite a recuperação de senha
                novamente.
            </div>
            <div style="text-align: center; margin-top: 20px;">
                <a href="esqueci_senha.php" class="btn-marrom"
                    style="text-decoration: none; display: inline-block;">Solicitar Novo Link</a>
            </div>
            <?php endif; ?>

        </div>
    </main>
</body>

</html>