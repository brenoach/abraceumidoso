<?php
session_start();
require_once 'includes/db.php';

// Proteção: Só voluntário entra aqui
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'voluntario') {
    header("Location: login.php");
    exit;
}

// Verifica se o ID do idoso veio na URL
if (!isset($_GET['id'])) {
    die("<h3 style='color:red;'>Erro: Nenhum idoso foi selecionado. Volte ao painel e tente novamente.</h3>");
}

$idIdoso = $_GET['id'];

// Busca o nome do idoso só para deixar a tela amigável
$stmt = $pdo->prepare("SELECT p.nmPessoa FROM idoso i JOIN pessoa p ON i.pessoa_idPessoa = p.idPessoa WHERE i.idIdoso = ?");
$stmt->execute([$idIdoso]);
$idoso = $stmt->fetch();

if (!$idoso) {
    die("Idoso não encontrado no sistema.");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Agendar Visita</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body { background-color: #fdfaf5; font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .caixa-agendamento { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; border: 1px solid #eee; }
        .campo { margin-bottom: 20px; text-align: left; }
        .campo label { display: block; font-weight: bold; margin-bottom: 5px; color: #5b3a26; }
        .campo input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        .btn-sucesso { background: #4caf50; color: white; border: none; padding: 12px; width: 100%; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 16px; transition: 0.3s; }
        .btn-sucesso:hover { background: #388e3c; }
        .btn-voltar { display: inline-block; margin-top: 15px; color: #666; text-decoration: none; }
    </style>
</head>
<body>

    <div class="caixa-agendamento">
        <h2 style="color: #5b3a26; margin-top: 0;">Agendar Visita ❤️</h2>
        <p>Você está solicitando uma visita para:<br><strong style="font-size: 1.2em; color: #333;"><?= htmlspecialchars($idoso['nmPessoa']) ?></strong></p>
        
        <form action="actions/salvar_agendamento.php" method="POST">
            <input type="hidden" name="idoso_id" value="<?= htmlspecialchars($idIdoso) ?>">
            
            <div class="campo">
                <label>Data da Visita:</label>
                <input type="date" name="data_visita" min="<?= date('Y-m-d') ?>" required>
            </div>
            
            <div class="campo">
    <label>Horário da Visita (07h às 20h):</label>
    <input type="time" name="hora_visita" min="07:00" max="20:00" required>
    <small style="color: #666;">Funcionamento: 07:00 às 20:00</small>
</div>
            
            <button type="submit" class="btn-sucesso">Confirmar Agendamento</button>
            <a href="painel_voluntario.php" class="btn-voltar">Cancelar e Voltar</a>
        </form>
    </div>

</body>
</html>