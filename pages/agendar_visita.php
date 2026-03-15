<?php
session_start();
require_once '../includes/db.php';
include '../includes/header.php';


// Proteção: Só voluntário entra aqui
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'voluntario') {
    header("Location: pages/login.php");
    exit;
}

// Verifica se o ID do idoso veio na URL
if (!isset($_GET['id'])) {
    die("<h3 style='color:red;'>Erro: Nenhum idoso foi selecionado. Volte ao painel e tente novamente.</h3>");
}

$idIdoso = $_GET['id'];

// Busca o nome do idoso só para deixar a tela amigável
$stmt = $pdo->prepare("SELECT p.nomePessoa FROM idoso i JOIN pessoa p ON i.idPessoa = p.idPessoa WHERE i.idIdoso = ?");
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
       
</head>
<body>

    <div class="caixa-agendamento">
        <h2 >Agendar Visita</h2>
        <p>Você está solicitando uma visita para:<br><strong style="font-size: 1.2em; color: #333;"><?= htmlspecialchars($idoso['nomePessoa']) ?></strong></p>
        
        <form action="<?php echo BASE_URL; ?>actions/salvar_agendamento.php" method="POST">
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
            <a href="<?php echo BASE_URL; ?>pages/painel_voluntario.php" class="btn-voltar">Cancelar e Voltar</a>
        </form>
    </div>
<?include '../includes/footer.php';?>
</body>
</html>