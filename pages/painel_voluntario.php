<?php
require_once __DIR__ . '/../connection/config.php';
require_once __DIR__ . '/../includes/auth.php';
verificarAcesso('voluntario'); 

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php'; // Aqui está a sua função exibirFoto!
require_once __DIR__ . '/../includes/header.php';

$id_logado = $_SESSION['idPessoa'];
$nome_voluntario = $_SESSION['nome'];

try {
    // 1. HISTÓRICO (Estilo lista Netflix)
    $sqlMinhasVisitas = "SELECT 
                            a.dataAgendamento, a.horaAgendamento, a.status,
                            p.nomePessoa AS nome_idoso, p.fotoPerfil AS foto_idoso
                         FROM agendamento a
                         JOIN idoso i ON a.idIdoso = i.idIdoso
                         JOIN pessoa p ON i.idPessoa = p.idPessoa
                         JOIN voluntario v ON a.idVoluntario = v.idVoluntario
                         WHERE v.idPessoa = ?
                         ORDER BY a.dataAgendamento ASC";
    
    $stmtMinhas = $pdo->prepare($sqlMinhasVisitas);
    $stmtMinhas->execute([$id_logado]);
    $minhasVisitas = $stmtMinhas->fetchAll(PDO::FETCH_ASSOC);

    // 2. VITRINE (Cards de Idosos)
    $sqlIdosos = "SELECT 
                    i.idIdoso, p.nomePessoa, p.fotoPerfil, p.sobre, p.dataNascimento,
                    inst.nomeInstituicao 
                  FROM idoso i
                  JOIN pessoa p ON i.idPessoa = p.idPessoa
                  JOIN instituicao inst ON i.idInstituicao = inst.idInstituicao
                  WHERE i.aceitaVisita = 1
                  ORDER BY p.nomePessoa ASC";
                  
    $stmtIdosos = $pdo->query($sqlIdosos);
    $idososDisponiveis = $stmtIdosos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $minhasVisitas = [];
    $idososDisponiveis = [];
}
?>

<style>
    /* Estilos para as fotos da sua função helpers.php */
    .foto-perfil-idoso {
        width: 100%; height: 100%; object-fit: cover;
        border-radius: 50%; /* Garante que o avatar das iniciais também fique redondo */
    }
    
    .netflix-list { display: flex; flex-direction: column; gap: 12px; padding: 20px; }
    .netflix-item { 
        display: flex; align-items: center; background: #fff; padding: 15px; 
        border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); gap: 15px;
    }
    .img-container-mini { width: 50px; height: 50px; flex-shrink: 0; }
    
    .vitrine-grid { 
        display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); 
        gap: 20px; padding: 20px; 
    }
    .card-vovo { 
        background: #fff; border-radius: 20px; overflow: hidden; 
        box-shadow: 0 8px 15px rgba(0,0,0,0.1); transition: 0.3s;
        border: 1px solid #eee;
    }
    .card-vovo:hover { transform: scale(1.03); }
    .img-container-card { height: 150px; width: 100%; background: #f0f0f0; display: flex; justify-content: center; padding: 15px; }
    .img-container-card .foto-perfil-idoso { width: 120px; height: 120px; border: 4px solid #673AB7; }
</style>

<div style="padding: 30px 20px; background: #673AB7; color: white; border-radius: 0 0 25px 25px;">
    <h2 style="margin:0;">Olá, <?= htmlspecialchars($nome_voluntario) ?>!</h2>
    <p style="margin:5px 0 0; opacity: 0.8;">Sua próxima visita pode mudar um dia inteiro.</p>
</div>

<div class="netflix-list">
    <h3 style="color: #5b3a26; margin-left: 5px;">📅 Minha Agenda</h3>
    <?php if (empty($minhasVisitas)): ?>
        <p style="color: #999; margin-left: 5px;">Nenhuma visita marcada ainda.</p>
    <?php else: ?>
        <?php foreach ($minhasVisitas as $v): ?>
        <div class="netflix-item">
            <div class="img-container-mini">
                <?= exibirFoto($v['foto_idoso'], $v['nome_idoso']) ?>
            </div>
            <div style="flex-grow: 1;">
                <strong style="color: #333;"><?= htmlspecialchars($v['nome_idoso']) ?></strong><br>
                <small style="color: #666;"><?= date('d/m/Y', strtotime($v['dataAgendamento'])) ?> às <?= date('H:i', strtotime($v['horaAgendamento'])) ?></small>
            </div>
            <span style="font-size: 0.8rem; font-weight: bold; color: #673AB7; text-transform: uppercase;">
                <?= $v['status'] ?>
            </span>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<h3 style="color: #5b3a26; margin: 20px 0 0 25px;">🎁 Residentes Disponíveis</h3>
<div class="vitrine-grid">
    <?php foreach ($idososDisponiveis as $idoso): ?>
    <div class="card-vovo">
        <div class="img-container-card">
            <?= exibirFoto($idoso['fotoPerfil'], $idoso['nomePessoa']) ?>
        </div>
        
        <div style="padding: 15px; text-align: center;">
            <h4 style="margin: 0; color: #5A3821;"><?= htmlspecialchars($idoso['nomePessoa']) ?></h4>
            <small style="color: #673AB7;"><?= htmlspecialchars($idoso['nomeInstituicao']) ?></small>
            <p style="font-size: 0.8rem; color: #777; margin: 10px 0;">
                <?= htmlspecialchars(mb_strimwidth($idoso['sobre'], 0, 80, "...")) ?>
            </p>
            <a href="agendar_visita.php?id=<?= $idoso['idIdoso'] ?>" 
               style="background: #673AB7; color: white; text-decoration: none; padding: 8px 15px; border-radius: 15px; font-size: 0.85rem; font-weight: bold; display: inline-block;">
                AGENDAR
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>