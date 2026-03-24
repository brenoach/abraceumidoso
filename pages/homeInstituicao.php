<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrace um idoso</title>
    <link rel="stylesheet" href="./../assets/css/estilo.css">
    <link rel="stylesheet" href="./../assets/css/inicio.css">
</head>
<body>
   
<head>
    <meta charset="UTF-8">
    <title>Seu Título</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Asap:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    
</head>
<header class="cabecalho">
    <a href="#" class="logo">
        <img src="./../assets/img/logoPI.jpg" alt="LogoAbraceUmIdoso">
    </a>
    <nav class="nav-menu">
        <ul>
            <li><a href="cadastroVoluntario.html">Cadastrar</a></li>
            <li><a href="loginFake.html" class="ga-nav" title="agendamento">Login</a></li>
        </ul>
        <ul>
            <li><a href="home.php">Início</a></li>
            <li><a href="agendamento" class="ga-nav" title="agendamento">Agendamento</a></li>
            <li><a href="cartas" class="ga-nav" title="cartas">Cartas</a></li>
            <li><a href="contatos.html" class="ga-nav" title="contato">Fale Conosco</a></li>
            <li><a href="MUDAR" class="ga-nav" title="contato">Instituição</a></li>
        </ul>
    </nav>
</header>



<div class="banner">
    <div>
        <h1>Seja um farol de carinho.</h1>
        <p>Conecte-se a quem mais precisa</p>
    </div>

    <div>
        <a href="cadastroVoluntario.html"><button class="btn btn-marrom">Quero visitar</button></a>
        <a href="cadastroInstituicao.html"><button  class="btn btn-amarelo">Quero Receber Visitas</button></a>
    </div>
   
</div>


<section class="missao-section">
    <h1>Nossa Missão</h1>
    <div class="missao-content">
        <div class="missao-item">
            <div class="icone-circulo">
                <img src="./../assets/img/maos.png" alt="Aperto de mão" /> 
        </div>
    </div>
        
        <div class="missao-item">
            <div class="icone-circulo">
                <img src="./../assets/img/idosos.png" alt="idosos" /> 
            </div>
        </div>
        
        <div class="missao-item">
            <div class="icone-circulo">
                <img src="./../assets/img/pessoas.png" alt="Duas pessoas lado a lado" /> 
            </div>
        </div>

    </div>
</section>

<div class="linha-inferior"></div>

<footer class="rodape"><p>© 2026 RastroCerto. Todos os direitos reservados</p></footer>

</body>
</html>