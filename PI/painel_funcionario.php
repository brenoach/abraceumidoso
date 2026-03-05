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
    // === BUSCA 1: AS VISITAS DESTE VOLUNTÁRIO ===
    $sqlVisitas = "
        SELECT 
            a.idAgendamento, 
            a.data_visita, 
            a.hora_inicio, 
            a.status,
            p.nmPessoa AS nome_idoso,
            inst.nomeInstituicao,
            inst.cidade
        FROM agendamento a
        JOIN idoso i ON a.idoso_idIdoso = i.idIdoso
        JOIN pessoa p ON i.pessoa_idPessoa = p.idPessoa
        JOIN instituicao inst ON i.instituicao_idinstituicao = inst.idinstituicao
        WHERE a.voluntario_idVoluntario = ?
        ORDER BY a.data_visita ASC, a.hora_inicio ASC
    ";
    $stmtVisitas = $pdo->prepare($sqlVisitas);
    $stmtVisitas->execute([$idVoluntario]);
    $minhasVisitas = $stmtVisitas->fetchAll(PDO::FETCH_ASSOC);

    // === BUSCA 2: A VITRINE DE IDOSOS DISPONÍVEIS ===
    $sqlIdosos = "
        SELECT 
            i.idIdoso, p.nmPessoa, p.dtNascimento, p.sobre, p.fotoPerfil, 
            i.necessidades, inst.nomeInstituicao 
        FROM idoso i
        JOIN pessoa p ON i.pessoa_idPessoa = p.idPessoa
        JOIN instituicao inst ON i.instituicao_idinstituicao = inst.idinstituicao
        WHERE i.aceita_visita = 1
        ORDER BY p.nmPessoa ASC
    ";
    $stmtIdosos = $pdo->query($sqlIdosos);
    $idososDisponiveis = $stmtIdosos->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $erro = "Erro ao carregar dados: " . $e->getMessage();
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
    <title>Painel do Voluntário - Abrace um Idoso</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: #f9f7f3; color: #333; margin: 0; font-family: sans-serif; }
        
        /* CABEÇALHO */
        .cabecalho { display: flex; justify-content: space-between; align-items: center; padding: 15px 40px; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .nav-menu ul { list-style: none; display: flex; gap: 15px; margin: 0; padding: 0; align-items: center; }
        .nav-menu a { text-decoration: none; color: #5b3a26; font-weight: bold; padding: 10px 15px; border-radius: 6px; background: #fdfaf5; transition: 0.2s; }
        .nav-menu a:hover { background: #5b3a26; color: #fff; }
        .btn-sair { background: #5b3a26 !important; color: white !important; }

        /* HERO SECTION */
        .boas-vindas { text-align: center; padding: 40px 20px; background: linear-gradient(to right, #5b3a26, #8d5b3d); color: white; margin-bottom: 40px; }
        .boas-vindas h1 { margin: 0 0 10px 0; font-size: 2.5em; }
        .boas-vindas p { font-size: 1.2em; max-width: 600px; margin: 0 auto; opacity: 0.9; }

        /* SEÇÃO DE MINHAS VISITAS */
        .secao-dashboard { width: 95%; max-width: 1200px; margin: 0 auto 50px auto; }
        .titulo-secao { color: #5b3a26; border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 20px; font-size: 1.8em; }
        
        .tabela-visitas { width: 100%; border-collapse: collapse; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .tabela-visitas th, .tabela-visitas td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #eee; }
        .tabela-visitas th { background: #fdfaf5; color: #5b3a26; font-weight: bold; }
        .tabela-visitas tr:last-child td { border-bottom: none; }
        .tabela-visitas tr:hover { background: #fafafa; }
        
        /* Badges de Status das Visitas */
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.85em; font-weight: bold; }
        .badge-pendente { background: #fff3e0; color: #e65100; }
        .badge-confirmado { background: #e8f5e9; color: #2e7d32; }
        .badge-cancelado { background: #ffebee; color: #c62828; }

        .box-vazio { background: #fff; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); color: #666; font-style: italic; }

        /* GRID DE CARTÕES (VITRINE) */
        .vitrine-container { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px; }
        .card-idoso { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.08); transition: transform 0.3s; display: flex; flex-direction: column; border: 1px solid #eee; }
        .card-idoso:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
        .card-header { text-align: center; padding: 25px 20px 10px 20px; background: #fdfaf5; border-bottom: 1px solid #f0e6d2; }
        .card-header img.foto-avatar { width: 100px; height: 100px; border: 4px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1); margin-bottom: 15px; }
        .card-header h3 { margin: 0; color: #5b3a26; font-size: 1.4em; }
        .card-header span { color: #666; font-size: 0.9em; }
        .card-body { padding: 20px; flex-grow: 1; }
        .tag-instituicao { display: inline-block; background: #eef5ee; color: #2e7d32; padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; margin-bottom: 15px; }
        .info-label { font-weight: bold; color: #5b3a26; font-size: 0.9em; display: block; margin-top: 10px; }
        .info-texto { color: #555; font-size: 0.95em; line-height: 1.5; margin-top: 5px; }
        .card-footer { padding: 20px; background: #fff; border-top: 1px solid #eee; text-align: center; }
        .btn-agendar { display: block; width: 100%; padding: 12px 0; background: #4caf50; color: white; text-decoration: none; font-weight: bold; border-radius: 6px; transition: 0.2s; box-sizing: border-box; }
        .btn-agendar:hover { background: #388e3c; }
    </style>
</head>
<body>
    <header class="cabecalho">
        <div class="logo perfil-cabecalho">
            <?= exibirFotoUsuario(null, $_SESSION['usuario_nome']) ?>
            <span>Olá, <?= htmlspecialchars($_SESSION['usuario_nome']); ?></span>
        </div>
        <nav class="nav-menu">
            <ul>
                <li><a href="#minhas-visitas">📅 Minhas Visitas</a></li>
                <li><a href="#vitrine">❤️ Doar Amor</a></li>
                <li><a href="actions/logout.php" class="btn-sair">Sair</a></li>
            </ul>
        </nav>
    </header>

    <div class="boas-vindas">
        <h1>Faça a diferença hoje!</h1>
        <p>Doar o seu tempo é o maior presente que você pode dar. Acompanhe seus agendamentos e conheça novas histórias.</p>
    </div>

    <main class="secao-dashboard" id="minhas-visitas">
        <h2 class="titulo-secao">📅 Meus Agendamentos</h2>

        <?php if (empty($minhasVisitas)): ?>
            <div class="box-vazio">
                Você ainda não tem nenhuma visita agendada. Escolha um residente abaixo e faça a diferença!
            </div>
        <?php else: ?>
            <table class="tabela-visitas">
                <thead>
                    <tr>
                        <th>Data e Hora</th>
                        <th>Residente</th>
                        <th>Instituição / Local</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($minhasVisitas as $visita): ?>
                        <tr>
                            <td>
                                <strong><?= date('d/m/Y', strtotime($visita['data_visita'])) ?></strong><br>
                                <span style="color: #666; font-size: 0.9em;">às <?= date('H:i', strtotime($visita['hora_inicio'])) ?></span>
                            </td>
                            <td><strong><?= htmlspecialchars($visita['nome_idoso']) ?></strong></td>
                            <td><?= htmlspecialchars($visita['nomeInstituicao']) ?> - <?= htmlspecialchars($visita['cidade']) ?></td>
                            <td>
                                <?php 
                                    // Pinta a etiqueta dependendo do status
                                    $status = $visita['status'];
                                    $classeBadge = 'badge-pendente'; // padrão
                                    if ($status == 'Confirmado') $classeBadge = 'badge-confirmado';
                                    if ($status == 'Cancelado') $classeBadge = 'badge-cancelado';
                                ?>
                                <span class="badge <?= $classeBadge ?>"><?= htmlspecialchars($status) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>

    <section class="secao-dashboard" id="vitrine">
        <h2 class="titulo-secao">❤️ Residentes aguardando por você</h2>
        
        <div class="vitrine-container">
            <?php if (isset($erro)): ?>
                <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 5px; grid-column: 1 / -1;"><?= $erro ?></div>
            <?php elseif (empty($idososDisponiveis)): ?>
                <div class="box-vazio" style="grid-column: 1 / -1;">
                    Puxa... Nenhum residente disponível para visitas no momento. Volte mais tarde!
                </div>
            <?php else: ?>
                <?php foreach ($idososDisponiveis as $idoso): ?>
                    <div class="card-idoso">
                        <div class="card-header">
                            <?= exibirFotoIdoso($idoso['fotoPerfil'], $idoso['nmPessoa']) ?>
                            <h3><?= htmlspecialchars($idoso['nmPessoa']) ?></h3>
                            <span>Idade: <?= calcularIdade($idoso['dtNascimento']) ?> anos</span>
                        </div>
                        
                        <div class="card-body">
                            <span class="tag-instituicao">📍 <?= htmlspecialchars($idoso['nomeInstituicao']) ?></span>
                            
                            <?php if (!empty($idoso['necessidades'])): ?>
                                <span class="info-label">Condição/Necessidades:</span>
                                <div class="info-texto"><?= htmlspecialchars($idoso['necessidades']) ?></div>
                            <?php endif; ?>

                            <span class="info-label">História de Vida:</span>
                            <div class="info-texto">
                                <?= htmlspecialchars(mb_strimwidth($idoso['sobre'], 0, 120, "...")) ?>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <a href="agendar_visita.php?id=<?= $idoso['idIdoso'] ?>" class="btn-agendar">❤️ Agendar Visita</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</body>
</html>