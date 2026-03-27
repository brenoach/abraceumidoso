<?php
session_start();
require_once __DIR__ . '/../connection/config.php';
require_once __DIR__ . '/../includes/db.php';

if (!isset($pdo)) {
    die("Erro crítico: A conexão \$pdo não foi criada. Verifique o arquivo db.php.");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
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
            // Buscando o idPessoa do Voluntário
            $sql = "SELECT v.idVoluntario as id, v.senha, p.nomePessoa as nome, p.idPessoa 
                    FROM voluntario v
                    JOIN contato c ON v.idContato = c.idContato
                    JOIN pessoa p ON v.idPessoa = p.idPessoa
                    WHERE c.email = ?";
        } else if ($tipo == 'funcionario') {
            // Buscando o idPessoa do Funcionário e a Instituição
            $sql = "SELECT f.idFuncionario as id, f.senha, p.nomePessoa as nome, p.idPessoa, f.idInstituicao 
                    FROM funcionario f
                    JOIN contato c ON f.idContato = c.idContato
                    JOIN pessoa p ON v.idPessoa = p.idPessoa
                    WHERE c.email = ?";
        } else {
            die("Tipo de usuário inválido.");
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se achou o usuário E se a senha bate
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Salvando os dados exatos para o Header funcionar
            // Correção: Usar 'nome' porque foi o apelido (AS) dado no SELECT
            $_SESSION['idPessoa'] = $usuario['idPessoa'];
            $_SESSION['nome'] = $usuario['nome']; 
            
            // Redireciona e salva os dados específicos de cada tipo
            if ($tipo == 'voluntario') {
                $_SESSION['usuario_tipo'] = 'voluntario';
                // Correção: O header no PHP se concatena com ponto, não com tag de echo
                header("Location: " . BASE_URL . "/pages/painel_voluntario.php");
                exit;
            } else {
                $_SESSION['usuario_tipo'] = 'funcionario';
                $_SESSION['idInstituicao'] = $usuario['idInstituicao'];
                // Correção de sintaxe aqui também
                header("Location: " . BASE_URL . "/pages/painel_funcionario.php");
                exit;
            }

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