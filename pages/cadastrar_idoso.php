<?php
session_start();
require_once __DIR__ . '/../connection/config.php';
require_once ROOT_PATH . 'includes/auth.php';
verificarAcesso('funcionario');
require_once ROOT_PATH . 'includes/helpers.php'; 
include ROOT_PATH . 'includes/header.php';
?>

<main class="container-principal">
    <div class="card-formulario">
        <h2>Cadastrar Novo Residente</h2>
        <form action="../includes/salvar_idoso.php" method="POST" enctype="multipart/form-data">
            
            <div class="perfil-upload-container">
                <div id="preview-foto" class="area-preview"><span>Sem foto</span></div>
                <label for="foto" class="label-upload">📷 Enviar Foto</label>
                <input type="file" name="foto" id="foto" accept="image/*" style="display:none">
            </div>

            <div class="grupo-input">
                <label>Nome Completo</label>
                <input type="text" name="nome" required>
            </div>
            <div class="grupo-input">
                <label>CPF</label>
                <input type="text" name="cpf" required>
            </div>
            <div class="grupo-input">
                <label>Data de Nascimento</label>
                <input type="date" name="dataNascimento" required>
            </div>

            <div class="grupo-radio">
                <label>Aceita Visitas? <input type="radio" name="aceitaVisita" value="1" checked> Sim</label>
                <label><input type="radio" name="aceitaVisita" value="0"> Não</label>
            </div>

            <div class="grupo-input">
                <label>Sobre ele</label>
                <textarea name="historia" rows="3"></textarea>
            </div>

            <h3>Horários de Visita</h3>
            <div class="container-disponibilidade">
                <?php
                $dias = ['Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado', 'Domingo'];
                foreach ($dias as $dia): ?>
                    <div class="linha-disponibilidade">
                        <label><input type="checkbox" name="dias[]" value="<?= $dia ?>"> <?= $dia ?></label>
                        <input type="time" name="horario_inicio[<?= $dia ?>]"> até 
                        <input type="time" name="horario_fim[<?= $dia ?>]">
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn-principal">Salvar Residente</button>
        </form>
    </div>
</main>

<script>
document.getElementById('foto').addEventListener('change', function() {
    const preview = document.getElementById('preview-foto');
    const reader = new FileReader();
    reader.onload = e => preview.innerHTML = `<img src="${e.target.result}" class="foto-perfil-idoso">`;
    reader.readAsDataURL(this.files[0]);
});
</script>