<?php
// 1. Inclui a conexão (assumindo que você criou o includes/db.php)
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vamos supor que você receba isso do formulário HTML
    $nome          = $_POST['nome'];
    $cpf           = $_POST['cpf'];
    $dtNascimento  = $_POST['data_nascimento'];
    $sobre         = $_POST['sobre']; // Campo "sobre" da tabela pessoa
    $senha         = $_POST['senha']; 
    // Hash seguro para a senha (nunca salve senha pura!)
    $senhaHash     = password_hash($senha, PASSWORD_DEFAULT); 

    try {
        // INICIA A TRANSAÇÃO (Tudo ou nada)
        $pdo->beginTransaction();

        // ---------------------------------------------------------
        // PASSO 1: Inserir na tabela PESSOA
        // ---------------------------------------------------------
        $sqlPessoa = "INSERT INTO pessoa (nmPessoa, cpf, dtNascimento, sobre) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sqlPessoa);
        $stmt->execute([$nome, $cpf, $dtNascimento, $sobre]);

        // *** O PULO DO GATO ***: Pegar o ID gerado agora
        $idNovaPessoa = $pdo->lastInsertId();

        // ---------------------------------------------------------
        // PASSO 2: Inserir Endereço e Contatos (Simplificado conforme diagrama)
        // ---------------------------------------------------------
        /* Nota: Pelo seu diagrama, Endereço e Contatos são tabelas separadas.
           Para o código não ficar gigante, vou simular que você já inseriu 
           ou vai deixar nulo por enquanto, mas a lógica seria a mesma:
           INSERT INTO endereco -> lastInsertId -> $idNovoEndereco
           INSERT INTO contatos -> lastInsertId -> $idNovoContato
        */
        
        // Para exemplo, vamos supor que você tratou isso ou aceita NULL
        $idNovoEndereco = null; // Substitua pela lógica de insert se tiver o form
        $idNovoContato = null;  // Substitua pela lógica de insert se tiver o form

        // ---------------------------------------------------------
        // PASSO 3: Inserir na tabela VOLUNTARIO
        // ---------------------------------------------------------
        // Aqui conectamos as tabelas usando o $idNovaPessoa
        $sqlVoluntario = "INSERT INTO voluntario (senha, pessoa_idPessoa, endereco_idEndereco, contatos_idContatos) VALUES (?, ?, ?, ?)";
        $stmtVol = $pdo->prepare($sqlVoluntario);
        $stmtVol->execute([$senhaHash, $idNovaPessoa, $idNovoEndereco, $idNovoContato]);

        // Se chegou até aqui sem erro, confirma tudo no banco
        $pdo->commit();

        echo "<div class='sucesso'>Voluntário cadastrado com sucesso! ID: " . $idNovaPessoa . "</div>";

    } catch (Exception $e) {
        // Se deu erro em qualquer etapa, desfaz tudo
        $pdo->rollBack();
        echo "<div class='erro'>Erro ao cadastrar: " . $e->getMessage() . "</div>";
    }
}
?>