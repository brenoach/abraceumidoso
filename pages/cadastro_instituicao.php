<?php
require "../conexao/conexao.php";
//require "../class/Contatos.php";
//require "../class/Enderecos.php";
require "../class/ValidarEntradas.php";

$validar = new ValidarEntradas(); 
 
if($_SERVER['REQUEST_METHOD']==='POST'){
        $nomeInstituicao = trim(ucwords($_POST['nomeInstituicao']));
        $cnpj = trim($_POST['cnpj']);
        $fotoInstituicao = $_FILES['fotoInstituicao'];
        $celular = null;
        $email = trim($_POST['email']);
        $telefone = trim($_POST['telefone']);
        $cep = trim($_POST['cep']);
        $cidade = trim(ucwords($_POST['cidade']));
        $bairro = trim(ucwords($_POST['bairro']));
        $estado = trim($_POST['estado']);
        $nomeLogradouro = trim(ucwords($_POST['nomeLogradouro']));;
        $tipoLogradouro = "null";
        $numero = trim($_POST['numero']);
        $complemento = trim($_POST['complemento']);
        $senha = trim($_POST['senha']);
        $confirmarSenha = trim($_POST['confirmarSenha']);
   
        // ---------------------------------------- Enviar imagem ----------------------------------------
        $imagem = null;

        if (isset($_FILES['fotoInstituicao']) && $_FILES['fotoInstituicao']['error'] === 0) {
                $dir = "./../assets/img/uploads/";
        if (!is_dir($dir)) mkdir($dir, 0755, true);
                $ext = strtolower(pathinfo($_FILES['fotoInstituicao']['name'], PATHINFO_EXTENSION));
                $permitidas = ['jpg','jpeg','png','gif'];
        if (!in_array($ext, $permitidas)) {
            die("Formato de imagem inválido");
        }
        $nomeArquivo = uniqid() . "." . $ext;
        $caminho = $dir . $nomeArquivo;
        if (!move_uploaded_file($_FILES['fotoInstituicao']['tmp_name'], $caminho)) {
            die("Erro ao salvar imagem");
        }
        $imagem = $nomeArquivo;
        }
        
        // ---------------------------------------- Checar se os campos estão preenchidos ----------------------------------------
        $validar->obrigatorio('nomeInstituicao',$nomeInstituicao);
        $validar->obrigatorio('cnpj',$cnpj);
        $validar->obrigatorio('fotoInstituicao',$fotoInstituicao);
        $validar->obrigatorio('email',$email);
        $validar->obrigatorio('telefone',$telefone);
        $validar->obrigatorio('cep',$cep);
        $validar->obrigatorio('cidade',$cidade);
        $validar->obrigatorio('bairro',$bairro);
        $validar->obrigatorio('estado',$estado);
        $validar->obrigatorio('nomeLogradouro',$nomeLogradouro);
        $validar->obrigatorio('numero',$numero);
        $validar->obrigatorio('senha',$senha);
        $validar->obrigatorio('confirmarSenha',$confirmarSenha);

        // ---------------------------------------- Checar tamanho maximo ----------------------------------------
        $validar->tamanhoMax('nomeInstituicao',$nomeInstituicao, 50);
        $validar->tamanhoMax('email',$email,50);
        $validar->tamanhoMax('cidade',$cidade, 50);
        $validar->tamanhoMax('bairro',$bairro, 50);
        $validar->tamanhoMax('nomeLogradouro',$nomeLogradouro, 50);
        $validar->tamanhoMax('numero',$numero, 6);
        $validar->tamanhoMax('senha',$senha, 45);
        $validar->tamanhoMax('confirmarSenha',$confirmarSenha, 45);

        // ---------------------------------------- Checar se o campo é numérico ----------------------------------------
        $validar->numero('cnpj',$cnpj);
        $validar->numero('telefone',$telefone);
        $validar->numero('cep',$cep);
        $validar->numero('numero',$numero);

        // ---------------------------------------- Checar E-mail (email) ----------------------------------------
        $validar->email('email',$email);

        // ---------------------------------------- Checar se o damanho esta certo (cep,telefone,celular,cpf,estado)----------------------------------------
        $validar->tamanhoExato('cnpj',$cnpj,14);
        $validar->tamanhoExato('telefone',$telefone, 11);
        $validar->tamanhoExato('cep',$cep, 8);
        $validar->tamanhoExato('estado',$estado, 2);

        // ---------------------------------------- Checar string sem número ----------------------------------------
        $validar->stringSemNumero('nomeInstituicao',$nomeInstituicao);
        $validar->stringSemNumero('estado',$estado);

        // ---------------------------------------- Checar Senha ----------------------------------------
        $validar->senha($senha, $confirmarSenha);

        if($validar->temErros()){
                $erros = $validar->getErros();
                header("Location: cadastroInstituicao.html");
        }else{
                try{       
                /*======================================================CONTATOS======================================================*/                            
                        $pdo->beginTransaction();
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

                /*======================================================INSTITUICAO======================================================*/        
                        $stmt = $pdo->prepare("INSERT INTO instituicao(fotoInstituicao,nomeInstituicao,cnpj,senha,idContato,idEndereco)
                        VALUES (:fotoInstituicao,:nomeInstituicao,:cnpj,:senha,:idContato,:idEndereco)");

                        $stmt->execute([':nomeInstituicao'=>$nomeInstituicao,
                                                ':fotoInstituicao'=>$imagem,
                                                ':cnpj'=>$cnpj,
                                                ':senha'=>password_hash($senha, PASSWORD_DEFAULT),
                                                ':idContato'=>$idContato,
                                                ':idEndereco'=>$idEndereco]);
                       
                        $idInstituicao = $pdo->lastInsertId();
                        $pdo->commit();
                        header("Location: loginFake.html");
                        
                } catch (Exception $e) {
                        $pdo->rollBack();
                        echo $e->getMessage();
                }
        }
}
?>