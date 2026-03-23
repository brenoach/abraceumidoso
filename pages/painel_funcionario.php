<?php
session_start();
require_once '../includes/auth.php';
verificarAcesso('funcionario');
require_once '../includes/db.php';
require_once '../includes/helpers.php'; 
require_once '../includes/header.php';


$idInst = $_SESSION['instituicao_id'];

// SQL robusto para buscar visitas
// 1. Pegamos o ID da instituição da sessão (para o funcionário ver só os idosos dele)
$idInst = $_SESSION['usuario_id_instituicao']; 

// 2. A Query Mestra
$sql = "SELECT 
            i.idIdoso,
            p.nomePessoa,
            p.fotoPerfil,
            p.sobre,
            i.aceitaVisita,
            GROUP_CONCAT(
                CONCAT(d.dia_semana, ' (', TIME_FORMAT(d.hora_inicio, '%H:%i'), '-', TIME_FORMAT(d.hora_fim, '%H:%i'), ')') 
                SEPARATOR ' | '
            ) AS agenda_completa
        FROM idoso i
        JOIN pessoa p ON i.idPessoa = p.idPessoa
        LEFT JOIN disponibilidade d ON i.idIdoso = d.idoso_idIdoso
        WHERE i.idInstituicao = :idInst
        GROUP BY i.idIdoso
        ORDER BY p.nomePessoa ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['idInst' => $idInst]);
$listaIdosos = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

<div class="grid-idosos">
    <?php foreach ($listaIdosos as $idoso): ?>
        <div class="card-residente">
            <div class="foto">
                <?php echo exibirFoto($idoso['fotoPerfil'], $idoso['nomePessoa'], 'idoso'); ?>
            </div>

            <div class="info">
                <h3><?php echo $idoso['nomePessoa']; ?></h3>
                <p class="sobre"><?php echo $idoso['sobre']; ?></p>
                
                <div class="agenda-bloco">
                    <strong>Horários de Visita:</strong><br>
                    <small>
                        <?php echo $idoso['agenda_completa'] ?: 'Nenhum horário cadastrado.'; ?>
                    </small>
                </div>
            </div>
            
            <div class="acoes">
                <a href="editar_idoso.php?id=<?php echo $idoso['idIdoso']; ?>" class="btn-edit">Editar</a>
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
