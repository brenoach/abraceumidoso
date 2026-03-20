<?php
session_start();
require_once '../includes/db.php';

// Ativando a exibição de erros
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    
    // Proteção caso o HTML não envie o tipo_usuario
    $tipo = isset($_POST['tipo_usuario']) ? trim($_POST['tipo_usuario']) : '';

    if (empty($tipo)) {
        die("<div style='color:red; padding: 20px;'>Erro: O tipo de usuário não foi enviado pelo formulário HTML!</div>");
    }

    try {
        if ($tipo == 'voluntario') {
            // Corrigido: idContato com C maiúsculo
            $sql = "SELECT v.idVoluntario as id, v.senha, p.nomePessoa as nome 
                    FROM voluntario v
                    JOIN contato c ON v.idContato = c.idContato
                    JOIN pessoa p ON v.idPessoa = p.idPessoa
                    WHERE c.email = ?";
        } else if ($tipo == 'funcionario') {
            // Corrigido: idContato com C maiúsculo
            $sql = "SELECT f.idFuncionario as id, f.senha, p.nomePessoa as nome, f.idInstituicao 
                    FROM funcionario f
                    JOIN contato c ON f.idContato = c.idContato
                    JOIN pessoa p ON f.idPessoa = p.idPessoa
                    WHERE c.email = ?";
        } else {
            die("Tipo de usuário inválido.");
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se achou o usuário E se a senha bate
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Login Sucesso: Salva dados na Sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $tipo;

            // Redireciona
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