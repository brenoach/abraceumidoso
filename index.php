<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require __DIR__ .'/connection/config.php';
    require_once ROOT_PATH .'includes/helpers.php'; 
    include ROOT_PATH .'includes/header.php';

    $url = $_GET['url'] ?? 'login';
    $url = rtrim($url, '/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

switch ($url) {
    // ==========================================
    // 1. MOSTRAR A TELA (GET)
    // ==========================================
    case 'login':
        require __DIR__ . '/pages/login.php';
        break;

    // ==========================================
    // 2. PROCESSAR OS DADOS NO BANCO (POST)
    // ==========================================
    case 'processar-login':
        // Substitua pelo caminho do arquivo de ação que verifica a senha no seu projeto
        require __DIR__ . '/actions/login_action.php'; 
        break;

    // ==========================================
    // ROTA NÃO ENCONTRADA
    // ==========================================
    default:
        http_response_code(404);
        echo "<h2 style='text-align:center; padding: 50px; color: #5b3a26;'>Página não encontrada!</h2>";
        break;
}
// 3. Chama o motor das bibliotecas (Google, etc)
// Verifique se este arquivo existe no servidor!
if (file_exists(ROOT_PATH . 'vendor/autoload.php')) {
    require_once ROOT_PATH . 'vendor/autoload.php';
} else {
    die("Erro Crítico: A pasta 'vendor' não foi encontrada. O login com Google não vai funcionar.");
}

    
    $clientID = '542179864570-vf7jgq7cqtq8snk5udevo5dubbkkshsr.apps.googleusercontent.com';
    $clientSecret = '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';
    $redirectUri = 'http://localhost/abraceumidoso/actions/callback_google.php';

    
    //   Criar o cliente do Google
    $client = new Google_Client();
    $client->setClientId($clientID);
    $client->setClientSecret($clientSecret);
    $client->setRedirectUri($redirectUri);
    $client->addScope("email");
    $client->addScope("profile");    
    
    //              Gerar a URL para o botão de login
    $loginUrl = $client->createAuthUrl();
    
    
    //      Se o usuário já estiver logado, não deixa ele ver a tela de login de novo
    
    //   Gerar a URL para o botão de login
           echo "<a href='$loginUrl' style='padding:10px; background:#4285f4; color:white; text-decoration:none;'>Fazer Login com Google</a>";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrace um Idoso</title>
    <link rel="stylesheet" href="<?php echo BASE_URL;?>/assets/css/style.css?v=1.6">

    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/img/favicon.png">
    <link rel="apple-touch-icon" href="<?php echo BASE_URL; ?>/assets/img/favicon-apple.png">

    <style>
        .perfil-cabecalho { display: flex; align-items: center; gap: 10px; padding: 5px 15px; border-right: 1px solid #ddd; }
        .user-avatar img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #673AB7; }
        .user-info { display: flex; flex-direction: column; line-height: 1.2; }
        .user-info strong { color: #5A3821; font-size: 0.9rem; }
        .user-info small { color: #673AB7; font-size: 0.7rem; font-weight: bold; }
    </style>
</head>
<body>

<!-- <h1>Abrace um Idoso TESTE</h1> -->


<section class="hero-container">
    
    <div class="hero-image">
        <img src="<?= BASE_URL ?>/assets/img/imagemBanner.png" alt="Idoso e neta lendo">
    </div>

    <div class="hero-content">
        <h1>Conectando gerações com carinho</h1>
        <p>Encontre companhia, compartilhe histórias e faça a diferença na vida de quem tem muito a ensinar.</p>
        <a href="#" class="btn-hero">Saiba Mais</a>
    </div>

</section>
  
<?php include 'includes/footer.php';?>
</body>
</html>