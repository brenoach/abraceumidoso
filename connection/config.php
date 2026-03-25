<?php


// 1. Definição do ROOT_PATH (Protegido para não repetir)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}

// 1. Detecta se é HTTP ou HTTPS automaticamente
$protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";

// 2. Define o BASE_URL sem a barra no final para evitar o erro de "//"
if (!defined('BASE_URL')) {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        // No seu PC
        define('BASE_URL', 'http://localhost/abraceumidoso'); 
    } else {
        // Na internet (InfinityFree)
        // O $_SERVER['HTTP_HOST'] já pega 'abraceumidoso.infinityfreeapp.com'
        define('BASE_URL', $protocolo . $_SERVER['HTTP_HOST']);
    }
}

// 3. Configuração do Banco de Dados
// Verifica se o site está rodando no seu computador (XAMPP)
$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1');

if ($isLocal) {
    // CONFIGURAÇÃO PARA XAMPP (LOCAL)
    return [
        'host' => 'localhost',         // No XAMPP o host é sempre localhost
        'user' => 'root',              // Usuário padrão do XAMPP
        'pass' => '',                  // Senha padrão do XAMPP é vazia
        'db'   => 'if0_41247895_bd_abraceumidoso', // NOME DO SEU BANCO NO PHPMYADMIN LOCAL
        'charset' => 'utf8mb4',
    ];
} else {
    // CONFIGURAÇÃO PARA INFINITYFREE (ONLINE)
    return [
        'host' => 'sql100.infinityfree.com',
        'user' => 'if0_41247895',
        'pass' => 'WNnKXcjoHjsDHjN',
        'db'   => 'if0_41247895_bd_abraceumidoso',
        'charset' => 'utf8mb4',
    ];
}
