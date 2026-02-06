<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $dtNascimento = $_POST['dtNascimento'];
    $sobre = $_POST['sobre'];
    $idInstituicao = $_POST['idInstituicao']; // Vem do Select

    try {
        $pdo->beginTransaction();

        // 1. Cria a PESSOA Genérica
        $sqlPessoa = "INSERT INTO pessoa (nmPessoa, cpf, dtNascimento, sobre) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sqlPessoa);
        $stmt->execute([$nome, $cpf, $dtNascimento, $sobre]);

        // Pega o ID que acabou de ser criado
        $idPessoa = $pdo->lastInsertId();

        // 2. Cria o registro na tabela IDOSO
        // Vincula a Pessoa (FK) e a Instituição (FK)
        $sqlIdoso = "INSERT INTO idoso (pessoa_idPessoa, instituicao_idinstituicao) VALUES (?, ?)";
        $stmtIdoso = $pdo->prepare($sqlIdoso);
        $stmtIdoso->execute([$idPessoa, $idInstituicao]);

        $pdo->commit();
        echo "<div class='sucesso'>Idoso cadastrado com sucesso!</div>";
        // Opcional: header("Location: ../cadastro_idoso.php?status=sucesso");

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div class='erro'>Erro: " . $e->getMessage() . "</div>";
    }
}
?>