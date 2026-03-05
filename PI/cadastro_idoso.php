<?php
require_once 'includes/auth.php';
verificarAcesso('funcionario'); // Bloqueia quem não for funcionário!
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Idoso - Painel do Funcionário</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container-principal">
        <div class="card-formulario">
            <h2>Cadastrar Novo Residente</h2>
            <p>Preencha os dados do idoso e defina os horários disponíveis para visitas.</p>

            <form method="POST" action="actions/salvar_idoso.php" enctype="multipart/form-data">
                
                <div class="area-upload">
                    <label for="foto">
                        Clique para enviar a foto do idoso
                        <span>(Formatos: JPG ou PNG)</span>
                    </label>
                    <input type="file" name="foto" id="foto" accept="image/*">
                </div>

                <h3>Dados Pessoais</h3>
                <div class="grupo-input">
                    <label>Nome Completo do Idoso:</label>
                    <input type="text" name="nome" required>
                </div>

                <div class="linha-dupla">
                    <div class="grupo-input">
                        <label>Data de Nascimento:</label>
                        <input type="date" name="dtNascimento" required>
                    </div>
                    <div class="grupo-input">
                        <label>Grau de Dependência / Necessidades Médicas:</label>
                        <input type="text" name="necessidades" placeholder="Ex: Cadeirante, Alzheimer leve..." required>
                    </div>
                </div>

                <div class="grupo-input">
                    <label>História de Vida (Breve resumo para o voluntário ler):</label>
                    <textarea name="historia" rows="4" class="campo-texto" placeholder="Gosta de jogar xadrez, foi professor..." required></textarea>
                </div>

                <h3>Disponibilidade para Visitas</h3>
                <p style="font-size: 0.9em; color: #666; margin-bottom: 15px;">Selecione os dias da semana e informe o horário (Ex: 14:00 às 16:00)</p>
                
                <div class="linha-dias-semana">
                    <div class="grupo-checkbox">
                        <label><input type="checkbox" name="dias[]" value="Segunda"> Segunda-feira</label>
                        <input type="time" name="horario_inicio_Segunda" title="Horário de Início"> até 
                        <input type="time" name="horario_fim_Segunda" title="Horário de Fim">
                    </div>

                    <div class="grupo-checkbox">
                        <label><input type="checkbox" name="dias[]" value="Terça"> Terça-feira</label>
                        <input type="time" name="horario_inicio_Terça"> até 
                        <input type="time" name="horario_fim_Terça">
                    </div>

                    <div class="grupo-checkbox">
                        <label><input type="checkbox" name="dias[]" value="Quarta"> Quarta-feira</label>
                        <input type="time" name="horario_inicio_Quarta"> até 
                        <input type="time" name="horario_fim_Quarta">
                    </div>

                    </div>

                <div class="banner" style="margin-top: 20px;">
                    <button type="submit" class="btn-marrom">Cadastrar Idoso e Horários</button>
                </div>
            </form>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>