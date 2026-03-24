<?php
    session_start();
    require "../restricao.php";
    require "../conexao/conexao.php";

    $idVoluntario = $_SESSION['idVoluntario'];

$sql = "SELECT p.idPessoa, p.nomePessoa, p.fotoPerfil, p.sobre, c.idContato, c.email, c.telefone, c.celular,
        e.idEndereco, e.cep, e.cidade, e.estado, e.bairro, e.nomeLogradouro, e.numero, e.complemento
        FROM voluntario v
        INNER JOIN pessoa p ON v.idPessoa = p.idPessoa
        INNER JOIN contato c ON v.idContato = c.idContato
        INNER JOIN endereco e ON v.idEndereco = e.idEndereco
        WHERE v.idVoluntario = :idVoluntario";

    $stmt = $pdo->prepare($sql);    
    $stmt->execute([ ':idVoluntario'=>$idVoluntario]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    $idPessoa   = $dados['idPessoa'];
    $idContato  = $dados['idContato'];
    $idEndereco = $dados['idEndereco'];
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
    
    <link rel="stylesheet" href="./../assets/css/perfil.css"> 
</head>
<header class="cabecalho">
    <a href="#" class="logo">
        <img src="./../assets/img/logoPI.jpg" alt="LogoAbraceUmIdoso">
    </a>
    <nav class="nav-menu">
        <ul>
            <li><a href="./../index.php">Sair</a></li>
        </ul>
        <ul>
            <li><a href="homeVoluntario.php">Início</a></li>
            <li><a href="agendamento" class="ga-nav" title="agendamento">Agendamento</a></li>
            <li><a href="cartas" class="ga-nav" title="cartas">Cartas</a></li>
            <li><a href="contatos.html" class="ga-nav" title="contato">Fale Conosco</a></li>
            <li><a href="perfilVoluntario.php" class="ga-nav" title="contato">Meu Perfil</a></li>
        </ul>
    </nav>
</header>

    <body>
    <main>
        <div class="perfil-container">
            <div class="fotoPerfil">
                <img src="./../assets/img/uploads/<?php var_dump($dados['fotoPerfil']);?>"
                 alt="Foto de <?php echo htmlspecialchars($dados['idPessoa']); ?>">
            </div>

            <h2><?= htmlspecialchars($dados['nomePessoa']) ?></h2>
            
            <div class="bio-box">
                <div class="titulo-sobre-mim"><label for="sobre-mim">Sobre Mim:</label><br></div>
                <p><?= htmlspecialchars($dados['sobre']) ?></p>
            </div>

            <h3>Informações pessoais:</h3>

            <?php
            // Função para mostrar campo, evita erros se dados faltarem
            function mostraCampo($label, $valor, $linkEditar) {
                $valor = htmlspecialchars($valor ?? '');
                echo "<div class='info-box'>
                        <span class='label'>$label</span>
                        <span class='valor'>$valor</span>
                      </div>";
            }

            mostraCampo('Email:', $dados['email'], 'editarVoluntario.php');
            mostraCampo('Telefone:', $dados['telefone'], 'editarVoluntario.php');
            mostraCampo('Celular:', $dados['celular'], 'editarVoluntario.php');
            mostraCampo('CEP:', $dados['cep'], 'editarVoluntario.php');
            mostraCampo('Cidade:', $dados['cidade'], 'editarVoluntario.php');
            mostraCampo('Estado:', $dados['estado'], 'editarVoluntario.php');
            mostraCampo('Bairro:', $dados['bairro'], 'editarVoluntario.php');
            mostraCampo('Rua:', $dados['nomeLogradouro'], 'editarVoluntario.php');
            mostraCampo('Nº:', $dados['numero'], 'editarVoluntario.php');
            mostraCampo('Complemento:', $dados['complemento'], 'editarEndereco.php');
            ?>

            <div class="info-box">
                <span class="label">Senha:</span>
                <span class="valor">********</span>
            </div>
            <br><div class="botoes"><li><a class="btn-azul" href="editarVoluntario.php">Editar</a></li>
                
        </div>
    </main>


</div>
 
    <footer class="rodape"><p>© 2026 RastroCerto. Todos os direitos reservados</p></footer>
</body>
</html>
