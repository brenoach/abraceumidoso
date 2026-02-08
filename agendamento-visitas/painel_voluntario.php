<?php
session_start();
// PROTEÇÃO: Se não estiver logado ou não for voluntário, expulsa
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'voluntario') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Voluntário</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="cabecalho">
        <div class="logo">Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</div>
        <nav class="nav-menu">
            <ul><li><a href="actions/logout.php" style="background: #5b3a26; color: white;">Sair</a></li></ul>
        </nav>
    </header>

    <main class="container-principal">
        <div class="card-formulario">
            <h2>Seus Agendamentos</h2>
            <p>Aqui você poderá ver suas visitas marcadas.</p>
            <br>
            <a href="index.php" class="btn-amarelo">Novo Agendamento</a>
        </div>
    </main>
</body>
</html>