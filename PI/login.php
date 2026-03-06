<?php
session_start(); 
// Se o usuário já estiver logado, não deixa ele ver a tela de login de novo
if (isset($_SESSION['usuario_tipo'])) {
    if ($_SESSION['usuario_tipo'] == 'voluntario') header("Location: painel_voluntario.php");
    else header("Location: painel_funcionario.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - Abrace um Idoso</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php include 'includes/header.php';?>

    <main class="container-principal">
        <div class="card-formulario" style="max-width: 400px;">
            <h2>Acesse sua conta</h2>
            <p>Bem-vindo de volta!</p>

            <form method="POST" action="actions/autenticar.php">

                <div class="grupo-input">
                    <label>Você é:</label>
                    <div style="display: flex; gap: 15px; margin-top: 5px;">
                        <label style="font-weight: normal; cursor: pointer;">
                            <input type="radio" name="tipo_usuario" value="voluntario" checked> Voluntário
                        </label>
                        <label style="font-weight: normal; cursor: pointer;">
                            <input type="radio" name="tipo_usuario" value="funcionario"> Funcionário
                        </label>
                    </div>
                </div>

                <div class="grupo-input">
                    <label>E-mail:</label>
                    <input type="email" name="email" required placeholder="seu@email.com">
                </div>

                <div class="grupo-input">
                    <label>Senha:</label>
                    <input type="password" name="senha" required placeholder="Sua senha">
                </div>

                <div class="banner">
                    <button type="submit" class="btn-marrom" style="width: 100%;">Entrar</button>
                </div>

                <p style="text-align: center; margin-top: 15px; font-size: 0.9em;">
                    Não tem conta? <a href="cadastro_voluntario.php"
                        style="color: #ebb860; font-weight: bold;">Cadastre-se</a>
                </p>
                <p style="text-align: center; margin-top: 5px; font-size: 0.85em;">
                    <a href="esqueci_senha.php"
                        style="color: #666; text-decoration: none;">Esqueci minha senha</a>
                </p>
            </form>
        </div>
    </main>

    <?php include 'includes/footer.php';?>
</body>

</html>