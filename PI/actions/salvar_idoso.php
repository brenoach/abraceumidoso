<?php
session_start();
require_once '../includes/db.php';

// Proteção dupla
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'funcionario') {
    die("Acesso negado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    try {
        // === A MÁGICA ACONTECE AQUI ===
        // 1. Descobrir qual é a instituição do funcionário logado
        $idFuncionarioLogado = $_SESSION['usuario_id'];
        
        $sqlBuscaInst = "SELECT instituicao_idinstituicao FROM funcionario WHERE idFuncionario = ?";
        $stmtBuscaInst = $pdo->prepare($sqlBuscaInst);
        $stmtBuscaInst->execute([$idFuncionarioLogado]);
        $dadosFuncionario = $stmtBuscaInst->fetch(PDO::FETCH_ASSOC);
        
        // Guarda o ID da instituição na variável
        $idInstituicao = $dadosFuncionario['instituicao_idinstituicao'];
        // ===============================

        // 2. Recebendo os dados básicos
        $nome = mb_strtoupper(trim($_POST['nome']));
        $cpf = preg_replace('/\D/', '', $_POST['cpf']); 
        $dtNascimento = $_POST['dtNascimento'];
        $sobre = trim($_POST['historia']); 
        
        // 3. Recebendo as opções de Visita/Carta e Necessidades
        $necessidades = trim($_POST['necessidades']);
        $aceita_visita = $_POST['aceita_visita']; 
        $aceita_carta = $_POST['aceita_carta'];   

        // 4. Upload da Foto
        $caminhoFoto = null; 

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
            }
        }

        // 5. Inserção no Banco de Dados
        $pdo->beginTransaction();

        // A. Inserir na tabela PESSOA
        $sqlPessoa = "INSERT INTO pessoa (nmPessoa, cpf, dtNascimento, sobre, fotoPerfil) VALUES (?, ?, ?, ?, ?)";
        $stmtPes = $pdo->prepare($sqlPessoa);
        $stmtPes->execute([$nome, $cpf, $dtNascimento, $sobre, $caminhoFoto]);
        
        $idPessoa = $pdo->lastInsertId();

        // B. Inserir na tabela IDOSO (Agora enviando o $idInstituicao!)
        $sqlIdoso = "INSERT INTO idoso (pessoa_idPessoa, necessidades, aceita_visita, aceita_carta, instituicao_idinstituicao) VALUES (?, ?, ?, ?, ?)";
        $stmtIdoso = $pdo->prepare($sqlIdoso);
        $stmtIdoso->execute([$idPessoa, $necessidades, $aceita_visita, $aceita_carta, $idInstituicao]);

        $pdo->commit();
        
        echo "<script>
                alert('Residente cadastrado com sucesso!');
                window.location.href = '../painel_funcionario.php';
              </script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div style='color:red; padding: 20px;'>Erro no Banco de Dados: " . $e->getMessage() . "</div>";
    }
}
?>