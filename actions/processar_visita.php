<?php
// O index.php já iniciou a sessão e chamou o db.php!

// 1. Recebe os dados da URL (via GET)
$idAgendamento = $_GET['id'] ?? null;
$acao = $_GET['acao'] ?? null; // 'aprovar' ou 'recusar'

// 2. Trava de segurança
if (!$idAgendamento || !$acao) {
    echo "<script>alert('Dados inválidos.'); window.location.href='" . BASE_URL . "/painel-funcionario';</script>";
    exit;
}

try {
    // 3. Define qual será a palavra gravada no banco
    if ($acao === 'aprovar') {
        $novoStatus = 'Aprovado';
        $mensagem = 'Visita aprovada com sucesso!';
    } elseif ($acao === 'recusar') {
        $novoStatus = 'Recusado';
        $mensagem = 'Visita recusada.';
    } else {
        die("Ação não reconhecida.");
    }

    // 4. Executa a atualização (UPDATE) na tabela
    $sql = "UPDATE agendamento SET status = :status WHERE idAgendamento = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':status' => $novoStatus,
        ':id' => $idAgendamento
    ]);

    // 5. Devolve o funcionário para o painel com o aviso
    echo "<script>
            alert('$mensagem');
            window.location.href='" . BASE_URL . "/painel-funcionario';
          </script>";
    exit;

} catch (PDOException $e) {
    die("Erro ao processar a visita: " . $e->getMessage());
}
?>