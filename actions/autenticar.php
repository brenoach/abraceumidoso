<?php
session_start();
require_once '../includes/db.php';

// Ativando a exibição de erros caso o banco reclame
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $tipo  = $_POST['tipo_usuario']; // 'voluntario' ou 'funcionario'

    try {
        if ($tipo == 'voluntario') {
            $sql = "SELECT v.idVoluntario as id, v.senha, p.nomePessoa as nome 
                    FROM voluntario v
                    JOIN contato c ON v.idContato = c.idcontato
                    JOIN pessoa p ON v.idPessoa = p.idPessoa
                    WHERE c.email = ?";
        } else {
            $sql = "SELECT f.idFuncionario as id, f.senha, p.nomePessoa as nome, f.idInstituicao 
                    FROM funcionario f
                    JOIN contato c ON f.idContato = c.idcontato
                    JOIN pessoa p ON f.idPessoa = p.idPessoa
                    WHERE c.email = ?";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se achou o usuário E se a senha bate com a criptografia
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Login Sucesso: Salva dados na Sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $tipo;

            // Redireciona para a página correta usando o Header oficial do PHP
            if ($tipo == 'voluntario') {
                header("Location: ../pages/painel_voluntario.php");
            } else {
                $_SESSION['instituicao_id'] = $usuario['idInstituicao'];
                header("Location: ../pages/painel_funcionario.php");
            }
            exit;

        } else {
            // Login Falhou
            echo "<script>
                    alert('E-mail ou senha incorretos! Ou você ainda não está cadastrado.'); 
                    window.location.href='../pages/login.php';
                  </script>";
        }

    } catch (PDOException $e) {
        echo "<div style='color:red; padding: 20px;'>Erro no sistema: " . $e->getMessage() . "</div>";
    }
} else {
    echo "Acesso inválido.";
}
?>