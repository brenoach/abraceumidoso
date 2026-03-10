<?php
require_once 'includes/auth.php';
verificarAcesso('funcionario');
require_once 'includes/db.php';

$idInst = $_SESSION['instituicao_id'];

// SQL robusto para buscar visitas
$sql = "SELECT a.idAgendamento, a.dtAgendamento, a.hrAgendamento, a.status, 
               p_idoso.nmPessoa AS nome_idoso, p_vol.nmPessoa AS nome_voluntario
        FROM agendamento a
        JOIN idoso i ON a.idoso_idIdoso = i.idIdoso
        JOIN pessoa p_idoso ON i.pessoa_idPessoa = p_idoso.idPessoa
        JOIN voluntario v ON a.voluntario_idVoluntario = v.idVoluntario
        JOIN pessoa p_vol ON v.pessoa_idPessoa = p_vol.idPessoa
        WHERE i.instituicao_idinstituicao = ?
        ORDER BY a.dtAgendamento ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idInst]);
$visitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="assets/css/style.css"> </head>
<body>

<header class="cabecalho">
    <div class="logo"><strong>ABRACE UM IDOSO</strong></div>
    <div>
        <span>Olá, <?= $_SESSION['usuario_nome'] ?></span>
        <a href="actions/logout.php" class="btn-sair">Sair</a>
    </div>
</header>

<div class="container">
    <div class="linha">
        <div class="card">
            <h3>👴 Cadastrar Idoso</h3>
            <p>Adicione novos residentes à unidade.</p>
            <a href="cadastrar_idoso.php" class="btn-acao">Novo Cadastro</a>
        </div>
        <div class="card">
            <h3>📋 Lista de Idosos</h3>
            <p>Gerencie quem já está cadastrado.</p>
            <a href="listar_idosos.php" class="btn-acao">Ver Lista</a>
        </div>
    </div>

    <h2>📅 Agenda de Visitas</h2>
    <div class="tabela-container">
        <table>
            <thead>
                <tr>
                    <th>Data/Hora</th>
                    <th>Voluntário</th>
                    <th>Residente</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visitas as $v): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($v['dtAgendamento'])) ?><br><small><?= $v['hrAgendamento'] ?></small></td>
                    <td><?= htmlspecialchars($v['nome_voluntario']) ?></td>
                    <td><?= htmlspecialchars($v['nome_idoso']) ?></td>
                    <td><span class="badge badge-<?= $v['status'] ?>"><?= $v['status'] ?></span></td>
                    <td>
                        <?php if ($v['status'] == 'Pendente'): ?>
                            <a href="actions/processar_visita.php?id=<?= $v['idAgendamento'] ?>&acao=Aprovado" class="link-aprovar">Aprovar</a>
                            <a href="actions/processar_visita.php?id=<?= $v['idAgendamento'] ?>&acao=Recusado" style="color:red; text-decoration:none;">Recusar</a>
                        
                        <?php elseif ($v['status'] == 'Aprovado'): ?>
                            <a href="actions/processar_visita.php?id=<?= $v['idAgendamento'] ?>&acao=Cancelado" 
                               class="link-cancelar" onclick="return confirm('Confirmar cancelamento?')">⚠️ Cancelar</a>
                        
                        <?php else: ?>
                            <span style="color:#bbb">Sem ações</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>