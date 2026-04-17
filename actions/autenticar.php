<?php
// O session_start() foi removido porque o index.php já faz isso!

// 1. Puxa as conexões essenciais 
// (Mantive com require_once, o que é super seguro para evitar duplicatas)
require_once __DIR__ . '/../connection/config.php'; 
require_once __DIR__ . '/../includes/db.php';

// 2. Recebe os dados de forma limpa
$email = trim($_POST['email'] ?? '');
$senha = trim($_POST['senha'] ?? '');
$tipo  = trim($_POST['tipo_usuario'] ?? '');

// 3. Trava de segurança simples para campos vazios (AJUSTADO PARA A ROTA)
if (empty($email) || empty($senha) || empty($tipo)) {
    echo "<script>
            alert('Por favor, preencha todos os campos.'); 
            window.location.href='" . BASE_URL . "/login';
          </script>";
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
// DEBUG TEMPORÁRIO - Remova após testar
if (!$usuario) {
    die("Erro: O e-mail {$email} não foi encontrado na tabela de contatos vinculada a um {$tipo}.");
}
if (!password_verify($senha, $usuario['senha'])) {
    die("Erro: Usuário encontrado, mas a senha no banco não é um hash válido ou não confere.");
}
// 6. Confirma a senha e faz o redirecionamento
if ($usuario && password_verify($senha, $usuario['senha'])) {
    
    // Grava a Sessão (Variável usuario_tipo corrigida para bater com o helpers.php)
    $_SESSION['idPessoa'] = $usuario['idPessoa'];
    $_SESSION['nome'] = $usuario['nome']; 
    $_SESSION['usuario_tipo'] = $tipo; 

    // Redireciona para o painel certo usando a ROTA (sem .php e sem a pasta /pages/)
    if ($tipo == 'voluntario') {
        header("Location: " . BASE_URL . "/painel-voluntario");
        } else {
        $_SESSION['idInstituicao'] = $usuario['idInstituicao'];
        header("Location: " . BASE_URL . "/painel-funcionario");
        }
        exit;

} else {
    // Falhou (E-mail não achou ou senha não bateu) - AJUSTADO PARA A ROTA
    echo "<script>
            alert('E-mail ou senha incorretos!'); 
            window.location.href='" . BASE_URL . "/login';
          </script>";
    exit;
}
?>