<?php


// 1. Definição do ROOT_PATH (Protegido para não repetir)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}

// Detecta se o site está usando HTTPS ou HTTP
$protocolo = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https://" : "http://";

if (!defined('BASE_URL')) {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define('BASE_URL', 'http://localhost/abraceumidoso/');
    } else {
        // Na internet, ele usa o protocolo detectado automaticamente
        define('BASE_URL', $protocolo . $_SERVER['HTTP_HOST'] . '/');
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