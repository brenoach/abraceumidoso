<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/db.php';

// Mesmas configurações da index
$clientID = '542179864570-vf7jgq7cqtq8snk5udevo5dubbkkshsr.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/abraceUmIdoso/actions/callback_google.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        $client->setAccessToken($token);

        // Pegar dados do Google
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email = $google_account_info->email;


        echo "E-mail recebido do Google: " . $email . "<br>";

        // 1. Tentar achar como VOLUNTÁRIO
        $sqlV = "SELECT v.idVoluntario as id, p.nmPessoa as nome 
                 FROM voluntario v
                 JOIN contatos c ON v.contatos_idcontatos = c.idcontatos
                 JOIN pessoa p ON v.pessoa_idPessoa = p.idPessoa
                 WHERE c.email = ?";
        
        $stmt = $pdo->prepare($sqlV);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
    echo "Usuário encontrado! Nome: " . $user['nome'];
    // ... restante do código de sessão
} else {
    echo "Nenhum usuário encontrado com esse e-mail no banco.";
}
if ($user) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['usuario_nome'] = $user['nome'];
            $_SESSION['usuario_tipo'] = 'funcionario';
            $_SESSION['instituicao_id'] = $user['instituicao_idinstituicao'];
            header("Location: ../painel_funcionario.php");
            exit;
        }

        // 3. Se não achou em nenhum dos dois
        echo "<script>alert('E-mail do Google ($email) não cadastrado no sistema.'); window.location.href='../index.php';</script>";

    } catch (Exception $e) {
        echo "Erro ao autenticar: " . $e->getMessage();
    }
} else {
    header("Location: ../index.php");
    exit;
}
?>