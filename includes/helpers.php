<?php
// includes/helpers.php

/**
 * Função para exibir a foto do idoso com fallback automático
 * Se não houver foto no banco, gera um avatar com as iniciais.
 */
function exibirFoto($caminho, $nome) {
    $corDeFundo = '6a1b9a'; 
    $corDoTexto = 'ffffff';

    // 1. Caso o campo esteja vazio no banco
    if (empty($caminho)) {
        return '<img src="https://ui-avatars.com/api/?name=' . urlencode($nome) . '&background=' . $corDeFundo . '&color=' . $corDoTexto . '&size=128" alt="Iniciais" class="foto-perfil-idoso">';
    }

    // 2. Verifica se é um link da INTERNET (começa com http ou https)
    if (str_starts_with($caminho, 'http')) {
        $urlFinal = $caminho; // Usa o link direto da internet
    } else {
        // 3. Caso seja um arquivo LOCAL (dentro da sua pasta assets)
        // Tiramos a barra da esquerda para não duplicar
        $caminhoLimpo = ltrim($caminho, '/');
        $urlFinal = "../" . $caminhoLimpo;

        // Se o arquivo local não existir fisicamente, volta pro avatar
        if (!file_exists(__DIR__ . "/../" . $caminhoLimpo)) {
            $urlFinal = "https://ui-avatars.com/api/?name=" . urlencode($nome) . "&background=" . $corDeFundo . "&color=" . $corDoTexto . "&size=128";
        }
    }

    return '<img src="' . $urlFinal . '" alt="' . htmlspecialchars($nome) . '" class="foto-perfil-idoso">';
}

function validarSessao($tipo_exigido = null) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Usando a sua variável correta: idPessoa
    if (!isset($_SESSION['idPessoa'])) {
        header("Location: " . BASE_URL . "/login");
        exit;
    }

    if ($tipo_exigido !== null) {
        if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== $tipo_exigido) {
            header("Location: " . BASE_URL . "/acesso-negado"); 
            exit;
        }
    }
}