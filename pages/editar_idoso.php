<?php
session_start();
require_once 'includes/db.php'; 

// PROTEÇÃO: Bloqueia quem não for funcionário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'funcionario') {
    header("Location: login.php");
    exit;
}

// 1. Verifica se o ID foi passado na URL (ex: editar_idoso.php?id=5)
if (!isset($_GET['id'])) {
    die("Erro: Nenhum residente selecionado para edição.");
}
$idIdoso = $_GET['id'];

try {
    // 2. Segurança: Descobrir a instituição do funcionário logado
    $idFuncionarioLogado = $_SESSION['usuario_id'];
    $sqlInst = "SELECT instituicao_idinstituicao FROM funcionario WHERE idFuncionario = ?";
    $stmtInst = $pdo->prepare($sqlInst);
    $stmtInst->execute([$idFuncionarioLogado]);
    $idInstituicao = $stmtInst->fetchColumn();

    // 3. Buscar os dados DESTE idoso específico, garantindo que ele é da mesma instituição
    $sqlIdoso = "
        SELECT 
            i.idIdoso, i.necessidades, i.aceita_visita, i.aceita_carta, i.pessoa_idPessoa,
            p.nmPessoa, p.cpf, p.dtNascimento, p.sobre, p.fotoPerfil
        FROM idoso i
        JOIN pessoa p ON i.pessoa_idPessoa = p.idPessoa
        WHERE i.idIdoso = ? AND i.instituicao_idinstituicao = ?
    ";
    $stmtIdoso = $pdo->prepare($sqlIdoso);
    $stmtIdoso->execute([$idIdoso, $idInstituicao]);
    $idoso = $stmtIdoso->fetch(PDO::FETCH_ASSOC);

    // Se um funcionário tentar editar o idoso de OUTRA instituição alterando o número na URL, ele é bloqueado aqui:
    if (!$idoso) {
        die("Residente não encontrado ou você não tem permissão para editá-lo.");
    }

} catch (Exception $e) {
    die("Erro no banco de dados: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Residente - Abrace um Idoso</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: #f9f7f3; color: #333; margin: 0; font-family: sans-serif; }
        .cabecalho { display: flex; justify-content: space-between; align-items: center; padding: 15px 40px; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .cabecalho .logo { color: #5b3a26; font-weight: bold; font-size: 1.2em; text-transform: uppercase; }
        .nav-menu ul { list-style: none; display: flex; gap: 15px; margin: 0; padding: 0; }
        .nav-menu a { text-decoration: none; color: #5b3a26; font-weight: bold; padding: 10px 15px; border-radius: 6px; background: #fdfaf5; transition: 0.2s; }
        .painel-container { width: 95%; max-width: 1400px; margin: 30px auto; }
        .painel-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 30px; align-items: start; }
        .coluna-cadastro, .coluna-preferencias { background: #ffffff; padding: 30px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .coluna-preferencias { background: #fdfaf5; border: 1px solid #f0e6d2; }
        .grupo-input { margin-bottom: 15px; }
        .grupo-input label { font-weight: bold; color: #5b3a26; margin-bottom: 8px; display: block; }
        .grupo-input input, .grupo-input textarea { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        .linha-dupla { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .toggle-switch { background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #e0e0e0; margin-bottom: 15px; }
        .titulo-toggle { font-weight: bold; display: block; margin-bottom: 10px; color: #5b3a26; }
        .btn-marrom { background: #5b3a26; color: white; padding: 15px; border: none; border-radius: 6px; font-size: 1.1em; cursor: pointer; font-weight: bold; width: 100%; margin-top: 20px;}
    </style>
</head>
<body>
    <header class="cabecalho">
        <div class="logo">Edição de Residente</div>
        <nav class="nav-menu">
            <ul>
                <li><a href="listar_idosos.php">🔙 Cancelar e Voltar</a></li>
            </ul>
        </nav>
    </header>

    <main class="painel-container">
        <h2 style="color: #5b3a26;">Editando: <?= htmlspecialchars($idoso['nmPessoa']) ?></h2>
        
        <form method="POST" action="actions/atualizar_idoso.php" enctype="multipart/form-data">
            
            <input type="hidden" name="idIdoso" value="<?= $idoso['idIdoso'] ?>">
            <input type="hidden" name="idPessoa" value="<?= $idoso['pessoa_idPessoa'] ?>">

            <div class="painel-grid">
                <div class="coluna-cadastro">
                    <h3 style="color: #5b3a26; border-bottom: 1px solid #eee; padding-bottom: 10px;">📝 Dados do Idoso</h3>
                    
                    <div class="grupo-input area-upload" style="border: 2px dashed #ccc; padding: 20px; text-align: center; border-radius: 8px; background: #fafafa;">
                        <label for="foto" style="cursor: pointer; margin: 0;">
                            <strong>📸 Alterar Foto (Opcional)</strong><br>
                            <span style="font-size: 0.8em; font-weight: normal; color: #666;">Deixe em branco para manter a foto atual.</span>
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/*" style="display:none;">
                    </div><br>

                    <div class="grupo-input">
                        <label>Nome Completo:</label>
                        <input type="text" name="nome" value="<?= htmlspecialchars($idoso['nmPessoa']) ?>" required>
                    </div>

                    <div class="linha-dupla">
                        <div class="grupo-input">
                            <label>CPF do Idoso:</label>
                            <input type="text" name="cpf" maxlength="11" value="<?= htmlspecialchars($idoso['cpf']) ?>" required>
                        </div>
                        <div class="grupo-input">
                            <label>Data de Nascimento:</label>
                            <input type="date" name="dtNascimento" value="<?= $idoso['dtNascimento'] ?>" required>
                        </div>
                    </div>

                    <div class="grupo-input">
                        <label>Grau de Dependência / Condição Médica:</label>
                        <input type="text" name="necessidades" value="<?= htmlspecialchars($idoso['necessidades']) ?>">
                    </div>

                    <div class="grupo-input">
                        <label>História de Vida (Resumo):</label>
                        <textarea name="historia" rows="3"><?= htmlspecialchars($idoso['sobre']) ?></textarea>
                    </div>
                </div>

                <div class="coluna-preferencias">
                    <h3 style="color: #5b3a26; border-bottom: 1px solid #eee; padding-bottom: 10px;">⚙️ Interação</h3>

                    <div class="toggle-switch">
                        <span class="titulo-toggle">🤝 Aceita Receber Visitas?</span>
                        <label>
                            <input type="radio" name="aceita_visita" value="1" <?= ($idoso['aceita_visita'] == 1) ? 'checked' : '' ?>> Sim, está apto
                        </label><br>
                        <label>
                            <input type="radio" name="aceita_visita" value="0" <?= ($idoso['aceita_visita'] == 0) ? 'checked' : '' ?>> Não no momento
                        </label>
                    </div>

                    <div class="toggle-switch">
                        <span class="titulo-toggle">✉️ Aceita Receber Cartas?</span>
                        <label>
                            <input type="radio" name="aceita_carta" value="1" <?= ($idoso['aceita_carta'] == 1) ? 'checked' : '' ?>> Sim, pode receber
                        </label><br>
                        <label>
                            <input type="radio" name="aceita_carta" value="0" <?= ($idoso['aceita_carta'] == 0) ? 'checked' : '' ?>> Não no momento
                        </label>
                    </div>

                    <button type="submit" class="btn-marrom">Salvar Alterações</button>
                </div>
            </div>
        </form>
    </main>
</body>
</html>