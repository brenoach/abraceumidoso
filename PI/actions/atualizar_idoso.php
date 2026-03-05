<?php
session_start();
require_once '../includes/db.php';

// PROTEÇÃO: Bloqueia quem não for funcionário
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'funcionario') {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 1. Recebendo os IDs ocultos
    $idIdoso = $_POST['idIdoso'];
    $idPessoa = $_POST['idPessoa'];

    // 2. Recebendo os novos textos digitados
    $nome = mb_strtoupper(trim($_POST['nome']));
    $cpf = preg_replace('/\D/', '', $_POST['cpf']); 
    $dtNascimento = $_POST['dtNascimento'];
    $sobre = trim($_POST['historia']); 
    
    $necessidades = trim($_POST['necessidades']);
    $aceita_visita = $_POST['aceita_visita']; 
    $aceita_carta = $_POST['aceita_carta'];   

    // 3. Lógica inteligente de UPLOAD DA FOTO
    $caminhoFoto = null; 
    $atualizarFoto = false; // Flag para saber se precisamos mexer na foto no banco

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $arquivo = $_FILES['foto'];
        $pastaDestino = '../assets/img/uploads/';
        
        if (!is_dir($pastaDestino)) {
            mkdir($pastaDestino, 0777, true);
        }

        $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
        $novoNome = "foto_idoso_" . md5(time() . uniqid()) . "." . $extensao;
        
        if (move_uploaded_file($arquivo['tmp_name'], $pastaDestino . $novoNome)) {
            $caminhoFoto = 'assets/img/uploads/' . $novoNome;
            $atualizarFoto = true; // Avisa que tem foto nova!
        }
    }

    // 4. Atualização no Banco de Dados (UPDATE em vez de INSERT)
    try {
        $pdo->beginTransaction();

        // A. Atualizar tabela PESSOA
        if ($atualizarFoto) {
            // Se tem foto nova, atualiza a coluna fotoPerfil também
            $sqlPessoa = "UPDATE pessoa SET nmPessoa = ?, cpf = ?, dtNascimento = ?, sobre = ?, fotoPerfil = ? WHERE idPessoa = ?";
            $stmtPes = $pdo->prepare($sqlPessoa);
            $stmtPes->execute([$nome, $cpf, $dtNascimento, $sobre, $caminhoFoto, $idPessoa]);
        } else {
            // Se NÃO tem foto nova, ignora a fotoPerfil para não apagar a antiga
            $sqlPessoa = "UPDATE pessoa SET nmPessoa = ?, cpf = ?, dtNascimento = ?, sobre = ? WHERE idPessoa = ?";
            $stmtPes = $pdo->prepare($sqlPessoa);
            $stmtPes->execute([$nome, $cpf, $dtNascimento, $sobre, $idPessoa]);
        }

        // B. Atualizar tabela IDOSO
        $sqlIdoso = "UPDATE idoso SET necessidades = ?, aceita_visita = ?, aceita_carta = ? WHERE idIdoso = ?";
        $stmtIdoso = $pdo->prepare($sqlIdoso);
        $stmtIdoso->execute([$necessidades, $aceita_visita, $aceita_carta, $idIdoso]);

        $pdo->commit();
        
        // Devolve o funcionário para a listagem com mensagem de sucesso
        echo "<script>
                alert('Dados do residente atualizados com sucesso!');
                window.location.href = '../listar_idosos.php';
              </script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div style='color:red; padding: 20px;'>Erro no Banco de Dados: " . $e->getMessage() . "</div>";
    }
} else {
    echo "Acesso inválido.";
}
?>