<?php
session_start();
// PROTEÇÃO: Se não estiver logado ou não for funcionário, expulsa
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'funcionario') {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Funcionário</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="cabecalho">
        <div class="logo">Olá, <?php echo $_SESSION['usuario_nome']; ?> (Funcionário)</div>
        <nav class="nav-menu">
            <ul><li><a href="actions/logout.php" style="background: #5b3a26; color: white;">Sair</a></li></ul>
        </nav>
    </header>

    <main class="container-principal">
        <div class="card-formulario">
            <h2>Gestão de Visitas</h2>
            <p>Controle aqui a disponibilidade dos idosos da instituição.</p>
            </div>
    </main>
</body>
</html>