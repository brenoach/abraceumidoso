<?php
session_start();
require_once '../includes/auth.php';
verificarAcesso('funcionario');
require_once '../includes/db.php';
require_once '../includes/helpers.php'; 
include '../includes/header.php';


$idInst = $_SESSION['instituicao_id'];

// SQL robusto para buscar visitas
$sql = "SELECT a.idAgendamento, a.dataAgendamento, a.horaAgendamento, a.status, 
               p_idoso.nomePessoa AS nome_idoso, p_vol.nomePessoa AS nome_voluntario
        FROM agendamento a
        JOIN idoso i ON a.idIdoso = i.idIdoso
        JOIN pessoa p_idoso ON i.idPessoa = p_idoso.idPessoa
        JOIN voluntario v ON a.idVoluntario = v.idVoluntario
        JOIN pessoa p_vol ON v.idPessoa = p_vol.idPessoa
        WHERE i.idInstituicao = ?
        ORDER BY a.dataAgendamento ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$idInst]);
$visitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    
<body>

    <div class="container">
    <h1 style="color: #5b3a26;">Painel Administrativo</h1>
    
    <div class="atalhos-grid">
        <div class="card-atalho">
            <span>👴👵</span>
            <h3>Nossos Residentes</h3>
            <p>Cadastre novos idosos na sua unidade.</p>
            <a href="cadastrar_idoso.php" class="btn-principal">Cadastrar Novo</a>
        </div>

        <div class="card-atalho">
            <span>📋</span>
            <h3>Lista de Residentes</h3>
            <p>Gerencie quem já está cadastrado.</p>
            <a href="listar_idosos.php" class="btn-principal">Ver Lista Completa</a>
        </div>
    </div>

    <h2 style="color: #5b3a26; margin-bottom: 20px;">📅 Agenda de Visitas</h2>
    <div class="tabela-container">
        <table>
            <thead>
                <tr>
                    <th>Data e Hora</th>
                    <th>Voluntário</th>
                    <th>Residente</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($visitas as $v): ?>
                <tr>
                    <td><img src="<?php echo exibirFoto($idoso['fotoPerfil'], $idoso['nomePessoa'], 'idoso');?>"></td>
                    <td><strong><?= date('d/m/Y', strtotime($v['dataAgendamento'])) ?></strong><br><small><?= $v['horaAgendamento'] ?></small></td>
                    <td><?= htmlspecialchars($v['nome_voluntario']) ?></td>
                    <td><?= htmlspecialchars($v['nome_idoso']) ?></td>
                    <td><span class="badge badge-<?= $v['status'] ?>"><?= $v['status'] ?></span></td>
                    <td>
                        <?php if ($v['status'] == 'Pendente'): ?>
                            <a href="actions/processar_visita.php?id=<?= $v['idAgendamento'] ?>&acao=Aprovado" class="acao-link" style="color: var(--sucesso);">Aprovar</a>
                            <a href="actions/processar_visita.php?id=<?= $v['idAgendamento'] ?>&acao=Recusado" class="acao-link" style="color: var(--cancelado);">Recusar</a>
                        <?php elseif ($v['status'] == 'Aprovado'): ?>
                            <a href="actions/processar_visita.php?id=<?= $v['idAgendamento'] ?>&acao=Cancelado" class="cancelar-btn" onclick="return confirm('Confirmar cancelamento?')">Cancelar Visita</a>
                        <?php else: ?>
                            <span style="color:#bbb">-</span>
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
