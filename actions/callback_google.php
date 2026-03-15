<?php
session_start();
require_once '../includes/db.php';
// O caminho do autoload pode variar dependendo de onde está sua pasta vendor
require_once  '../vendor/autoload.php'; 

$clientID = '542179864570-vf7jgq7cqtq8snk5udevo5dubbkkshsr.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/abraceumidoso/actions/callback_google.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

// 1. Verifica se o Google enviou o código de autorização na URL
if (isset($_GET['code'])) {
    
    // 2. Troca o código por um Token de Acesso válido
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    // 3. Pede os dados do perfil do usuário para o Google
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    
    $google_email = $google_account_info->email;
    $google_nome = $google_account_info->name;
    $google_foto = $google_account_info->picture; // Foto direto do Gmail!

    try {
        // 4. VERIFICAÇÃO NO SEU BANCO DE DADOS
        // Vamos procurar se esse email já existe na tabela pessoa
        $sql = "SELECT p.idPessoa, p.nomePessoa, v.idVoluntario 
                FROM pessoa p
                LEFT JOIN voluntario v ON p.idPessoa = v.idPessoa
                LEFT JOIN contato c ON v.idPessoa = c.idcontato
                WHERE c.email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$google_email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario) {
            // USUÁRIO JÁ EXISTE: Cria a sessão e loga ele direto
            $_SESSION['usuario_id'] = $usuario['idVoluntario'];
            $_SESSION['usuario_nome'] = $usuario['nomePessoa'];
            $_SESSION['usuario_tipo'] = 'voluntario';
            $_SESSION['usuario_foto'] = $google_foto; // Podemos usar a foto do Google

            header("Location: ../pages/painel_voluntario.php");
            exit;
        } else {
            // USUÁRIO NÃO EXISTE: Redireciona para completar o cadastro
            // Salvamos os dados do Google na sessão temporária para preencher o form
            $_SESSION['cadastro_google'] = [
                'nome' => $google_nome,
                'email' => $google_email,
                'foto' => $google_foto
            ];
            
            header("Location: ../pages/cadastro_voluntario.php?msg=completar_cadastro");
            exit;
        }

    } catch (PDOException $e) {
        die("Erro ao conectar com o banco: " . $e->getMessage());
    }

} else {
    // Se tentarem acessar a página direto sem vir do Google
    header("Location: ../index.php");
    exit;
}