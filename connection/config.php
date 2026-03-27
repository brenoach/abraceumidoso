<?php
// 1. Definição do ROOT_PATH (Mantendo sua lógica que está certa)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}

// 2. Detecta o protocolo de forma robusta (Importante para o InfinityFree)
$protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";

// 3. BASE_URL: O segredo é NÃO colocar a barra no final aqui
if (!defined('BASE_URL')) {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define('BASE_URL', 'http://localhost/abraceumidoso'); // Sem barra no fim
    } else {
        // Removi a barra extra que estava causando o // no seu link
        define('BASE_URL', $protocolo . $_SERVER['HTTP_HOST']); 
    }
}

// 4. Configuração do Banco de Dados
$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1');

if ($isLocal) {
    // Variáveis locais (facilita para o seu db.php ler)
    $db_config = [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db'   => 'if0_41247895_bd_abraceumidoso',
        'charset' => 'utf8mb4',
    ];
} else {
    // Configuração para o InfinityFree
    $db_config = [
        'host' => 'sql100.infinityfree.com',
        'user' => 'if0_41247895',
        'pass' => 'WNnKXcjoHjsDHjN',
        'db'   => 'if0_41247895_bd_abraceumidoso',
        'charset' => 'utf8mb4',
    ];
}

// Em vez de dar return direto, vamos garantir que o arquivo de conexão consiga ler
return $db_config;