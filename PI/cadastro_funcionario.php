<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Funcionário</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="cabecalho">
        <a href="#" class="logo"><img src="assets/img/logo.jpg" alt="Logo"></a>
        <nav class="nav-menu">
            <ul><li><a href="index.php">Voltar</a></li></ul>
        </nav>
    </header>

    <main class="container-principal">
        <div class="card-formulario">
            <h2>Cadastro de Funcionário</h2>
            <p>Cadastre os colaboradores da instituição.</p>

            <form method="POST" action="actions/salvar_funcionario.php" enctype="multipart/form-data">
                
                <div class="area-upload">
                    <label for="foto">
                        Foto do Crachá/Perfil
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

                <div class="linha-dupla">
                    <div class="grupo-input">
                        <label>Cargo / Função:</label>
                        <input type="text" name="cargo" placeholder="Ex: Enfermeiro, Cozinheira..." required>
                    </div>
                    
                    <div class="grupo-input">
                        <label>Instituição:</label>
                        <select name="idInstituicao" required>
                            <option value="" disabled selected>Selecione o local de trabalho...</option>
                            <option value="1">Lar dos Avós (Unidade 1)</option>
                            <option value="2">Recanto Feliz (Unidade 2)</option>
                        </select>
                    </div>
                </div>
                
                <div class="grupo-input">
                    <label>Resumo Profissional (Sobre):</label>
                    <textarea name="sobre" rows="2" class="campo-texto"></textarea>
                </div>
              
                <h3>Endereço</h3>
                <div class="linha-endereco">
                    <div class="grupo-input">
                        <label>CEP:</label>
                        <input type="text" name="cep" id="cep" maxlength="9" onblur="buscarCep()" required>
                        <span id="erro-cep" style="color:red; display:none; font-size:12px;">CEP não encontrado</span>
                    </div>
                    
                    <div class="linha-dupla">
                        <div class="grupo-input">
                            <label>Estado:</label>
                            <input type="text" name="estado" id="uf" readonly>
                        </div>
                        <div class="grupo-input">
                            <label>Cidade:</label>
                            <input type="text" name="cidade" id="cidade" readonly>
                        </div>
                    </div>

                    <div class="grupo-input">
                        <label>Bairro:</label>
                        <input type="text" name="bairro" id="bairro" readonly>
                    </div>

                    <div class="grupo-input">
                        <label>Rua (Logradouro):</label>
                        <input type="text" name="nmlogradouro" id="rua" readonly>
                    </div>

                    <div class="linha-dupla">
                         <div class="grupo-input">
                            <label>Número:</label>
                            <input type="text" name="numero" id="numero" required>
                        </div>
                        <div class="grupo-input">
                            <label>Complemento:</label>
                            <input type="text" name="complemento">
                        </div>
                    </div>
                </div>

                <h3>Contato e Login</h3>
                <div class="linha-dupla">
                    <div class="grupo-input">
                        <label>Email Corporativo ou Pessoal:</label>
                        <input type="email" name="email" required>
                    </div>
                    <div class="grupo-input">
                        <label>Celular:</label>
                        <input type="text" name="celular" required>
                    </div>
                </div>

                <div class="grupo-input">
                    <label>Definir Senha de Acesso:</label>
                    <input type="password" name="senha" required>
                </div>

                <div class="banner">
                    <button type="submit" class="btn-marrom">Cadastrar Funcionário</button>
                </div>
            </form>
        </div>
    </main>

    <script src="assets/js/script.js"></script>

    <script>
        function buscarCep() {
            // 1. Pega o valor
            let cepInput = document.getElementById('cep');
            let cep = cepInput.value.replace(/\D/g, '');

            // 2. Validação básica
            if (cep.length === 8) {
                // Efeito visual de carregando
                document.getElementById('rua').value = "...";
                
                // 3. Busca na API
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.erro) {
                            // 4. Preenche os campos (IDs devem bater com o HTML acima)
                            document.getElementById('rua').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('uf').value = data.uf;
                            
                            // Foca no número
                            document.getElementById('numero').focus();
                        } else {
                            alert("CEP não encontrado.");
                            limparCampos();
                        }
                    })
                    .catch(error => {
                        console.error("Erro:", error);
                        alert("Erro ao buscar CEP.");
                    });
            } else {
                alert("CEP inválido (digite 8 números).");
            }
        }

        function limparCampos() {
            document.getElementById('rua').value = "";
            document.getElementById('bairro').value = "";
            document.getElementById('cidade').value = "";
            document.getElementById('uf').value = "";
        }
    </script>
</body>
</html>