<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../connection/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';

// Pegamos o ID da sessão
$id_logado = $_SESSION['idPessoa'] ?? null;
$tipo = $_SESSION['usuario_tipo'] ?? null;

$nome_exibir = '';
$foto_exibir = '';
$unidade_exibir = '';

if ($id_logado) {
    if ($tipo === 'funcionario') {
        $sql = "SELECT p.nomePessoa, p.fotoPerfil, i.nomeInstituicao 
                FROM pessoa p
                JOIN funcionario f ON p.idPessoa = f.idPessoa
                JOIN instituicao i ON f.idInstituicao = i.idInstituicao
                WHERE p.idPessoa = ?";
    } else {
        $sql = "SELECT nomePessoa, fotoPerfil FROM pessoa WHERE idPessoa = ?";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_logado]);
    $dados = $stmt->fetch();

    if ($dados) {
        $nome_exibir = $dados['nomePessoa'];
        $foto_exibir = $dados['fotoPerfil'];
        $unidade_exibir = $dados['nomeInstituicao'] ?? '';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrace um Idoso</title>
    <link rel="stylesheet" href="<?php echo BASE_URL;?>assets/css/style.css?v=1.6">
    <style>
        .perfil-cabecalho { display: flex; align-items: center; gap: 10px; padding: 5px 15px; border-right: 1px solid #ddd; }
        .user-avatar img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #673AB7; }
        .user-info { display: flex; flex-direction: column; line-height: 1.2; }
        .user-info strong { color: #5A3821; font-size: 0.9rem; }
        .user-info small { color: #673AB7; font-size: 0.7rem; font-weight: bold; }
    </style>
</head>
<body>

<header class="cabecalho">
    <a href="<?php echo BASE_URL;?>index.php" class="logo">
        <img src="<?php echo BASE_URL;?>assets/img/logo.jpg" alt="Logo">
    </a>

    <nav class="nav-menu">
        <ul>
            <?php if (!$id_logado): ?>
                <li><a href="<?php echo BASE_URL; ?>pages/cadastro_voluntario.php">Cadastro Voluntário</a></li>
                <li><a href="<?php echo BASE_URL; ?>pages/login.php" class="btn-login">Login</a></li>

            <?php else: ?>
                <li class="perfil-cabecalho">
                    <div class="user-avatar">
                        <?php echo exibirFoto($foto_exibir, $nome_exibir, 'usuario'); ?>
                    </div>
                    <div class="user-info">
                        <strong>Olá, <?= htmlspecialchars(explode(' ', $nome_exibir)[0] ?? '') ?></strong>
                        <?php if ($unidade_exibir): ?>
                            <small>📍 <?= htmlspecialchars($unidade_exibir) ?></small>
                        <?php endif; ?>
                    </div>
                </li>

                <li>
                    <a href="<?php echo BASE_URL; ?>pages/painel_<?= $tipo ?>.php">🏠 Painel</a>
                </li>
                
                <li><a href="<?php echo BASE_URL; ?>actions/logout.php" style="color: #e11d48; font-weight: bold;">Sair</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>