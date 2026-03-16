<?php
// 1. Início da sessão (ESSENCIAL para evitar os erros de Undefined Variable)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Definição da BASE_URL
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . '/abraceumidoso/');
}

// 3. Proteção contra índices inexistentes (evita os Warnings na tela)
$tipo = $_SESSION['usuario_tipo'] ?? null;
$inst_id = $_SESSION['instituicao_id'] ?? null;
$nome_usuario = $_SESSION['usuario_nome'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrace um Idoso</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asap:wght@400;600&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    

    

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css?v=1.2">
</head>
<body>

<header class="cabecalho">
    <a href="<?php echo BASE_URL; ?>index.php" class="logo">
        <img src="<?php echo BASE_URL; ?>assets/img/logo.jpg" alt="Logo">
    </a>

    <nav class="nav-menu">
        <ul>
            <?php if (!$inst_id && $tipo !== 'funcionario' && $tipo !== 'voluntario'): ?>
                <li><a href="<?php echo BASE_URL; ?>pages/cadastro_voluntario.php">Cadastro Voluntário</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/login.php">Login</a></li>
                <!-- <li><a href='$loginUrl' style='padding:5px; background:#4285f4; color:white; text-decoration:none;'>Fazer Login com Google</a> -->

            <?php endif; ?>

            <?php if ($inst_id || $tipo === 'funcionario'): ?>
                <li class="perfil-cabecalho">
                    <?= exibirFotoUsuario(null, $nome_usuario) ?>
                    <span>Olá, <?php echo htmlspecialchars($nome_usuario); ?></span>
                </li>
                <li><a href="<?php echo BASE_URL; ?>pages/painel_funcionario.php">🏠 Voltar ao Painel</a></li>
                <li><a href="<?php echo BASE_URL; ?>actions/logout.php" class="btn-sair">Sair</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>