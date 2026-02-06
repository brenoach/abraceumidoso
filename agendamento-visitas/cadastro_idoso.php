<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Residente</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <main class="container-principal">
        <div class="card-formulario">
            <h2>Novo Residente</h2>
            <p>Cadastre os dados pessoais do idoso.</p>

            <form method="POST" action="actions/salvar_idoso.php">
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
                        <label>Data de Nascimento:</label>
                        <input type="date" name="dtNascimento" required>
                    </div>
                </div>

                <div class="grupo-input">
                    <label>Instituição:</label>
                    <select name="idInstituicao" required>
                        <option value="" disabled selected>Selecione a casa de repouso...</option>
                        <option value="1">Lar dos Avós (Exemplo)</option>
                    </select>
                </div>

                <div class="grupo-input">
                    <label>História de Vida (Sobre):</label>
                    <textarea name="sobre" rows="4" style="width: 100%; padding: 10px; border-radius: 10px; border: 2px solid #f0ebdc;"></textarea>
                </div>

                <div class="banner">
                    <button type="submit" class="btn-marrom">Salvar Residente</button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>