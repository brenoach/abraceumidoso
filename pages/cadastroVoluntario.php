<?php
require "../conexao/conexao.php";
//require "../class/Pessoas.php";
//require "../class/Contatos.php";
//require "../class/Enderecos.php";
require "../class/ValidarEntradas.php";

$validar = new ValidarEntradas();

if($_SERVER['REQUEST_METHOD']==='POST'){
        $nomePessoa = trim(ucwords($_POST['nomePessoa']));
        $cpf = trim($_POST['cpf']);
        $dataNascimento = trim($_POST['dataNascimento']);
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
        $tipoLogradouro = null;//trim($_POST['tipoLogradouro']);
        $numero = trim($_POST['numero']);
        $complemento = trim($_POST['complemento']);
        $senha = trim($_POST['senha']);
        $confirmarSenha = trim($_POST['confirmarSenha']);
        $resetToken = null;
        $tokenExpira = null;

        // ---------------------------------------- Enviar imagem ----------------------------------------
        $imagem = null;

        if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === 0) {
                $dir = "./../assets/img/uploads/";
        if (!is_dir($dir)) mkdir($dir, 0755, true);
                $ext = strtolower(pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg','jpeg','png','gif'];
        if (!in_array($ext, $permitidas)) {
            die("Formato de imagem inválido");
        }
        $nomeArquivo = uniqid() . "." . $ext;
        $caminho = $dir . $nomeArquivo;
        if (!move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $caminho)) {
            die("Erro ao salvar imagem");
        }
        $imagem = $nomeArquivo;
        }
        
        // ---------------------------------------- Checar se os campos estão preenchidos ----------------------------------------
        $validar->obrigatorio('nomePessoa',$nomePessoa);
        $validar->obrigatorio('cpf',$cpf);
        $validar->obrigatorio('dataNascimento',$dataNascimento);
        $validar->obrigatorio('fotoPerfil',$fotoPerfil);
        $validar->obrigatorio('sobre',$sobre);
        $validar->obrigatorio('celular',$celular);
        $validar->obrigatorio('email',$email);
        $validar->obrigatorio('cep',$cep);
        $validar->obrigatorio('cidade',$cidade);
        $validar->obrigatorio('bairro',$bairro);
        $validar->obrigatorio('estado',$estado);
        $validar->obrigatorio('nomeLogradouro',$nomeLogradouro);
        $validar->obrigatorio('numero',$numero);
        $validar->obrigatorio('senha',$senha);
        $validar->obrigatorio('confirmarSenha',$confirmarSenha);

        // ---------------------------------------- Checar tamanho maximo ----------------------------------------
        $validar->tamanhoMax('nomePessoa',$nomePessoa, 50);
        $validar->tamanhoMax('sobre',$sobre, 150);
        $validar->tamanhoMax('email',$email,50);
        $validar->tamanhoMax('cidade',$cidade, 50);
        $validar->tamanhoMax('bairro',$bairro, 50);
        $validar->tamanhoMax('nomeLogradouro',$nomeLogradouro, 50);
        $validar->tamanhoMax('numero',$numero, 6);
        $validar->tamanhoMax('senha',$senha, 45);
        $validar->tamanhoMax('confirmarSenha',$confirmarSenha, 45);

        // ---------------------------------------- Checar se o campo é numérico ----------------------------------------
        $validar->numero('cpf',$cpf);
        $validar->numero('celular',$celular);
        $validar->numero('telefone',$telefone);
        $validar->numero('cep',$cep);
        $validar->numero('numero',$numero);

        // ---------------------------------------- Checar E-mail (email) ----------------------------------------
        $validar->email('email',$email);

        // ---------------------------------------- Checar se o damanho esta certo (cep,telefone,celular,cpf,estado)----------------------------------------
        $validar->tamanhoExato('cpf',$cpf,11);
        $validar->tamanhoExato('celular',$celular,11);
        $validar->tamanhoExato('telefone',$telefone, 11);
        $validar->tamanhoExato('cep',$cep, 8);
        $validar->tamanhoExato('estado',$estado, 2);

        // ---------------------------------------- Checar string sem número ----------------------------------------
        $validar->stringSemNumero('nomePessoa',$nomePessoa);
        $validar->stringSemNumero('estado',$estado);

        // ---------------------------------------- Checar Data de nascimento ----------------------------------------
        $validar->maiorDeIdade('dataNascimento',$dataNascimento);

        // ---------------------------------------- Checar Senha ----------------------------------------
        $validar->senha($senha, $confirmarSenha);

        if($validar->temErros()){
                        $erros = $validar->getErros();
                        header("Location: cadastroVoluntario.html");
        }else{
                try{
                /*======================================================PESSOAS======================================================*/
                        $pdo->beginTransaction();

                        $stmt = $pdo->prepare("INSERT INTO pessoa(nomePessoa, cpf, dataNascimento, fotoPerfil, sobre)
                        VALUES (:nomePessoa, :cpf, :dataNascimento, :fotoPerfil, :sobre)");

                        $stmt->execute([':nomePessoa'=>$nomePessoa,
                                                ':cpf'=>$cpf,
                                                ':dataNascimento'=>$dataNascimento,
                                                ':fotoPerfil'=>$imagem,
                                                ':sobre'=>$sobre]);
                        if(!$stmt->rowCount()){
                        throw new Exception("Erro ao inserir em pessoas");
                        }

                        $idPessoa = $pdo->lastInsertId();//Retorna o ID da última linha ou valor de sequência inserido

                /*======================================================CONTATOS======================================================*/                            
                        $stmt = $pdo->prepare("INSERT INTO contato(email,celular,telefone)
                                VALUES (:email,:celular,:telefone)");

                        $stmt->execute([':email'=>$email,
                                                ':celular'=>$celular,
                                                ':telefone'=>$telefone]);
                       
                        $idContato = $pdo->lastInsertId();//Retorna o ID da última linha ou valor de sequência inserido

                /*======================================================ENDERECOS======================================================*/        
                        $stmt = $pdo->prepare("INSERT INTO endereco(cep,estado,cidade,bairro,numero,nomeLogradouro,tipoLogradouro,complemento)
                        VALUES (:cep,:estado,:cidade,:bairro,:numero,:nomeLogradouro,:tipoLogradouro,:complemento)");

                        $stmt->execute([':cep'=>$cep,
                                                ':estado'=>$estado,
                                                ':cidade'=>$cidade,
                                                ':bairro'=>$bairro,
                                                ':numero'=>$numero,
                                                ':nomeLogradouro'=>$nomeLogradouro,
                                                ':tipoLogradouro'=>$tipoLogradouro,
                                                ':complemento'=>$complemento]);
                                       
                        $idEndereco = $pdo->lastInsertId();//Retorna o ID da última linha ou valor de sequência inserido

                /*======================================================ENDERECOS======================================================*/        
                        $stmt = $pdo->prepare("INSERT INTO voluntario(senha,idContato,idEndereco,idPessoa,resetToken,tokenExpira)
                        VALUES (:senha,:idContato,:idEndereco,:idPessoa,:resetToken,:tokenExpira)");

                        $stmt->execute([':senha'=>password_hash($senha, PASSWORD_DEFAULT),
                                                ':idContato'=>$idContato,
                                                ':idEndereco'=>$idEndereco,
                                                ':idPessoa'=>$idPessoa,
                                                ':resetToken'=>$resetToken,
                                                ':tokenExpira'=>$tokenExpira]);
                       
                        $idVoluntario = $pdo->lastInsertId();
                        $pdo->commit();
                        header("Location: loginFake.html");

                } catch (Exception $e) {
                        $pdo->rollBack();
                        echo $e->getMessage();
                }
        }
}
?>