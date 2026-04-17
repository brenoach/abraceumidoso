<?php


require_once __DIR__ . '/../connection/config.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/header.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// PROTEÇÃO UNIFICADA: Se já estiver logado, mostra o ALERTA e redireciona
if (isset($_SESSION['idPessoa']) && isset($_SESSION['usuario_tipo'])) {
    
    $tipo = $_SESSION['usuario_tipo'];
    $painel = ($tipo == 'voluntario') ? "painel_voluntario.php" : "painel_funcionario.php";
    $nomeTipo = ($tipo == 'voluntario') ? "Voluntário" : "Funcionário";
    $idDaMinhaInstituicao = $_SESSION['idInstituicao'];


    // Exibe a mensagem amigável e depois joga para o painel
    echo "<script>
            alert('Você já está logado no sistema como $nomeTipo. Para entrar com outra conta, clique em Sair primeiro.');
            window.location.href = '" . BASE_URL . "/pages/$painel';
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

    
    <main class="container-principal">
        <div class="card-formulario" style="max-width: 400px;">
            <h2>Acesse sua conta</h2>
            <p>Bem-vindo de volta!</p>

            <form action="<?= BASE_URL ?>/actions/autenticar.php" method="POST">

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
                <pre> 
                    
                <p style="text-align: center; margin-top: 5px; font-size: 0.85em;">
                Tipo: Voluntário

    E-mail: voluntario@email.com

    Senha: 123456

    Resultado Esperado: Redirecionar para painel_voluntario.php.

Teste 2 (O Funcionário):

    Tipo: Funcionário (Gestor da Instituição)

    E-mail: funcionario@email.com

    Senha: 123456

    Resultado Esperado: Redirecionar para painel_funcionario.php.
</p>

    <p><strong>Email Funcionário:</strong> breno@abraceumidoso.com | <strong>Senha:</strong> 123456</p>";
    echo "<p><strong>Email Voluntária:</strong> aline.voluntaria@teste.com | <strong>Senha:</strong> 123456</p>"


                </pre>
            </form>
        </div>
    </main>

    <?php include '../includes/footer.php';?>
</body>

</html>