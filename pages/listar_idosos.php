<?php
session_start();
require_once '../includes/helpers.php';
require_once '../includes/db.php'; 
include '../includes/header.php';

// PROTEÇÃO: Bloqueia quem não for funcionário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'funcionario') {
    header("Location: login.php");
    exit;
}

try {
    // 1. Descobrir de qual instituição é este funcionário
    $idFuncionarioLogado = $_SESSION['usuario_id'];
    $sqlInst = "SELECT idInstituicao FROM funcionario WHERE idFuncionario = ?";
    $stmtInst = $pdo->prepare($sqlInst);
    $stmtInst->execute([$idFuncionarioLogado]);
    $idInstituicao = $stmtInst->fetchColumn();

    // 2. Buscar todos os idosos que pertencem a esta instituição (AGORA BUSCANDO A FOTO!)
    $sqlIdosos = "
        SELECT 
            i.idIdoso, 
            p.nomePessoa, 
            p.cpf, 
            p.dataNascimento, 
            p.fotoPerfil,  /* <-- Adicionamos a foto aqui! */
            p.sobre, 
            i.aceitaVisita, 
            i.aceitaCarta 
        FROM idoso i
        JOIN pessoa p ON i.idPessoa = p.idPessoa
        WHERE i.idInstituicao = ?
        ORDER BY p.nomePessoa ASC
    ";
    $stmtIdosos = $pdo->prepare($sqlIdosos);
    $stmtIdosos->execute([$idInstituicao]);
    $listaIdosos = $stmtIdosos->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    $erro = "Erro ao buscar residentes: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Residentes - Abrace um Idoso</title>
    
</head>
<body>
    

    <main class="painel-container" style="max-width: 1200px; margin: 40px auto; padding: 0 20px;">
        <h2 style="color: #5b3a26; border-bottom: 2px solid #eee; padding-bottom: 10px;">⚙️ Gerenciar Residentes</h2>
        <p style="color: #666;">Aqui você pode visualizar, alterar horários/permissões ou remover um residente do sistema.</p>

        <?php if (isset($erro)): ?>
            <div style="background: #ffebee; color: #c62828; padding: 15px; border-radius: 5px;"><?= $erro ?></div>
        <?php elseif (empty($listaIdosos)): ?>
            <div style="background: #fff; padding: 30px; border-radius: 8px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                <p style="color: #666; font-size: 1.1em;">Nenhum residente cadastrado nesta instituição ainda.</p>
                <a href="painel_funcionario.php" style="color: #5b3a26; font-weight: bold;">Clique aqui para cadastrar o primeiro!</a>
            </div>
        <?php else: ?>
            <table class="tabela-gerenciamento">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nome do Residente</th>
                        <th>CPF</th>
                        <th>Nascimento</th>
                        <th>Visitas</th>
                        <th>Cartas</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listaIdosos as $idoso): ?>
                        <tr>
                            <td style="text-align: center;">
                                <img src="<?php echo exibirFoto($idoso['fotoPerfil'], $idoso['nomePessoa'], 'idoso');?>" alt="Foto do Resident ">
                                
                            
                            <td><strong><?= htmlspecialchars($idoso['nomePessoa']) ?></strong></td>
                            <td><?= htmlspecialchars($idoso['cpf']) ?></td>
                            <td><?= date('d/m/Y', strtotime($idoso['dataNascimento'])) ?></td>
                            
                            <td>
                                <?php if ($idoso['aceitaVisita'] == 1): ?>
                                    <span class="badge badge-sim">Sim</span>
                                <?php else: ?>
                                    <span class="badge badge-nao">Não</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($idoso['aceitaCarta'] == 1): ?>
                                    <span class="badge badge-sim">Sim</span>
                                <?php else: ?>
                                    <span class="badge badge-nao">Não</span>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <a href="editar_idoso.php?id=<?= $idoso['idIdoso'] ?>" class="btn-acao btn-editar">✏️ Editar</a>
                                <a href="actions/excluir_idoso.php?id=<?= $idoso['idIdoso'] ?>" class="btn-acao btn-excluir" onclick="return confirm('Tem certeza que deseja EXCLUIR o residente <?= htmlspecialchars($idoso['nomePessoa']) ?>? Isso não pode ser desfeito.');">🗑️ Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>