<?php
// 1. NÚCLEO DO SISTEMA
require_once __DIR__ . '/../connection/config.php';
require_once __DIR__ . '/../includes/auth.php';
verificarAcesso('voluntario'); // Segurança em primeiro lugar

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php'; // Para usar sua função exibirFoto()
require_once __DIR__ . '/../includes/header.php';

// 2. VALIDAÇÃO DE ENTRADA
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: " . BASE_URL . "/pages/painel_voluntario.php");
    exit;
}

$idIdoso = $_GET['id'];

try {
    // 3. A QUERY MARAVILHA (PARTE 1): Dados do Idoso + Trava de Segurança
    // Só permite carregar a página se o idoso existir E aceitar visitas
    $sqlIdoso = "SELECT p.nomePessoa, p.fotoPerfil, i.idIdoso 
                 FROM idoso i 
                 JOIN pessoa p ON i.idPessoa = p.idPessoa 
                 WHERE i.idIdoso = ? AND i.aceitaVisita = 1";
    
    $stmtIdoso = $pdo->prepare($sqlIdoso);
    $stmtIdoso->execute([$idIdoso]);
    $idoso = $stmtIdoso->fetch(PDO::FETCH_ASSOC);

    if (!$idoso) {
        echo "<script>alert('Atenção: Este residente não está disponível para novas visitas no momento.'); window.location.href='painel_voluntario.php';</script>";
        exit;
    }

    // 4. A QUERY MARAVILHA (PARTE 2): Busca a agenda definida pelo Funcionário
    $sqlDispo = "SELECT dia_Semana, hora_Inicio, hora_Fim 
                 FROM disponibilidade 
                 WHERE idoso_idIdoso = ? 
                 ORDER BY FIELD(dia_Semana, 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'), hora_Inicio";
    
    $stmtDispo = $pdo->prepare($sqlDispo);
    $stmtDispo->execute([$idIdoso]);
    $horariosPermitidos = $stmtDispo->fetchAll(PDO::FETCH_ASSOC);


} catch (PDOException $e) {
    // Isso vai mostrar a mensagem real do erro (ex: tabela não encontrada)
    die("Erro real: " . $e->getMessage()); 
}
?>

<style>
    /* Estilização Premium */
    .agendamento-container {
        max-width: 600px;
        margin: 40px auto;
        background: #fff;
        border-radius: 30px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.1);
        overflow: hidden;
        border: 1px solid #f0f0f0;
    }

    .banner-idoso {
        background: linear-gradient(135deg, #673AB7 0%, #4527a0 100%);
        padding: 40px 20px;
        text-align: center;
        color: white;
    }

    .foto-moldura {
        width: 120px;
        height: 120px;
        margin: 0 auto 15px;
        border: 5px solid rgba(255,255,255,0.2);
        border-radius: 50%;
        overflow: hidden;
    }

    /* Sua classe da função helpers.php */
    .foto-perfil-idoso { width: 100%; height: 100%; object-fit: cover; }

    .corpo-agendamento { padding: 30px; }

    .quadro-horarios {
        background: #f4f0ff;
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 30px;
        border: 1px dashed #673AB7;
    }

    .quadro-horarios h4 { color: #673AB7; margin-top: 0; display: flex; align-items: center; gap: 8px; }

    .lista-permitida { list-style: none; padding: 0; margin: 0; font-size: 0.9rem; color: #444; }
    .lista-permitida li { margin-bottom: 8px; display: flex; justify-content: space-between; border-bottom: 1px solid rgba(103, 58, 183, 0.1); padding-bottom: 4px; }

    .form-grupo { margin-bottom: 25px; }
    .form-grupo label { display: block; font-weight: bold; color: #5b3a26; margin-bottom: 10px; }

    input[type="date"], input[type="time"] {
        width: 100%;
        padding: 15px;
        border: 2px solid #eee;
        border-radius: 15px;
        font-size: 1rem;
        transition: 0.3s;
        outline: none;
    }

    input:focus { border-color: #673AB7; background: #fdfbff; }

    .btn-agendar-final {
        background: #673AB7;
        color: white;
        border: none;
        width: 100%;
        padding: 18px;
        border-radius: 15px;
        font-size: 1.2rem;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
        box-shadow: 0 10px 20px rgba(103, 58, 183, 0.2);
    }

    .btn-agendar-final:hover { background: #512da8; transform: translateY(-3px); box-shadow: 0 15px 25px rgba(103, 58, 183, 0.3); }

    .link-voltar { display: block; text-align: center; margin-top: 20px; color: #999; text-decoration: none; font-size: 0.9rem; }
</style>

<div class="agendamento-container">
    <div class="banner-idoso">
        <div class="foto-moldura">
            <?= exibirFoto($idoso['fotoPerfil'], $idoso['nomePessoa']) ?>
        </div>
        <h2 style="margin:0;"><?= htmlspecialchars($idoso['nomePessoa']) ?></h2>
        <p style="margin:5px 0 0; opacity: 0.8;">Escolha um horário para sua visita</p>
    </div>

    <div class="corpo-agendamento">
        <div class="quadro-horarios">
            <h4><i class="fas fa-clock"></i> Horários Disponíveis:</h4>
            <?php if (empty($horariosPermitidos)): ?>
                <p style="font-size: 0.85rem; color: #666;">Não há horários específicos cadastrados. Verifique com a instituição.</p>
            <?php else: ?>
                <ul class="lista-permitida">
                    <?php foreach ($horariosPermitidos as $h): ?>
                        <li>
                            <span><strong><?= $h['diaSemana'] ?></strong></span>
                            <span><?= date('H:i', strtotime($h['horaInicio'])) ?> - <?= date('H:i', strtotime($h['horaFim'])) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <form action="<?= BASE_URL ?>/actions/salvar_agendamento.php" method="POST">
            <input type="hidden" name="idIdoso" value="<?= $idIdoso ?>">

            <div class="form-grupo">
                <label>📅 Data da Visita:</label>
                <input type="date" name="data_visita" min="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-grupo">
                <label>⏰ Horário da Chegada:</label>
                <input type="time" name="hora_visita" required>
            </div>

            <button type="submit" class="btn-agendar-final">CONFIRMAR AGENDAMENTO 💜</button>
            
            <a href="painel_voluntario.php" class="link-voltar">Cancelar e voltar ao painel</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>