// Verifica se achou o usuário E se a senha bate
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            
            // Login Sucesso: Salva dados na Sessão
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $tipo;

            // Agora o header() vai funcionar perfeitamente!
            if ($tipo == 'voluntario') {
                header("Location: ../painel_voluntario.php");
            } else {
                $_SESSION['instituicao_id'] = $usuario['instituicao_idinstituicao'];
                header("Location: ../painel_funcionario.php");
            }
            exit;

        } else {