<?php
session_start();
require_once __DIR__ . '/../connection/config.php';
require_once ROOT_PATH . '/connection/bd.php'; // Sua conexão PDO



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pdo->beginTransaction();

        // 1. Upload da Foto
        $fotoCaminho = 'assets/img/uploads/default.png';

        if (isset($_FILES['fotoPerfil']) && $_FILES['fotoPerfil']['error'] === 0) {
            $ext = pathinfo($_FILES['fotoPerfil']['name'], PATHINFO_EXTENSION);
            $nomeArquivo = uniqid() . "." . $ext;
            $destino = ROOT_PATH . 'assets/img/uploads/' . $nomeArquivo;
            if (move_uploaded_file($_FILES['fotoPerfil']['tmp_name'], $destino)) {
                $fotoCaminho = 'assets/img/uploads/' . $nomeArquivo;
                // echo "este é o nome do arquivo {$nomeArquivo}";
            }
        }

    
            $erroUpload = $_FILES['fotoPerfil']['error'];

            switch ($erroUpload) {
                case 0:
                    // Lógica de sucesso (mover arquivo, salvar no banco)
                    break;
                case 1:
                case 2:
                    echo "Ops! Essa foto é muito grande. Tente uma imagem menor.";
                    break;
                case 4:
                    echo "Você esqueceu de selecionar a foto!";
                    break;
                default:
                    echo "Houve um erro inesperado no upload. Tente novamente.";
                    break;
            }
        // 2. Inserir na tabela PESSOA
        $stmt = $pdo->prepare("INSERT INTO pessoa (nomePessoa, cpf, dataNascimento, fotoPerfil, sobre) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['nome'], $_POST['cpf'], $_POST['dataNascimento'], $fotoCaminho, $_POST['sobre']]);
        $idPessoa = $pdo->lastInsertId();

        // 3. Inserir na tabela IDOSO (Puxando a instituição da sessão do funcionário)
        // $idInstituicao = $_SESSION['usuario_id_instituicao']; 
        $idDaMinhaInstituicao = $_SESSION['idInstituicao'];
        $stmt = $pdo->prepare("INSERT INTO idoso (idPessoa, idInstituicao, aceitaVisita, aceitaCarta) VALUES (?, ?, ?, ?)");
        $stmt->execute([$idPessoa, $idDaMinhaInstituicao, $_POST['aceitaVisita'], $_POST['aceitaCarta'] ?? 1]);
        $idIdoso = $pdo->lastInsertId();

        // 4. Inserir DISPONIBILIDADE (Loop nos dias marcados)
        if (isset($_POST['dias'])) {
            $stmtDisp = $pdo->prepare("INSERT INTO disponibilidade (idoso_idIdoso, dia_semana, hora_inicio, hora_fim) VALUES (?, ?, ?, ?)");
            foreach ($_POST['dias'] as $dia) {
                $inicio = $_POST['horario_inicio'][$dia];
                $fim = $_POST['horario_fim'][$dia];
                if (!empty($inicio) && !empty($fim)) {
                    $stmtDisp->execute([$idIdoso, $dia, $inicio, $fim]);
                }
            }
        }

        $pdo->commit();
        header("Location: ../pages/listar_idosos.php?sucesso=1");
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro ao salvar: " . $e->getMessage());
    }
}