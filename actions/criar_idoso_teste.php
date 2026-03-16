<?php
require_once '../includes/db.php';

echo "<h1>👴 Gerador de Idoso para Teste</h1>";

try {
    // Inicia a transação (se der erro no idoso, ele não salva a pessoa pela metade)
    $pdo->beginTransaction();

    $cpfFalso = '12345678901'; // Cuidado para não tentar inserir um CPF que já existe no banco

    // 1. INSERIR A PESSOA
    // 5 interrogações = 5 valores no array
    $sqlPessoa = "INSERT INTO pessoa (nomePessoa, cpf, dataNascimento, fotoPerfil, sobre) VALUES (?, ?, ?, ?, ?)";
    $stmtPessoa = $pdo->prepare($sqlPessoa);
    
    $stmtPessoa->execute([
        'Seu Joaquim Silva', 
        $cpfFalso, 
        '1945-08-20', 
        'assets/img/fotoPerfil.png', // Um caminho simples para não quebrar a imagem
        'Adora jogar dominó, ouvir músicas antigas e contar histórias da sua época de marinheiro em Santos.'
    ]);

    // Pega o ID que o banco acabou de gerar para o Seu Joaquim
    $idPessoaGerada = $pdo->lastInsertId();

    // 2. INSERIR O IDOSO
    // Assumindo que já existe uma instituição com ID 1 no seu banco para vincular a ele
    $idInstituicaoTeste = 1; 

    // 4 interrogações = 4 valores no array
    $sqlIdoso = "INSERT INTO idoso (idPessoa, idInstituicao, aceitaVisita, aceitaCarta) VALUES (?, ?, ?, ?)";
    $stmtIdoso = $pdo->prepare($sqlIdoso);
    
    $stmtIdoso->execute([
        $idPessoaGerada,
        $idInstituicaoTeste,
        1, // 1 = Aceita Visita
        1  // 1 = Aceita Carta
    ]);

    // Se chegou até aqui sem estourar erro, salva tudo definitivamente!
    $pdo->commit();
    
    echo "<p style='color: green;'>✅ Sucesso! Seu Joaquim cadastrado com o ID de Pessoa: <strong>{$idPessoaGerada}</strong></p>";

} catch (PDOException $e) {
    // Se der qualquer erro, ele desfaz tudo para não sujar o banco
    $pdo->rollBack();
    echo "<p style='color: red;'>❌ Ocorreu um erro no banco:<br>";
    echo "SQLSTATE: " . $e->getMessage() . "</p>";
}
?>