<?php
session_start();

// 1. Puxa as conexões essenciais
require_once __DIR__ . '/../connection/config.php'; 
require_once __DIR__ . '/../includes/db.php';

// 2. Recebe os dados de forma limpa
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');
$tipo  = trim($_POST['tipo_usuario'] ?? '');

// 3. Trava de segurança simples para campos vazios
if (empty($email) || empty($senha) || empty($tipo)) {
    echo "<script>alert('Por favor, preencha todos os campos.'); window.location.href='../pages/login.php';</script>";
    exit;
}

// 4. Define a busca no banco de acordo com o tipo
if ($tipo == 'voluntario') {
    $sql = "SELECT v.idVoluntario as id, v.senha, p.nomePessoa as nome, p.idPessoa 
            FROM voluntario v
            JOIN contato c ON v.idContato = c.idContato
            JOIN pessoa p ON v.idPessoa = p.idPessoa
            WHERE c.email = ?";
} else {
    $sql = "SELECT f.idFuncionario as id, f.senha, p.nomePessoa as nome, p.idPessoa, f.idInstituicao 
            FROM funcionario f
            JOIN contato c ON f.idContato = c.idContato
            JOIN pessoa p ON f.idPessoa = p.idPessoa
            WHERE c.email = ?";
}

// 5. Executa a busca
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// 6. Confirma a senha e faz o redirecionamento
if ($usuario && password_verify($senha, $usuario['senha'])) {
    
    // Grava a Sessão
    $_SESSION['idPessoa'] = $usuario['idPessoa'];
    $_SESSION['nome'] = $usuario['nome']; 
    $_SESSION['tipo_usuario'] = $tipo;

    // Redireciona para o painel certo
    if ($tipo == 'voluntario') {
        header("Location: " . BASE_URL . "/pages/painel_voluntario.php");
    } else {
        $_SESSION['idInstituicao'] = $usuario['idInstituicao'];
        header("Location: " . BASE_URL . "/pages/painel_funcionario.php");
    }
    exit;

} else {
    // Falhou (E-mail não achou ou senha não bateu)
    echo "<script>alert('E-mail ou senha incorretos!'); window.location.href='/login.php';</script>";
    exit;
}
?>