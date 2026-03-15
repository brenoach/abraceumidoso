<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/helpers.php'; 
include '../includes/header.php';



// PROTEÇÃO: Bloqueia quem não for voluntário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'voluntario') {
    header("Location: login.php");
    exit;
}

$idVoluntario = $_SESSION['usuario_id'];

try {
    // Busca idosos que aceitam visita, juntando os dados da Pessoa e da Instituição
    $sqlIdosos = "SELECT 
                    i.idIdoso, 
                    p.nomePessoa, 
                    p.fotoPerfil, 
                    p.sobre,
                    p.dataNascimento,
                    inst.nomeInstituicao 
                  FROM idoso i
                  JOIN pessoa p ON i.idPessoa = p.idPessoa
                  JOIN instituicao inst ON i.idInstituicao = inst.idInstituicao
                  WHERE i.aceitaVisita = 1";
                  
    $stmtIdosos = $pdo->query($sqlIdosos);
    
    // Salva o resultado na variável que o seu foreach está pedindo!
    $idososDisponiveis = $stmtIdosos->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Se der algum erro no banco, cria um array vazio para o site não "quebrar" (o foreach não vai dar erro)
    $idososDisponiveis = []; 
    // echo "Erro ao buscar idosos: " . $e->getMessage(); // Descomente para debugar se precisar
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
   
   
</head>
<body>

<header class="cabecalho">
    <div><strong>Painel do Voluntário</strong></div>
    <nav>
        <span style="margin-right: 20px;">Olá, <?= htmlspecialchars($_SESSION['usuario_nome']) ?></span>
        <a href="<?php echo BASE_URL; ?>actions/logout.php" style="color: #c62828; font-weight: bold;">Sair</a>
    </nav>
</header>

<div class="boas-vindas">
    <h1>Minha Agenda de Visitas</h1>
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
                    <td><?= date('d/m/Y', strtotime($v['dataAgendamento'])) ?> às <?= date('H:i', strtotime($v['horaAgendamento'])) ?></td>
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
                <?php if(function_exists('exibirFotoIdoso')) echo exibirFotoIdoso($idoso['fotoPerfil'], $idoso['nomePessoa']); ?>
                <h3><?= htmlspecialchars($idoso['nomePessoa']) ?></h3>
                <span><?= calcularIdade($idoso['dataNascimento']) ?> anos</span>
            </div>
            <div class="card-body">
                <small>📍 <?= htmlspecialchars($idoso['nomeInstituicao']) ?></small>
                <p style="font-size: 0.9em; color: #555;"><?= htmlspecialchars(mb_strimwidth($idoso['sobre'], 0, 100, "...")) ?></p>
            </div>
            <div style="padding: 15px; border-top: 1px solid #eee;">
                <a href="agendar_visita.php?id=<?= $idoso['idIdoso'] ?>" class="btn-agendar">Agendar Visita</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?include '../includes/footer.php';?>
</body>
</html>