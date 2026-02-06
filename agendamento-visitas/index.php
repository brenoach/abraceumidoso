<?php
require_once 'includes/db.php';

$mensagem = "";

// Processar o formulário quando enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO agendamento (idoso_idIdoso, voluntario_idVoluntario, dtAgendamento, voluntariohrAgendamento ) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        $stmt->execute([
            $_POST['idIdoso'],
            $_POST['idVoluntario'],
            $_POST['data'],
            $_POST['horario']
        ]);

        $mensagem = "<div class='sucesso'>Visita agendada com sucesso!</div>";
    } catch (PDOException $e) {
        $mensagem = "<div class='erro'>Erro ao agendar: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Visitas</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <header class="cabecalho">
        <a href="#" class="logo">
            <img src="assets/img/logo.jpg" alt="Logo Lar dos Idosos"> 
        </a>

        <nav class="nav-menu">
            <ul>
                <li><a href="#">Cadastrar</a></li>
                <li><a href="#">Login</a></li>
            </ul>
            <ul>
                <li><a href="#">Início</a></li>
                <li><a href="#">Agendamento</a></li>
                <li><a href="#">Cartas</a></li>
                <li><a href="#">Fale Conosco</a></li>
            </ul>
        </nav>
    </header>

    <main class="container-principal">
        <div class="card-formulario">
            <h2>Agendar Visita</h2>
            <p>Escolha o voluntário e o idoso para marcar o encontro.</p>
            
            <?php echo $mensagem; ?>

            <form method="POST" action="">
                <div class="grupo-input">
                    <label for="idIdoso">Idoso(a):</label>
                    <select name="idIdoso" id="idIdoso" required>
                        <option value="" disabled selected>Selecione o residente...</option>
                        <option value="1">Sra. Maria (ID: 1)</option>
                        <option value="2">Sr. João (ID: 2)</option>
                    </select>
                </div>

                <div class="grupo-input">
                    <label for="idVoluntario">Voluntário(a):</label>
                    <select name="idVoluntario" id="idVoluntario" required>
                        <option value="" disabled selected>Selecione o voluntário...</option>
                        <option value="10">Carlos (ID: 10)</option>
                        <option value="11">Aline (ID: 11)</option>
                    </select>
                </div>

                <div class="linha-dupla">
                    <div class="grupo-input">
                        <label for="data">Data da Visita:</label>
                        <input type="date" name="data" id="data" required>
                    </div>

                    <div class="grupo-input">
                        <label for="horario">Horário:</label>
                        <input type="time" name="horario" id="horario" required>
                    </div>
                </div>

                <div class="banner">
                    <button type="submit" class="btn-marrom">Confirmar Agendamento</button>
                </div>
            </form>
        </div>
    </main>

    <footer class="rodape">
        <p>&copy; 2025 Lar dos Idosos. Todos os direitos reservados.</p>
    </footer>
    <script src="assets/js/script.js"></script>
</body>
</html>