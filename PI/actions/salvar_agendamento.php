<?php
session_start();
require_once '../includes/db.php';

// PROTEÇÃO: Só voluntário pode agendar visita
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'voluntario') {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Pega os dados que vieram do formulário
    $idIdoso = $_POST['idIdoso'];
    $dataVisita = $_POST['data_visita'];
    $horaInicio = $_POST['hora_inicio'];
    
    // Pega o ID do voluntário que está logado na sessão agora
    $idVoluntario = $_SESSION['usuario_id'];
    
    // Status inicial padrão
    $statusInicial = 'Pendente';

    try {
        // Grava na tabela agendamento
        $sql = "INSERT INTO agendamento (data_visita, hora_inicio, status, idoso_idIdoso, voluntario_idVoluntario) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dataVisita, $horaInicio, $statusInicial, $idIdoso, $idVoluntario]);

        // Redireciona o voluntário para a tela de acompanhamento das visitas dele
        echo "<script>
                alert('Sua visita foi agendada com sucesso! Aguarde a confirmação da instituição.');
                window.location.href = '../painel_voluntario.php#minhas-visitas';
              </script>";

    } catch (Exception $e) {
        echo "<div style='color:red; padding: 20px;'>Erro ao agendar visita: " . $e->getMessage() . "</div>";
    }
} else {
    echo "Acesso inválido.";
}
?>