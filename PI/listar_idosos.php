<?php
require_once 'includes/helpers.php';
session_start();
require_once 'includes/db.php'; 

// PROTEÇÃO: Bloqueia quem não for funcionário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'funcionario') {
    header("Location: login.php");
    exit;
}

try {
    // 1. Descobrir de qual instituição é este funcionário
    $idFuncionarioLogado = $_SESSION['usuario_id'];
    $sqlInst = "SELECT instituicao_idinstituicao FROM funcionario WHERE idFuncionario = ?";
    $stmtInst = $pdo->prepare($sqlInst);
    $stmtInst->execute([$idFuncionarioLogado]);
    $idInstituicao = $stmtInst->fetchColumn();

    // 2. Buscar todos os idosos que pertencem a esta instituição (AGORA BUSCANDO A FOTO!)
    $sqlIdosos = "
        SELECT 
            i.idIdoso, 
            p.nmPessoa, 
            p.cpf, 
            p.dtNascimento, 
            p.fotoPerfil,  /* <-- Adicionamos a foto aqui! */
            i.necessidades, 
            i.aceita_visita, 
            i.aceita_carta 
        FROM idoso i
        JOIN pessoa p ON i.pessoa_idPessoa = p.idPessoa
        WHERE i.instituicao_idinstituicao = ?
        ORDER BY p.nmPessoa ASC
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
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="cabecalho">
        <div class="logo perfil-cabecalho">
            <?= exibirFotoUsuario(null, $_SESSION['usuario_nome']) ?>
            <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
        </div>
        
        <nav class="nav-menu">
            <ul>
                <li><a href="painel_funcionario.php">🏠 Voltar ao Painel</a></li>
                <li><a href="actions/logout.php" class="btn-sair" style="background: #5b3a26; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">Sair</a></li>
            </ul>
        </nav>
    </header>

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
                                <?= exibirFotoIdoso($idoso['fotoPerfil'], $idoso['nmPessoa']) ?>
                            </td>
                            
                            <td><strong><?= htmlspecialchars($idoso['nmPessoa']) ?></strong></td>
                            <td><?= htmlspecialchars($idoso['cpf']) ?></td>
                            <td><?= date('d/m/Y', strtotime($idoso['dtNascimento'])) ?></td>
                            
                            <td>
                                <?php if ($idoso['aceita_visita'] == 1): ?>
                                    <span class="badge badge-sim">Sim</span>
                                <?php else: ?>
                                    <span class="badge badge-nao">Não</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($idoso['aceita_carta'] == 1): ?>
                                    <span class="badge badge-sim">Sim</span>
                                <?php else: ?>
                                    <span class="badge badge-nao">Não</span>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <a href="editar_idoso.php?id=<?= $idoso['idIdoso'] ?>" class="btn-acao btn-editar">✏️ Editar</a>
                                <a href="actions/excluir_idoso.php?id=<?= $idoso['idIdoso'] ?>" class="btn-acao btn-excluir" onclick="return confirm('Tem certeza que deseja EXCLUIR o residente <?= htmlspecialchars($idoso['nmPessoa']) ?>? Isso não pode ser desfeito.');">🗑️ Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>