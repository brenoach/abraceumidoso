<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Recebendo dados de TEXTO
    $nome = mb_strtoupper(trim($_POST['nome']));
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); 
    
    if (strlen($cpf) !== 11) {
        echo "<div class='erro'>Erro: O CPF precisa ter exatamente 11 números.</div>";
        exit;
    }
    
    $nasc = $_POST['dtNascimento'];
    $sobre = $_POST['sobre'];
    
    // DADOS NOVOS: Cargo e Instituição
    $cargo = trim($_POST['cargo']);
    $idInstituicao = $_POST['idInstituicao'];
    
    $email = trim($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='erro'>Erro: E-mail inválido!</div>";
        exit;
    }
    
    $celular = preg_replace('/\D/', '', $_POST['celular']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
    // 2. Recebendo DADOS DE ENDEREÇO (Ajustado para nmlogradouro)
    $cep = preg_replace('/\D/', '', $_POST['cep']);
    $estado = $_POST['estado']; 
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $nmLogradouro = $_POST['nmlogradouro']; // Corrigido para bater com o HTML     
    $numero = $_POST['numero']; 
    $complemento = $_POST['complemento']; 

    // 3. Lógica de UPLOAD DA FOTO
    $caminhoFoto = null; 

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $arquivo = $_FILES['foto'];
        $pastaDestino = '../assets/img/uploads/';
        
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }

        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $novoNome = "foto_" . md5(time() . uniqid()) . "." . $extensao;
        
        if (move_uploaded_file($arquivo['tmp_name'], $pastaDestino . $novoNome)) {
            $caminhoFoto = 'assets/img/uploads/' . $novoNome;
        }
    }

    // 4. Inserção no Banco de Dados (Transação)
    try {
        $pdo->beginTransaction();

        // A. Inserir Endereço 
        $sqlEnd = "INSERT INTO endereco (cep, cidade, estado, bairro, nmLogradouro, numero, complemento) VALUES (?, ?, ?, ?, ?, ?, ?)"; 
        $stmtEnd = $pdo->prepare($sqlEnd);
        $stmtEnd->execute([$cep, $cidade, $estado, $bairro, $nmLogradouro, $numero, $complemento]); 
        $idEndereco = $pdo->lastInsertId();

        // B. Inserir Contatos 
        $sqlCon = "INSERT INTO contatos (email, celular) VALUES (?, ?)";
        $stmtCon = $pdo->prepare($sqlCon);
        $stmtCon->execute([$email, $celular]);
        $idContato = $pdo->lastInsertId();

        // C. Inserir Pessoa com Foto 
        $sqlPessoa = "INSERT INTO pessoa (nmPessoa, cpf, dtNascimento, sobre, fotoPerfil) VALUES (?, ?, ?, ?, ?)";
        $stmtPes = $pdo->prepare($sqlPessoa);
        $stmtPes->execute([$nome, $cpf, $nasc, $sobre, $caminhoFoto]);
        $idPessoa = $pdo->lastInsertId();

        // D. Inserir FUNCIONÁRIO (Agora com Instituição e Cargo)
        // Atenção aqui: o banco precisa ter a coluna 'cargo' na tabela funcionario
        $sqlFunc = "INSERT INTO funcionario (senha, pessoa_idPessoa, endereco_idEndereco, contatos_idcontatos, instituicao_idinstituicao, cargo) VALUES (?, ?, ?, ?, ?, ?)";
        $stmtFunc = $pdo->prepare($sqlFunc);
        $stmtFunc->execute([$senha, $idPessoa, $idEndereco, $idContato, $idInstituicao, $cargo]);

        $pdo->commit();
        
        echo "<script>
                alert('Funcionário cadastrado com sucesso!');
                window.location.href = '../login.php';
              </script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div class='erro'>Erro no Banco de Dados: " . $e->getMessage() . "</div>";
    }
} else {
    echo "Acesso inválido.";
}
?>