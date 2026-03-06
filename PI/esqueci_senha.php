<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - Abrace um Idoso</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <?php include 'includes/header.php';?>

    <main class="container-principal">
        <div class="card-formulario" style="max-width: 400px;">
            <h2>Esqueceu a senha? 🔒</h2>
            <p>Digite seu e-mail abaixo. Nós enviaremos um link seguro para você criar uma nova senha.</p>

            <form action="actions/processa_envio.php" method="POST">

                <div class="grupo-input">
                    <label>Eu sou um:</label>
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
                    <label>E-mail cadastrado:</label>
                    <input type="email" name="email" required placeholder="seu@email.com">
                </div>

                <button type="submit" class="btn-marrom" style="width: 100%; margin-top: 15px;">Enviar Link de
                    Recuperação</button>
            </form>

            <p style="text-align: center; margin-top: 20px; font-size: 0.9em;">
                Lembrou a senha? <a href="login.php" style="color: #ebb860; font-weight: bold;">Voltar ao Login</a>
            </p>

        </div>
    </main>

    <?php include 'includes/footer.php';?>
</body>

</html>