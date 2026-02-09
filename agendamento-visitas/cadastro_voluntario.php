<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seja um Voluntário</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="cabecalho">
        <a href="#" class="logo"><img src="assets\img\logo.jpg" alt="Logo"></a>
        <nav class="nav-menu">
            <ul><li><a href="index.php">Voltar</a></li></ul>
        </nav>
    </header>

    <main class="container-principal">
        <div class="card-formulario">
            <h2>Cadastro de Voluntário</h2>
            <p>Junte-se a nós e faça a diferença.</p>

            <form method="POST" action="actions/salvar_voluntario.php" enctype="multipart/form-data">
                
                <div class="area-upload">
                    <label for="foto">
                        Clique para enviar sua foto
                        <span>(Formatos: JPG ou PNG)</span>
                    </label>
                    <input type="file" name="foto" id="foto" accept="image/*">
                </div>

                <h3>Dados Pessoais</h3>
                <div class="grupo-input">
                    <label>Nome Completo:</label>
                    <input type="text" name="nome" required>
                </div>

                <div class="linha-dupla">
                    <div class="grupo-input">
                        <label>CPF:</label>
                        <input type="text" name="cpf" maxlength="11" required>
                    </div>
                    <div class="grupo-input">
                        <label>Nascimento:</label>
                        <input type="date" name="dtNascimento" required>
                    </div>
                </div>

                <div class="grupo-input">
                    <label>Sobre você (Por que quer ser voluntário?):</label>
                    <textarea name="sobre" rows="3" class="campo-texto"></textarea>
                </div>
              
                <h3>Endereço</h3>
                <div class="linha-endereco">
                    <div class="grupo-input">
                        <label>CEP:</label>
                        <input type="text" name="cep" id="cep" maxlength="9" onblur="buscarCep()" required>
                    </div>
                     <div class="grupo-input">
                        <label>Estado:</label>
                        <input type="text" name="estado" id="uf" readonly>
                    </div>
                    <div class="grupo-input">
                        <label>Cidade:</label>
                        <input type="text" name="cidade" id="cidade"readonly>
                    </div>
                    <div class="grupo-input">
                        <label>Tipo de Logradouro:</label>
                        <input type="text" name="tpLogradouro" required>
                    </div>
                    <div class="grupo-input">
                        <label>Logradouro:</label>
                        <input type="text" name="nmlogradouro" id="rua"readonly>
                    </div>
                    <div class="grupo-input">
                        <label>Número:</label>
                        <input type="text" name="numero" required>
                    </div>
                    <div class="grupo-input">
                        <label>Complemento:</label>
                        <input type="text" name="complemento" required>
                    </div>
                    <div class="grupo-input">
                        <label>Bairro:</label>
                        <input type="text" name="bairro" readonly>
                    </div>
                </div>
                 <h3>Contato e Login</h3>
                <div class="linha-dupla">
                    <div class="grupo-input">
                        <label>Email:</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="grupo-input">
                        <label>Celular:</label>
                        <input type="text" name="celular" required>
                    </div>
                </div>

                <div class="grupo-input">
                    <label>Crie uma Senha:</label>
                    <input type="password" name="senha" required>
                </div>

                <div class="banner">
                    <button type="submit" class="btn-marrom">Finalizar Cadastro</button>
                </div>
            </form>
        </div>
    </main>
    <script src="assets/js/script.js">
        
        function buscarCep() {
            // 1. Pega o valor digitado no campo CEP
            let cep = document.getElementById('cep').value;

            // 2. Remove caracteres que não sejam números (ex: traço, ponto)
            cep = cep.replace(/\D/g, '');

            // 3. Verifica se o CEP tem 8 dígitos antes de fazer a requisição
            if (cep.length === 8) {
                
                // 4. Faz a busca na API ViaCEP
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(res => res.json()) // Converte a resposta para JSON (objeto JS)
                    .then(data => {
                        if (!data.erro) {
                            // 5. Se achou, preenche os campos com os IDs correspondentes
                            document.getElementById('rua').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('uf').value = data.uf;
                        } else {
                            alert("CEP não encontrado.");
                        }
                    })
                    .catch(error => console.error("Erro na API:", error));
            } else {
                alert("Formato de CEP inválido.");
            }
        }
    </script>
</body>
</html>

