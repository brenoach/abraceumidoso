<?php
session_start(); // Inicia a sessão para salvar o login
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo  = $_POST['tipo_usuario']; // 'voluntario' ou 'funcionario'

    try {
        if ($tipo == 'voluntario') {
            // QUERY PARA VOLUNTÁRIO
            // Precisamos fazer JOIN com Contatos (para ver o email) e Pessoa (para pegar o nome)
            $sql = "SELECT v.idVoluntario as id, v.senha, p.nmPessoa as nome 
                    FROM voluntario v
                    JOIN contatos c ON v.contatos_idcontatos = c.idcontatos
                    JOIN pessoa p ON v.pessoa_idPessoa = p.idPessoa
                    WHERE c.email = ?";
        } else {
            // QUERY PARA FUNCIONÁRIO
            $sql = "SELECT f.idFuncionario as id, f.senha, p.nmPessoa as nome, f.instituicao_idinstituicao 
                    FROM funcionario f
                    JOIN contatos c ON f.contatos_idcontatos = c.idcontatos
                    JOIN pessoa p ON f.pessoa_idPessoa = p.idPessoa
                    WHERE c.email = ?";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se achou o usuário E se a senha bate
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Login Sucesso: Salva dados na Sessão do navegador
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $tipo;

            // Redireciona para a página correta
            if ($tipo == 'voluntario') {
                header("Location: ../painel_voluntario.php");
            } else {
                $_SESSION['instituicao_id'] = $usuario['instituicao_idinstituicao'];
                header("Location: ../painel_funcionario.php");
            }
            exit;

        } else {
            // Login Falhou
            echo "<script>alert('E-mail ou senha incorretos!'); window.location.href='../login.php';</script>";
        }

    } catch (PDOException $e) {
        echo "Erro no sistema: " . $e->getMessage();
    }
}
?>