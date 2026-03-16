<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    require_once __DIR__ . '../vendor/autoload.php';
    require_once 'includes/helpers.php'; 
    include 'includes/header.php';

    
    $clientID = '542179864570-vf7jgq7cqtq8snk5udevo5dubbkkshsr.apps.googleusercontent.com';
    $clientSecret = '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';
    $redirectUri = 'http://localhost/abraceumidoso/actions/callback_google.php';

    // Gerar a URL para o botão de login
        // echo "<a href='$loginUrl' style='padding:10px; background:#4285f4; color:white; text-decoration:none;'>Fazer Login com Google</a>";

    // Criar o cliente do Google
        $client = new Google_Client();
        $client->setClientId($clientID);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope("email");
        $client->addScope("profile");

                // Gerar a URL para o botão de login
        $loginUrl = $client->createAuthUrl();

        echo "<a href='$loginUrl' style='padding:10px; background:#4285f4; color:white; text-decoration:none;'>Fazer Login com Google</a>";
        // Se o usuário já estiver logado, não deixa ele ver a tela de login de novo

?>
<!DOCTYPE html>
<html lang="pt-BR">

<body>
<?php include 'includes/header.php'; 

?>
<section class="hero-container">
    
    <div class="hero-image">
        <img src="assets/img/imagemBanner.png" alt="Idoso e neta lendo">
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