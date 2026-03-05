<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Recebendo e tratando dados de TEXTO
    $nome = mb_strtoupper(trim($_POST['nome']));
    
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); // Limpa pontuações do CPF
    $quant_Cpf = strlen($cpf);
    
    // Validação do CPF
    if ($quant_Cpf !== 11) {
        echo "<div class='erro'>Erro: O CPF precisa ter exatamente 11 números.</div>";
        exit;
    }
    
    $nasc = $_POST['dtNascimento'];
    $sobre = $_POST['sobre'];
    $email = trim($_POST['email']);
    
    // Validação do E-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='erro'>Erro: E-mail inválido!</div>";
        exit;
    }
    
    $celular = preg_replace('/\D/', '', $_POST['celular']); // Deixa só números
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    
    // 2. Recebendo DADOS DE ENDEREÇO
    $cep = preg_replace('/\D/', '', $_POST['cep']);
    $estado = $_POST['estado']; 
    $cidade = $_POST['cidade'];
    $bairro = $_POST['bairro'];
    $rua = $_POST['rua'];       
    $numero = $_POST['numero']; 
    $complemento = $_POST['complemento']; 

    // 3. Lógica de UPLOAD DA FOTO
    $caminhoFoto = null; // Padrão se não enviar foto

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $arquivo = $_FILES['foto'];
        $pastaDestino = '../assets/img/uploads/';
        
        // Cria a pasta se ela não existir
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }

        // Gera um nome único para a imagem
        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $novoNome = "foto_" . md5(time() . uniqid()) . "." . $extensao;
        
        // Move o arquivo e salva o caminho para o banco
        if (move_uploaded_file($arquivo['tmp_name'], $pastaDestino . $novoNome)) {
            $caminhoFoto = 'assets/img/uploads/' . $novoNome;
        }
    }

    // 4. Inserção no Banco de Dados (Transação)
    try {
        // Inicia a transação (se der erro em uma tabela, ele cancela tudo)
        $pdo->beginTransaction();

        // A. Inserir Endereço (7 interrogações, 7 dados)
        $sqlEnd = "INSERT INTO endereco (cep, cidade, estado, bairro, nmLogradouro, numero, complemento) VALUES (?, ?, ?, ?, ?, ?, ?)"; 
        $stmtEnd = $pdo->prepare($sqlEnd);
        $stmtEnd->execute([$cep, $cidade, $estado, $bairro, $rua, $numero, $complemento]); 
        $idEndereco = $pdo->lastInsertId();

        // B. Inserir Contatos (2 interrogações, 2 dados)
        $sqlCon = "INSERT INTO contatos (email, celular) VALUES (?, ?)";
        $stmtCon = $pdo->prepare($sqlCon);
        $stmtCon->execute([$email, $celular]);
        $idContato = $pdo->lastInsertId();

        // C. Inserir Pessoa com Foto (5 interrogações, 5 dados)
        $sqlPessoa = "INSERT INTO pessoa (nmPessoa, cpf, dtNascimento, sobre, fotoPerfil) VALUES (?, ?, ?, ?, ?)";
        $stmtPes = $pdo->prepare($sqlPessoa);
        $stmtPes->execute([$nome, $cpf, $nasc, $sobre, $caminhoFoto]);
        $idPessoa = $pdo->lastInsertId();

        // D. Inserir Voluntário (4 interrogações, 4 dados)
        $sqlVol = "INSERT INTO voluntario (senha, pessoa_idPessoa, endereco_idEndereco, contatos_idcontatos) VALUES (?, ?, ?, ?)";
        $stmtVol = $pdo->prepare($sqlVol);
        $stmtVol->execute([$senha, $idPessoa, $idEndereco, $idContato]);

        // Confirma todas as inserções
        $pdo->commit();
        
        // Redireciona para o login com mensagem de sucesso
        echo "<script>
                alert('Voluntário cadastrado com sucesso!');
                window.location.href = '../login.php';
              </script>";

    } catch (Exception $e) {
        // Se algo der errado, desfaz tudo o que tentou inserir
        $pdo->rollBack();
        echo "<div class='erro'>Erro no Banco de Dados: " . $e->getMessage() . "</div>";
    }
} else {
    echo "Acesso inválido.";
}
?>