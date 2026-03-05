<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/helpers.php';

// PROTEÇÃO: Bloqueia quem não for voluntário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'voluntario') {
    header("Location: login.php");
    exit;
}

// Verifica se veio um ID pela URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("<script>alert('Nenhum residente selecionado.'); window.location.href='painel_voluntario.php';</script>");
}

$idIdoso = $_GET['id'];

try {
    // Busca os dados do Idoso para mostrar com quem é a visita
    $sqlIdoso = "
        SELECT 
            p.nmPessoa, 
            p.fotoPerfil, 
            inst.nomeInstituicao,
            inst.nmLogradouro,
            inst.numero,
            inst.bairro,
            inst.cidade
        FROM idoso i
        JOIN pessoa p ON i.pessoa_idPessoa = p.idPessoa
        JOIN instituicao inst ON i.instituicao_idinstituicao = inst.idinstituicao
        WHERE i.idIdoso = ? AND i.aceita_visita = 1
    ";
    $stmtIdoso = $pdo->prepare($sqlIdoso);
    $stmtIdoso->execute([$idIdoso]);
    $idoso = $stmtIdoso->fetch(PDO::FETCH_ASSOC);

    if (!$idoso) {
        die("<script>alert('Residente não encontrado ou indisponível para visitas.'); window.location.href='painel_voluntario.php';</script>");
    }

} catch (Exception $e) {
    die("Erro ao buscar dados: " . $e->getMessage());
}

// Pega a data de hoje para bloquear datas no passado no calendário
$dataMinima = date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendar Visita - Abrace um Idoso</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: #f9f7f3; color: #333; margin: 0; font-family: sans-serif; }
        .cabecalho { display: flex; justify-content: space-between; align-items: center; padding: 15px 40px; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .nav-menu ul { list-style: none; display: flex; gap: 15px; margin: 0; padding: 0; }
        .nav-menu a { text-decoration: none; color: #5b3a26; font-weight: bold; padding: 10px 15px; border-radius: 6px; background: #fdfaf5; transition: 0.2s; }
        
        .container-agendamento { max-width: 800px; margin: 40px auto; background: #fff; padding: 40px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.08); }
        
        .perfil-destaque { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .perfil-destaque img { width: 120px; height: 120px; border: 4px solid #fdfaf5; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-bottom: 15px; }
        .perfil-destaque h2 { color: #5b3a26; margin: 0 0 5px 0; }
        .perfil-destaque p { color: #666; margin: 0; }

        .form-agendamento .grupo-input { margin-bottom: 20px; }
        .form-agendamento label { display: block; font-weight: bold; color: #5b3a26; margin-bottom: 8px; }
        .form-agendamento input { width: 100%; padding: 15px; border: 1px solid #ccc; border-radius: 8px; font-size: 1.1em; box-sizing: border-box; }
        
        .linha-dupla { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        
        .box-aviso { background: #eef5ee; border-left: 5px solid #4caf50; padding: 15px; margin-bottom: 25px; border-radius: 5px; color: #2e7d32; font-size: 0.95em; }
        
        .btn-confirmar { background: #4caf50; color: white; border: none; padding: 18px; width: 100%; border-radius: 8px; font-size: 1.2em; font-weight: bold; cursor: pointer; transition: 0.2s; margin-top: 10px; }
        .btn-confirmar:hover { background: #388e3c; }
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
                <li><a href="painel_voluntario.php">🔙 Voltar à Vitrine</a></li>
            </ul>
        </nav>
    </header>

    <main class="container-agendamento">
        <div class="perfil-destaque">
            <?= exibirFotoIdoso($idoso['fotoPerfil'], $idoso['nmPessoa']) ?>
            <h2>Agendar visita com <?= htmlspecialchars($idoso['nmPessoa']) ?></h2>
            <p>📍 <?= htmlspecialchars($idoso['nomeInstituicao']) ?> - <?= htmlspecialchars($idoso['cidade']) ?></p>
        </div>

        <div class="box-aviso">
            <strong>Lembrete:</strong> Ao confirmar o agendamento, um funcionário da instituição receberá o seu pedido e o status ficará como "Pendente" até a aprovação.
        </div>

        <form action="actions/salvar_agendamento.php" method="POST" class="form-agendamento">
            <input type="hidden" name="idIdoso" value="<?= $idIdoso ?>">

            <div class="linha-dupla">
                <div class="grupo-input">
                    <label>Data da Visita:</label>
                    <input type="date" name="data_visita" min="<?= $dataMinima ?>" required>
                </div>
                <div class="grupo-input">
                    <label>Horário de Chegada:</label>
                    <input type="time" name="hora_inicio" required>
                </div>
            </div>

            <button type="submit" class="btn-confirmar">✅ Confirmar Agendamento</button>
        </form>
    </main>
</body>
</html>
