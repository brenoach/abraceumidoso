<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'voluntario') {
    header("Location: ../login.php");
    exit;
}

$idVoluntario = $_SESSION['usuario_id'];
$idIdoso = $_POST['idoso_id'];
$dataAgendamento = $_POST['data_visita'];
$horaAgendamento = $_POST['hora_visita'];

// === NOVA TRAVA DE HORÁRIO ===
$horaInt = (int)substr($horaAgendamento, 0, 2); // Pega as primeiras duas letras (a hora)

if ($horaInt < 7 || $horaInt >= 20) {
    echo "<script>
        alert('Erro: A instituição só aceita visitas entre 07:00 e 20:00.');
        window.history.back();
    </script>";
    exit;
}
// =============================

$status = 'Pendente';

try {
    $sql = "INSERT INTO agendamento (dataAgendamento, horaAgendamento, status, idVoluntario, idIdoso) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dataAgendamento, $horaAgendamento, $status, $idVoluntario, $idIdoso]);

    echo "<script>
        alert('Agendamento solicitado! Aguarde a aprovação da instituição.');
        window.location.href = '../pages/painel_voluntario.php';
    </script>";
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage();
}

?>