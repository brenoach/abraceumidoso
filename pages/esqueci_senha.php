<?php
// 1. CARREGA AS CONFIGURAÇÕES (O PHP precisa do __DIR__ para achar os arquivos)
require_once __DIR__ . '/../connection/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - Abrace um Idoso</title>
</head>
<body>
    
    <main class="container-principal">
        <div class="card-formulario" style="max-width: 400px; margin: 50px auto; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border-radius: 15px;">
            <h2 style="color: #673AB7;">Esqueceu a senha? 🔒</h2>
            <p style="color: #666; font-size: 0.9em;">Digite seu e-mail cadastrado e nós enviaremos um link para você criar uma nova senha.</p>

            <form action="<?php echo BASE_URL; ?>actions/processa_envio.php" method="POST">

                <div class="grupo-input" style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display: block;">Eu sou um:</label>
                    <div style="display: flex; gap: 20px; margin-top: 8px;">
                        <label style="cursor: pointer;">
                            <input type="radio" name="tipo_usuario" value="voluntario" checked> Voluntário
                        </label>
                        <label style="cursor: pointer;">
                            <input type="radio" name="tipo_usuario" value="funcionario"> Funcionário
                        </label>
                    </div>
                </div>

                <div class="grupo-input" style="margin-bottom: 15px;">
                    <label style="font-weight: bold; display: block;">E-mail cadastrado:</label>
                    <input type="email" name="email" required placeholder="seu@email.com" 
                           style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-top: 5px;">
                </div>

                <button type="submit" class="btn-marrom" 
                        style="width: 100%; padding: 12px; background: #673AB7; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    Enviar Link de Recuperação
                </button>
            </form>

            <p style="text-align: center; margin-top: 20px; font-size: 0.9em;">
                Lembrou a senha? <a href="login.php" style="color: #673AB7; font-weight: bold; text-decoration: none;">Voltar ao Login</a>
            </p>
        </div>
    </main>

    <?php 
    // No include do PHP, o __DIR__ continua sendo o jeito mais seguro!
    include __DIR__ . '/../includes/footer.php'; 
    ?>
</body>
</html>