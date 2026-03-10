<?php
require_once '../includes/db.php';
date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $tipo = $_POST['tipo'];
    $nova_senha = $_POST['nova_senha'];
    $confirma_senha = $_POST['confirma_senha'];

    // Verifica se o usuário digitou a mesma senha nas duas caixinhas
    if ($nova_senha !== $confirma_senha) {
        die("<script>alert('As senhas não coincidem! Tente novamente.'); window.history.back();</script>");
    }

    // Criptografa a nova senha (A Mágica da Segurança!)
    $senhaCriptografada = password_hash($nova_senha, PASSWORD_DEFAULT);

    try {
        // Atualiza a senha e ANULA o token para que o link não possa ser reusado
        if ($tipo == 'voluntario') {
            $sql = "UPDATE voluntario SET senha = ?, reset_token = NULL, token_expira = NULL WHERE reset_token = ?";
        } else {
            $sql = "UPDATE funcionario SET senha = ?, reset_token = NULL, token_expira = NULL WHERE reset_token = ?";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$senhaCriptografada, $token]);

        // Verifica se realmente alterou alguma linha (se o token ainda existia no momento do clique)
        if ($stmt->rowCount() > 0) {
            echo "<script>
                    alert('Sucesso! Sua senha foi alterada. Você já pode fazer o login.');
                    window.location.href = '../login.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Erro: Não foi possível alterar a senha. O link pode ter expirado.');
                    window.location.href = '../esqueci_senha.php';
                  </script>";
        }

    } catch (Exception $e) {
        echo "Erro no banco de dados: " . $e->getMessage();
    }
} else {
    echo "Acesso inválido.";
}
?>