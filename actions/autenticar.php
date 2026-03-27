<?php
session_start();

// ATENÇÃO: Verifique se o nome do seu arquivo é config.php ou conf.php
require_once __DIR__ . '/../connection/config.php'; 
require_once __DIR__ . '/../includes/db.php';

if (!isset($pdo)) {
    die("Erro crítico: A conexão \$pdo não foi criada. Verifique o arquivo db.php.");
}

// Exibição de erros (Pode remover ou comentar quando o site for para produção)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// // --- MODO RAIO-X ATIVADO ---
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     echo "<div style='background: #222; color: #0f0; padding: 20px; font-family: monospace;'>";
//     echo "<h3>🕵️ Dados recebidos do Formulário:</h3>";
//     echo "<pre>";
//     var_dump($_POST);
//     echo "</pre>";
//     echo "</div>";
//     exit; // O 'exit' mata o processo aqui. Ele impede o redirecionamento e os alertas!
// }



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
            // Query do Voluntário (Correta)
            $sql = "SELECT v.idVoluntario as id, v.senha, p.nomePessoa as nome, p.idPessoa 
                    FROM voluntario v
                    JOIN contato c ON v.idContato = c.idContato
                    JOIN pessoa p ON v.idPessoa = p.idPessoa
                    WHERE c.email = ?";
        } else if ($tipo == 'funcionario') {
            // Query do Funcionário (Corrigida: p.idPessoa = f.idPessoa)
            $sql = "SELECT f.idFuncionario as id, f.senha, p.nomePessoa as nome, p.idPessoa, f.idInstituicao 
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

        // --- NOVO RAIO-X: O QUE VEIO DO BANCO? ---
        echo "<div style='background: #000; color: #ff0; padding: 20px; font-family: monospace;'>";
        echo "<h3>🕵️ Dados que o PHP achou no Banco:</h3>";
        var_dump($usuario);
        
        if ($usuario) {
            echo "<br>Teste de Senha: ";
            var_dump(password_verify($senha, $usuario['senha']));
        }
        echo "</div>";
        exit;
        // ------------------------------------------

        // Verifica se achou o usuário E se a senha bate
        if ($usuario && password_verify($senha, $usuario['senha'])) {

        // Verifica se achou o usuário E se a senha bate
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Grava as informações na Sessão para usar no Painel
            $_SESSION['idPessoa'] = $usuario['idPessoa'];
            $_SESSION['nome'] = $usuario['nome']; 
            
            // Redireciona de acordo com o tipo
            if ($tipo == 'voluntario') {
                $_SESSION['tipo_usuario'] = 'voluntario';
                header("Location: " . BASE_URL . "/pages/painel_voluntario.php");
                exit;
            } else {
                $_SESSION['tipo_usuario'] = 'funcionario';
                $_SESSION['idInstituicao'] = $usuario['idInstituicao'];
                header("Location: " . BASE_URL . "/pages/painel_funcionario.php");
                exit;
            }

        } else {
            // Se a senha estiver errada ou o e-mail não existir
            echo "<script>
                    alert('E-mail ou senha incorretos! Ou você ainda não está cadastrado.'); 
                    window.location.href='../pages/login.php';
                  </script>";
        }

    } catch (PDOException $e) {
        echo "<div style='color:red; padding: 20px;'>Erro no sistema: " . $e->getMessage() . "</div>";
    }
} else {
    echo "Acesso direto não permitido.";
}
?>