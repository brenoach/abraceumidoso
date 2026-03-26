<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once __DIR__ .'/connection/config.php';
    // require_once __DIR__ . '/vendor/autoload.php';
    require_once ROOT_PATH .'/includes/helpers.php'; 
    include ROOT_PATH .'/includes/header.php';
    include ROOT_PATH .'/includes/db.php';


if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

 //   Gerar a URL para o botão de login
        //echo "<a href='$loginUrl' style='padding:10px; background:#4285f4; color:white; text-decoration:none;'>Fazer Login com Google</a>";

 //   Criar o cliente do Google
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");    

  //              Gerar a URL para o botão de login
        $loginUrl = $client->createAuthUrl();

?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php BASE_URL;?>assets/css/estilo.css">
    <link rel="stylesheet" href="<?php BASE_URL;?>assets/css/inicio.css">
    <title>Abrace um Idoso</title>
    <style> .hero-container {display: none !important;}
</style>
</head>

<section class="hero-container">
    
    <div class="hero-image">
        <!-- <img src="<?php echo BASE_URL;?>assets/img/imagemBanner.png" alt="Idoso e neta lendo"> -->
    </div>

    <div class="hero-content">
        <h1>Conectando gerações com carinho</h1>
        <p>Encontre companhia, compartilhe histórias e faça a diferença na vida de quem tem muito a ensinar.</p>
        <a href="#" class="btn-hero">Saiba Mais</a>
    </div>

</section>
  <div class="banner">
    <div>
        <h1>Seja um farol de carinho.</h1>
        <p>Conecte-se a quem mais precisa</p>
    </div>

    <div>
        <a href= '<?php echo BASE_URL;?>pages/cadastro_voluntario.php'><button class="btn btn-marrom">Quero visitar</button></a>
        <a href='<?php echo BASE_URL;?>pages/cadastro_Instituicao.html'><button  class="btn btn-amarelo">Quero Receber Visitas</button></a>
    </div>
   
</div>

</section>
<?php include 'includes/footer.php';?>
</body>
</html>