<?php
//123456*Ty bad@gmail.com
    session_start();
    require "../restricao.php";
    require "../conexao/conexao.php";
    require "../class/ValidarEntradas.php";

    $idVoluntario = $_SESSION['idVoluntario'];
    $validar = new ValidarEntradas();

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fotoPerfil = $_FILES['fotoPerfil'];
        $sobre = trim($_POST['sobre']);
        $celular = trim($_POST['celular']);;
        $email = trim($_POST['email']);
        $telefone = trim($_POST['telefone']);
        $cep = trim($_POST['cep']);
        $cidade = trim(ucwords($_POST['cidade']));
        $bairro = trim(ucwords($_POST['bairro']));
        $estado = trim($_POST['estado']);
        $nomeLogradouro = trim(ucwords($_POST['nomeLogradouro']));
        $numero = trim($_POST['numero']);
        $complemento = trim($_POST['complemento']);
        $senha = trim($_POST['senha']);

        // ---------------------------------------- Checar se os campos estão preenchidos ----------------------------------------
        //$validar->obrigatorio('fotoPerfil',$fotoPerfil);
        $validar->obrigatorio('sobre',$sobre);
        $validar->obrigatorio('celular',$celular);
        $validar->obrigatorio('email',$email);
        $validar->obrigatorio('cep',$cep);
        $validar->obrigatorio('cidade',$cidade);
        $validar->obrigatorio('bairro',$bairro);
        $validar->obrigatorio('estado',$estado);
        $validar->obrigatorio('nomeLogradouro',$nomeLogradouro);
        $validar->obrigatorio('numero',$numero);
        //$validar->obrigatorio('senha',$senha);

        // ---------------------------------------- Checar tamanho maximo ----------------------------------------
        $validar->tamanhoMax('sobre',$sobre, 150);
        $validar->tamanhoMax('email',$email,50);
        $validar->tamanhoMax('cidade',$cidade, 50);
        $validar->tamanhoMax('bairro',$bairro, 50);
        $validar->tamanhoMax('nomeLogradouro',$nomeLogradouro, 50);
        $validar->tamanhoMax('numero',$numero, 6);

        // ---------------------------------------- Checar se o campo é numérico ----------------------------------------
        $validar->numero('celular',$celular);
        $validar->numero('telefone',$telefone);
        $validar->numero('cep',$cep);
        $validar->numero('numero',$numero);

        // ---------------------------------------- Checar E-mail (email) ----------------------------------------
        $validar->email('email',$email);

        // ---------------------------------------- Checar se o damanho esta certo (cep,telefone,celular,cpf,estado)----------------------------------------
        $validar->tamanhoExato('celular',$celular,11);
        $validar->tamanhoExato('telefone',$telefone, 11);
        $validar->tamanhoExato('cep',$cep, 8);
        $validar->tamanhoExato('estado',$estado, 2);

        // ---------------------------------------- Checar string sem número ----------------------------------------
        $validar->stringSemNumero('estado',$estado);

        // ---------------------------------------- Checar Senha ----------------------------------------
        //$validar->senha($senha, $confirmarSenha);

        if($validar->temErros()){
                $erros = $validar->getErros();
                var_dump($erros);
                //header("Location: editarVoluntario.php");
        }else{
                try{

                /*======================================================PESSOAS======================================================*/      
                    $pdo->beginTransaction();
                    $stmt = $pdo->prepare("UPDATE pessoa 
                                                    SET sobre = :sobre,
                                                    fotoPerfil = :fotoPerfil
                                                    WHERE idPessoa = :idPessoa");

                    $stmt->execute([':fotoPerfil'=> $fotoPerfil,
                                                ':sobre'=> $sobre,
                                                ':idPessoa'=> $idPessoa]);

                /*======================================================CONTATOS======================================================*/                            
                        $stmt = $pdo->prepare("UPDATE contato
                                                        SET email = :email,
                                                        celular = :celular,
                                                        telefone = :telefone
                                                        WHERE idContato = :idContato");
         
                        $stmt->execute([':email'=>$email,
                                                ':celular'=>$celular,
                                                ':telefone'=>$telefone,
                                                ':idContato'=>$idContato]);

                /*======================================================ENDERECOS======================================================*/        
                        $stmt = $pdo->prepare("UPDATE endereco
                                                        SET cep = :cep,
                                                        estado = :estado,
                                                        cidade = :cidade,
                                                        bairro = :bairro,
                                                        numero = :numero,
                                                        nomeLogradouro = :nomeLogradouro,
                                                        complemento = :complemento
                                                        WHERE idEndereco = :idEndereco");

                        $stmt->execute([':cep'=>$cep,
                                                ':estado'=>$estado,
                                                ':cidade'=>$cidade,
                                                ':bairro'=>$bairro,
                                                ':numero'=>$numero,
                                                ':nomeLogradouro'=>$nomeLogradouro,
                                                ':complemento'=>$complemento,
                                                ':idEndereco'=>$idEndereco]);
                    
                /*======================================================ENDERECOS======================================================*/        
                        /*$stmt = $pdo->prepare("UPDATE contato
                                                        SET senha = :senha
                                                        WHERE idVoluntario = :idVoluntario");

                        $stmt->execute([':senha'=>password_hash($senha, PASSWORD_DEFAULT),
                                                ':idContato'=>$idContato,
                                                ':idEndereco'=>$idEndereco,
                                                ':idPessoa'=>$idPessoa,
                                                ':resetToken'=>$resetToken,
                                                ':tokenExpira'=>$tokenExpira]);*/

                        $pdo->commit();
                        header("Location: perfilVoluntario.php");
                        exit;
                } catch (Exception $e) {
                        $pdo->rollBack();
                        echo "ERRO: ".$e->getMessage();
                }
        }

    }
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
                <img src="./../assets/img/uploads/<?php echo $dados['fotoPerfil']; ?>" 
                    alt="Foto de <?php echo htmlspecialchars($dados['idPessoa']); ?>">
            </div>

            <h2><?= htmlspecialchars($dados['nomePessoa']) ?></h2>

            <form action="editarVoluntario.php" method="post">
                <div class="titulo-sobre-mim"><label for="sobre-mim">Sobre Mim:</label><br>
                <textarea type="text" name="sobre" id="sobre"><?= htmlspecialchars($dados['sobre']);?></textarea></div>
                
                <h3>Informações pessoais:</h3>

                <?php
                // Função para mostrar campo, evita erros se dados faltarem
                function mostraCampo($label, $valor, $linkEditar) {
                    $valor = htmlspecialchars($valor ?? '');
                    echo "<div class='info-box'>
                            <span class='label'>$label</span>
                            <span class='valor'>$valor</span>
                        </div>";
                }?>
                <div class="input-grupo"><label for="email">E-mail:</label>
                <input type="email" name="email" id="email" value="<?= $dados['email'] ?>"></div>
                <div class="input-grupo"><label for="celular">Celular:</label><input type="tel" name="celular" id="celular" maxlength="12" value="<?=$dados['celular']?>"></div>
                <div class="input-grupo"><label for="telefone">Telefone:</label><input type="tel" name="telefone" id="telefone" maxlength="12" value="<?=$dados['telefone']?>"></div>

                <div class="input-linha">
                    <div class="input-grupo input-grande"><label for="cep">CEP:</label><input type="text" name="cep" id="cep" value="<?=$dados['cep']?>"></div>
                    <div class="input-grupo input-pequeno"><label for="cidade">Cidade:</label><input type="text" name="cidade" id="cidade" value="<?=$dados['cidade']?>"></div>
                    <div class="input-grupo input-pequeno"><label for="bairro">Bairro:</label><input type="text" name="bairro" id="bairro" value="<?=$dados['bairro']?>"></div>
                    <div class="input-grupo input-pequeno">
                    <label for="estado">Estado:</label>
                        <select name="estado" id="estado" value="<?=$dados['estado']?>">
                            <option value="AC">AC</option>
                            <option value="AL">AL</option>
                            <option value="AP">AP</option>
                            <option value="AM">AM</option>
                            <option value="BA">BA</option>
                            <option value="CE">CE</option>
                            <option value="DF">DF</option>
                            <option value="GO">GO</option>
                            <option value="MA">MA</option>
                            <option value="MT">MT</option>
                            <option value="MS">MS</option>
                            <option value="MG">MG</option>
                            <option value="PA">PA</option>
                            <option value="PB">PB</option>
                            <option value="PR">PR</option>
                            <option value="PE">PE</option>
                            <option value="PI">PI</option>
                            <option value="RJ">RJ</option>
                            <option value="RN">RN</option>
                            <option value="RS">RS</option>
                            <option value="RO">RO</option>
                            <option value="RR">RR</option>
                            <option value="SC">SC</option>
                            <option value="SP">SP</option>
                            <option value="SE">SE</option>
                            <option value="TO">TO</option>
                        </select></div>
                </div>

                <div class="input-linha">
                    <div class="input-grupo input-grande"><label for="nomeLogradouro">Rua:</label><input type="text" name="nomeLogradouro" id="nomeLogradouro" value="<?=$dados['nomeLogradouro']?>"></div>
                    <div class="input-grupo input-mini"><label for="numero">Número:</label><input type="text" name="numero" id="numero" value="<?=$dados['numero']?>"></div>
                    <div class="input-grupo input-grande"><label for="complemento">Complemento:</label><input type="text" name="complemento" id="complemento" value="<?=$dados['complemento']?>"></div>
                </div>
                <div class="input-grupo"><label for="senha">Senha:</label><input type="password" name="senha" id="senha" value="<?=$dados['senha']?>"></div>

                <button type="submit" class="btn btn-marrom">Salvar</button><br>
            </form>
            <div class="botoes">
                <li><a class="btn-azul" href="perfilVoluntario.php">Cancelar</a></li>
            </div>
        </div>
    </main>
</div>
    <footer class="rodape"><p>© 2026 RastroCerto. Todos os direitos reservados</p></footer>
</body>
</html>