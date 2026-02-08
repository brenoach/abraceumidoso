<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 1. Recebendo dados de TEXTO
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $nasc = $_POST['dtNascimento'];
    $sobre = $_POST['sobre'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
    // Dados de Endereço (Simplificado)
    $cep = $_POST['cep'];
    $cidade = $_POST['cidade'];

    // 2. Lógica de UPLOAD DA FOTO
    $caminhoFoto = null; // Padrão se não enviar foto

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $arquivo = $_FILES['foto'];
        $pastaDestino = '../assets/img/uploads/';
        
        // Cria a pasta se não existir
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }

        // Gera nome único para não sobrepor arquivos (ex: foto_MD5HASH.jpg)
        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $novoNome = "foto_" . md5(time() . uniqid()) . "." . $extensao;
        
        // Move o arquivo e salva o caminho para o banco
        if (move_uploaded_file($arquivo['tmp_name'], $pastaDestino . $novoNome)) {
            // No banco salvamos o caminho relativo a partir da raiz do site
            $caminhoFoto = 'assets/img/uploads/' . $novoNome;
        }
    }

    try {
        $pdo->beginTransaction();

        // A. Inserir Endereço
        // Nota: Complete com os outros campos do endereço conforme necessário
        $sqlEnd = "INSERT INTO endereco (cep, cidade, estado, bairro, nmLogradouro) VALUES (?, ?, ?, ?, ?)"; 
        $stmtEnd = $pdo->prepare($sqlEnd);
        $stmtEnd->execute([$cep, $cidade]);
        $idEndereco = $pdo->lastInsertId();

        // B. Inserir Contatos
        $sqlCon = "INSERT INTO contatos (email, celular) VALUES (?, ?)";
        $stmtCon = $pdo->prepare($sqlCon);
        $stmtCon->execute([$email, $celular]);
        $idContato = $pdo->lastInsertId();

        // C. Inserir Pessoa (COM A FOTO)
        $sqlPessoa = "INSERT INTO pessoa (nmPessoa, cpf, dtNascimento, sobre, fotoPerfil) VALUES (?, ?, ?, ?, ?)";
        $stmtPes = $pdo->prepare($sqlPessoa);
        $stmtPes->execute([$nome, $cpf, $nasc, $sobre, $caminhoFoto]);
        $idPessoa = $pdo->lastInsertId();

        // D. Inserir Voluntário (Vinculando tudo)
        $sqlVol = "INSERT INTO voluntario (senha, pessoa_idPessoa, endereco_idEndereco, contatos_idcontatos) VALUES (?, ?, ?, ?)";
        $stmtVol = $pdo->prepare($sqlVol);
        $stmtVol->execute([$senha, $idPessoa, $idEndereco, $idContato]);

        $pdo->commit();
        echo "<div class='sucesso'>Voluntário cadastrado com sucesso!</div>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div class='erro'>Erro: " . $e->getMessage() . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    
</body>
</html>