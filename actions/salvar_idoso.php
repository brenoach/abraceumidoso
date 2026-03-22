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
        
        $sqlBuscaInst = "SELECT idInstituicao FROM funcionario WHERE idFuncionario = ?";
        $stmtBuscaInst = $pdo->prepare($sqlBuscaInst);
        $stmtBuscaInst->execute([$idFuncionarioLogado]);
        $dadosFuncionario = $stmtBuscaInst->fetch(PDO::FETCH_ASSOC);
        
        // Guarda o ID da instituição na variável
        $idInstituicao = $dadosFuncionario['idInstituicao'];
        // ===============================

        // 2. Recebendo os dados básicos
        $nome = mb_strtoupper(trim($_POST['nome']));
        $cpf = preg_replace('/\D/', '', $_POST['cpf']); 
        $dataNascimento = $_POST['dataNascimento'];
        $sobre = trim($_POST['historia']); 
        
        // 3. Recebendo as opções de Visita/Carta e Necessidades
        $sobre = trim($_POST['sobre']);
        $aceitaVisita = $_POST['aceitaVisita']; 
        $aceitaCarta = $_POST['aceitaCarta'];   

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
        $sqlPessoa = "INSERT INTO pessoa (nomePessoa, cpf, dataNascimento, sobre, fotoPerfil) VALUES (?, ?, ?, ?, ?)";
        $stmtPes = $pdo->prepare($sqlPessoa);
        $stmtPes->execute([$nome, $cpf, $dataNascimento, $sobre, $caminhoFoto]);
        
        $idPessoa = $pdo->lastInsertId();

        // B. Inserir na tabela IDOSO (Agora enviando o $idInstituicao!)
        $sqlIdoso = "INSERT INTO idoso (idPessoa, aceitaVisita, aceitaCarta, idInstituicao) VALUES ( ?, ?, ?, ?)";
        $stmtIdoso = $pdo->prepare($sqlIdoso);
        $stmtIdoso->execute([$idPessoa, $aceitaVisita, $aceitaCarta, $idInstituicao]);

        $pdo->commit();
        
        echo "<script>
                alert('Residente cadastrado com sucesso!');
                window.location.href = '../pages/painel_funcionario.php';
              </script>";

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div style='color:red; padding: 20px;'>Erro no Banco de Dados: " . $e->getMessage() . "</div>";
    }
}
?>