
<main class="container-principal">
    <div class="card-formulario">
        <h2>Cadastrar Novo Residente</h2>
        <form action="<?php echo BASE_URL; ?>/actions/salvar_idoso.php" method="POST" enctype="multipart/form-data">
            
            <!-- <div class="perfil-upload-container">
                <div id="preview-foto" class="area-preview"><span>Sem foto</span></div>
                <label for="foto" class="label-upload">📷 Enviar Foto</label>
                <input type="file" name="foto" id="foto" accept="image/*" style="display:none">
            </div> -->
            <div class="secao-foto">
                <label for="fotoPerfil" class="perfil-upload-container" title="Clique para escolher uma foto">
                    <img id="preview-foto" src="<?php echo BASE_URL; ?>/assets/img/perfil_placeholder.png" alt="Pré-visualização da foto">
        
                <div class="perfil-overlay">
                    <span class="emoji-camera">📷</span>
                    <span class="texto-upload">Enviar Foto</span>
                </div>
           
                </label>
    
                <input type="file" name="fotoPerfil" id="fotoPerfil" accept="image/*" onchange="previewImage(this);">
    
                <button type="button" id="btn-remover-foto" class="btn-secundario" onclick="removeImage();" style="display: none; margin-top: 10px;">
                ❌ Remover Foto
                </button>
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
            <div class="grupo-radio">
                <label>Aceita Cartas? <input type="radio" name="aceitaCarta" value="1" checked> Sim</label>
                <label><input type="radio" name="aceitaCarta" value="0"> Não</label>
            </div>

            <div class="grupo-input">
                <label>Sobre ele</label>
                <textarea name="sobre" rows="3"></textarea>
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


// Função para fazer a pré-visualização instantânea
function previewImage(input) {
    const preview = document.getElementById('preview-foto');
    const btnRemover = document.getElementById('btn-remover-foto');
    const inputFoto = document.getElementById('fotoPerfil');

    // Verifica se um arquivo foi selecionado
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        // Quando a leitura for concluída, atualiza a imagem
        reader.onload = function (e) {
            preview.src = e.target.result; // O result é uma URL base64 da imagem
            btnRemover.style.display = 'inline-block'; // Mostra o botão de remover
        }

        // Lê o arquivo selecionado
        reader.readAsDataURL(input.files[0]);
    } else {
        // Se cancelou a seleção, volta ao placeholder
        preview.src = "<?php echo BASE_URL; ?>/assets/img/perfil_placeholder.png";
        btnRemover.style.display = 'none';
        inputFoto.value = ''; // Limpa o input
    }
}

// Função para remover a foto e voltar ao padrão
function removeImage() {
    const preview = document.getElementById('preview-foto');
    const inputFoto = document.getElementById('fotoPerfil');
    const btnRemover = document.getElementById('btn-remover-foto');

    preview.src = "<?php echo BASE_URL; ?>/assets/img/perfil_placeholder.png"; // Placeholder padrão
    inputFoto.value = ''; // Limpa o arquivo selecionado no input
    btnRemover.style.display = 'none'; // Esconde o botão de remover
}

</script>