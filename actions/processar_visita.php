<?php
session_start();
require_once '../includes/db.php';

// Segurança: Só funcionário logado pode processar
if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'funcionario') {
    die("Acesso negado.");
}

$idAgendamento = $_GET['id'];
$novaAcao = $_GET['acao']; // Recebe 'Aprovado' ou 'Recusado'

try {
    $sql = "UPDATE agendamento SET status = ? WHERE idAgendamento = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$novaAcao, $idAgendamento]);

    header("Location: ../pages/painel_funcionario.php?msg=sucesso");
    exit;

} catch (PDOException $e) {
    die("Erro ao processar: " . $e->getMessage());
}