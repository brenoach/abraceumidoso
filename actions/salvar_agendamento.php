<?php
// session_start();
// require_once __DIR__ . '/../connection/config.php';
// require_once __DIR__ . '/../includes/db.php';

// // 1. VERIFICAÇÃO DE SEGURANÇA
// if (!isset($_SESSION['idPessoa']) || $_SESSION['usuario_tipo'] !== 'voluntario') {
//     header("Location: " . BASE_URL . "/login?erro=sessao_expirada");
//     exit;
// }

// 2. RECUPERAÇÃO DOS DADOS DO FORMULÁRIO (Sincronizado com os names do agendar_visita.php)
// Verificamos se os dados existem antes de usar para evitar o erro de "Undefined key"
$id_pessoa_logada = $_SESSION['idPessoa'];
$id_idoso         = $_POST['idIdoso'] ?? null;
$data_visita      = $_POST['data_visita'] ?? null;
$hora_visita      = $_POST['hora_visita'] ?? null;

// Validação básica: se faltar algo, volta com erro
if (!$id_idoso || !$data_visita || !$hora_visita) {
    header("Location: " . BASE_URL . "/painel-voluntario?erro=dados_incompletos");
    exit;
}

try {
    // 3. A QUERY DE OURO: 
    // Como na tabela 'agendamento' usamos 'idVoluntario' e na sessão temos 'idPessoa',
    // vamos usar um sub-select para descobrir o ID de voluntário automaticamente.
    
    $sql = "INSERT INTO agendamento (idVoluntario, idIdoso, dataAgendamento, horaAgendamento, status) 
            VALUES (
                (SELECT idVoluntario FROM voluntario WHERE idPessoa = ?), 
                ?, 
                ?, 
                ?, 
                'Pendente'
            )";

    $stmt = $pdo->prepare($sql);
    $sucesso = $stmt->execute([
        $id_pessoa_logada,
        $id_idoso,
        $data_visita,
        $hora_visita
    ]);

    if ($sucesso) {
        // Deu tudo certo! Volta para o painel com mensagem de sucesso
        header("Location: " . BASE_URL . "/painel-voluntario?status=agendado");
        exit;
    }

} catch (PDOException $e) {
    // Se der erro de banco (ex: coluna errada), ele cai aqui
    die("Erro ao salvar agendamento: " . $e->getMessage());
}