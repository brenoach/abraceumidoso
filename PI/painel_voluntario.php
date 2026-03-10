<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/helpers.php'; 

// PROTEÇÃO: Bloqueia quem não for voluntário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'voluntario') {
    header("Location: login.php");
    exit;
}

$idVoluntario = $_SESSION['usuario_id'];

try {
    // === BUSCA 1: AGENDAMENTOS DO VOLUNTÁRIO ===
    $sqlVisitas = "
        SELECT a.dtAgendamento, a.hrAgendamento, a.status, p.nmPessoa AS nome_idoso
        FROM agendamento a
        JOIN idoso i ON a.idoso_idIdoso = i.idIdoso
        JOIN pessoa p ON i.pessoa_idPessoa = p.idPessoa
        WHERE a.voluntario_idVoluntario = ?
        ORDER BY a.dtAgendamento DESC";
    $stmtVisitas = $pdo->prepare($sqlVisitas);
    $stmtVisitas->execute([$idVoluntario]);
    $minhasVisitas = $stmtVisitas->fetchAll(PDO::FETCH_ASSOC);

    // === BUSCA 2: VITRINE DE IDOSOS ===
    $sqlIdosos = "
        SELECT i.idIdoso, p.nmPessoa, p.dtNascimento, p.sobre, p.fotoPerfil, i.necessidades, inst.nmInstituicao 
        FROM idoso i
        JOIN pessoa p ON i.pessoa_idPessoa = p.idPessoa
        JOIN instituicao inst ON i.instituicao_idinstituicao = inst.idinstituicao
        WHERE i.aceita_visita = 1
        ORDER BY p.nmPessoa ASC";
    $idososDisponiveis = $pdo->query($sqlIdosos)->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $erro = "Erro: " . $e->getMessage();
}

function calcularIdade($dataNascimento) {
    $dataNasc = new DateTime($dataNascimento);
    $hoje = new DateTime('today');
    return $dataNasc->diff($hoje)->y;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Voluntário</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: #f9f7f3; font-family: sans-serif; margin: 0; }
        .cabecalho { display: flex; justify-content: space-between; padding: 15px 40px; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); align-items: center; }
        .boas-vindas { text-align: center; padding: 30px; background: linear-gradient(to right, #5b3a26, #8d5b3d); color: white; }
        .secao { width: 95%; max-width: 1200px; margin: 30px auto; }
        .tabela-status { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; }
        .tabela-status th, .tabela-status td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; }
        .badge-Pendente { background: #fff3e0; color: #e65100; }
        .badge-Aprovado { background: #e8f5e9; color: #2e7d32; }
        .badge-Recusado { background: #ffebee; color: #c62828; }
        .vitrine-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .card-idoso { background: #fff; border-radius: 10px; border: 1px solid #eee; overflow: hidden; display: flex; flex-direction: column; }
        .card-header { padding: 20px; background: #fdfaf5; text-align: center; }
        .card-body { padding: 15px; flex-grow: 1; }
        .btn-agendar { display: block; text-align: center; background: #4caf50; color: white; padding: 10px; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>

<header class="cabecalho">
    <div><strong>Abrace um Idoso</strong></div>
    <nav>
        <span style="margin-right: 20px;">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
        <a href="actions/logout.php" style="color: #c62828; font-weight: bold;">Sair</a>
    </nav>
</header>

<div class="boas-vindas">
    <h1>Minha Agenda de Amor ❤️</h1>
    <p>Acompanhe suas visitas e escolha novos amigos para visitar.</p>
</div>

<main class="secao">
    <h2 style="color: #5b3a26;">📅 Meus Agendamentos</h2>
    <?php if (empty($minhasVisitas)): ?>
        <p>Você ainda não solicitou nenhuma visita.</p>
    <?php else: ?>
        <table class="tabela-status">
            <thead>
                <tr style="background: #fdfaf5;">
                    <th>Data/Hora</th>
                    <th>Residente</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($minhasVisitas as $v): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($v['dtAgendamento'])) ?> às <?= date('H:i', strtotime($v['hrAgendamento'])) ?></td>
                    <td><strong><?= htmlspecialchars($v['nome_idoso']) ?></strong></td>
                    <td>
                        <span class="badge badge-<?= $v['status'] ?>"><?= $v['status'] ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<section class="secao">
    <h2 style="color: #5b3a26;">🎁 Residentes Disponíveis</h2>
    <div class="vitrine-container">
        <?php foreach ($idososDisponiveis as $idoso): ?>
        <div class="card-idoso">
            <div class="card-header">
                <?php if(function_exists('exibirFotoIdoso')) echo exibirFotoIdoso($idoso['fotoPerfil'], $idoso['nmPessoa']); ?>
                <h3><?= htmlspecialchars($idoso['nmPessoa']) ?></h3>
                <span><?= calcularIdade($idoso['dtNascimento']) ?> anos</span>
            </div>
            <div class="card-body">
                <small>📍 <?= htmlspecialchars($idoso['nmInstituicao']) ?></small>
                <p style="font-size: 0.9em; color: #555;"><?= htmlspecialchars(mb_strimwidth($idoso['sobre'], 0, 100, "...")) ?></p>
            </div>
            <div style="padding: 15px; border-top: 1px solid #eee;">
                <a href="agendar_visita.php?id=<?= $idoso['idIdoso'] ?>" class="btn-agendar">Agendar Visita</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

</body>
</html>