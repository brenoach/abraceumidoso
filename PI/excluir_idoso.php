<?php
session_start();
require_once '../includes/db.php';

// PROTEÇÃO: Bloqueia quem não for funcionário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'funcionario') {
    die("Acesso negado.");
}

// Verifica se o ID veio na URL (ex: excluir_idoso.php?id=3)
if (isset($_GET['id'])) {
    $idIdoso = $_GET['id'];
    $idFuncionarioLogado = $_SESSION['usuario_id'];

    try {
        // 1. Segurança: Descobrir a instituição do funcionário logado
        $sqlInst = "SELECT instituicao_idinstituicao FROM funcionario WHERE idFuncionario = ?";
        $stmtInst = $pdo->prepare($sqlInst);
        $stmtInst->execute([$idFuncionarioLogado]);
        $idInstituicao = $stmtInst->fetchColumn();

        // 2. Verificar se o idoso existe e PERTENCE a esta instituição (evita que apaguem idosos de outras filiais)
        $sqlBusca = "SELECT pessoa_idPessoa FROM idoso WHERE idIdoso = ? AND instituicao_idinstituicao = ?";
        $stmtBusca = $pdo->prepare($sqlBusca);
        $stmtBusca->execute([$idIdoso, $idInstituicao]);
        $idPessoa = $stmtBusca->fetchColumn(); // Guarda o ID da Pessoa para apagar depois

        if (!$idPessoa) {
            die("<script>alert('Residente não encontrado ou você não tem permissão para excluí-lo.'); window.location.href = '../listar_idosos.php';</script>");
        }

        // 3. Inicia a exclusão em cascata (Transação ativada)
        $pdo->beginTransaction();

        // Passo A: Excluir os agendamentos futuros ou passados vinculados a este idoso
        $sqlAgendamentos = "DELETE FROM agendamento WHERE idoso_idIdoso = ?";
        $stmtAgendamentos = $pdo->prepare($sqlAgendamentos);
        $stmtAgendamentos->execute([$idIdoso]);

        // Passo B: Excluir a ficha do IDOSO
        $sqlDeleteIdoso = "DELETE FROM idoso WHERE idIdoso = ?";
        $stmtDeleteIdoso = $pdo->prepare($sqlDeleteIdoso);
        $stmtDeleteIdoso->execute([$idIdoso]);

        // Passo C: Excluir a ficha da PESSOA (para não deixar dados soltos e fantasmas no banco)
        $sqlDeletePessoa = "DELETE FROM pessoa WHERE idPessoa = ?";
        $stmtDeletePessoa = $pdo->prepare($sqlDeletePessoa);
        $stmtDeletePessoa->execute([$idPessoa]);

        // Tudo certo! Confirma a exclusão.
        $pdo->commit();

        echo "<script>
                alert('Residente excluído com sucesso!');
                window.location.href = '../listar_idosos.php';
              </script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div style='color:red; padding: 20px;'>Erro no Banco de Dados: " . $e->getMessage() . "</div>";
    }
} else {
    echo "ID de residente inválido.";
}
?>