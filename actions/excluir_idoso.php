<?php

require_once __DIR__ . '/../includes/db.php';

// 2. PROTEÇÃO DE SESSÃO E ID
// Captura o ID da URL de forma segura
$idIdoso = $_GET['id'] ?? null;

// PARA TESTE: Como você está testando e pode não estar logado, coloquei um fallback genérico ("?? 1").
// Isso evita o erro 'Undefined array key "usuario_id"'. Quando o login estiver pronto, tire o "?? 1".
$idFuncionarioLogado = $_SESSION['usuario_id'] ?? 1;

if ($idIdoso) {
    try {
        // 1. Segurança: Descobrir a instituição do funcionário logado
        $sqlInst = "SELECT idInstituicao FROM funcionario WHERE idFuncionario = ?";
        $stmtInst = $pdo->prepare($sqlInst);
        $stmtInst->execute([$idFuncionarioLogado]);
        $idInstituicao = $stmtInst->fetchColumn();

        // 2. Verificar se o idoso existe e PERTENCE a esta instituição
        $sqlBusca = "SELECT idPessoa FROM idoso WHERE idIdoso = ? AND idInstituicao = ?";
        $stmtBusca = $pdo->prepare($sqlBusca);
        $stmtBusca->execute([$idIdoso, $idInstituicao]);
        $idPessoa = $stmtBusca->fetchColumn(); 

        if (!$idPessoa) {
            // REDIRECIONAMENTO AJUSTADO PARA A ROTA
            $urlListagem = BASE_URL . "/listar-idosos"; // Garanta que esta rota existe no seu index.php
            die("<script>alert('Residente não encontrado ou você não tem permissão.'); window.location.href = '$urlListagem';</script>");
        }

        // 3. Inicia a exclusão em cascata (Transação ativada)
        $pdo->beginTransaction();

        // Passo A: Excluir os agendamentos 
        $sqlAgendamentos = "DELETE FROM agendamento WHERE idIdoso = ?";
        $stmtAgendamentos = $pdo->prepare($sqlAgendamentos);
        $stmtAgendamentos->execute([$idIdoso]);

        // Passo B: Excluir a ficha do IDOSO
        $sqlDeleteIdoso = "DELETE FROM idoso WHERE idIdoso = ?";
        $stmtDeleteIdoso = $pdo->prepare($sqlDeleteIdoso);
        $stmtDeleteIdoso->execute([$idIdoso]);

        // Passo C: Excluir a ficha da PESSOA
        $sqlDeletePessoa = "DELETE FROM pessoa WHERE idPessoa = ?";
        $stmtDeletePessoa = $pdo->prepare($sqlDeletePessoa);
        $stmtDeletePessoa->execute([$idPessoa]);

        // Tudo certo! Confirma a exclusão.
        $pdo->commit();

        // REDIRECIONAMENTO DE SUCESSO AJUSTADO PARA A ROTA
        $urlListagem = BASE_URL . "/listar-idosos";
        echo "<script>
                alert('Residente excluído com sucesso!');
                window.location.href = '$urlListagem';
              </script>";
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div style='color:red; padding: 20px;'>Erro no Banco de Dados: " . $e->getMessage() . "</div>";
    }
} else {
    echo "ID de residente inválido.";
}

// O bloco "if ($sucesso)" que estava aqui embaixo foi removido porque a variável não existia 
// e ele causaria um erro. O redirecionamento correto agora acontece dentro do bloco try/catch!
?>