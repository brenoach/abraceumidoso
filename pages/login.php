<?php
//Diagnóstico de erro
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../connection/config.php';
require_once __DIR__ . '/../includes/helpers.php';


// O PHP verifica: "A sessão está desligada?" Se sim, ele liga! Se não, ele fica quieto.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    


// PROTEÇÃO MELHORADA: Avisa o usuário antes de redirecionar
if (isset($_SESSION['usuario_tipo'])) {
    
    // Descobre para onde mandar e qual nome mostrar no alerta
    if ($_SESSION['usuario_tipo'] == 'voluntario') {
        $painel = "painel_voluntario.php";
        $nomeTipo = "Voluntário";
    } else {
        $painel = "painel_funcionario.php";
        $nomeTipo = "Funcionário";
    }

    // Exibe a mensagem amigável e depois joga para o painel
    echo "<script>
            alert('Você já está logado no sistema como $nomeTipo. Para entrar com outra conta, por favor, clique em Sair (Logout) primeiro.');
            window.location.href = '$painel';
          </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - Abrace um Idoso</title>
    
</head>

<body>

    <?php include '../includes/header.php';?>

    <main class="container-principal">
        <div class="card-formulario" style="max-width: 400px;">
            <h2>Acesse sua conta</h2>
            <p>Bem-vindo de volta!</p>

            <form method="POST" action="<?php echo BASE_URL; ?>/actions/autenticar.php">

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
                <p> 🎉 Todos os dados foram inseridos com sucesso!

                        Email Funcionário: breno@abraceumidoso.com | Senha: 123456

                        Email Voluntária: aline.voluntaria@teste.com | Senha: 123456
                </p>
            </form>
        </div>
    </main>

    <?php include '../includes/footer.php';?>
</body>

</html>