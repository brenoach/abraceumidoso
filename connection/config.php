<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}

if ($_SERVER['HTTP_HOST'] == 'localhost') {
    define('BASE_URL', 'http://localhost/abraceumidoso/');
} else {
    define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
}
// Verifica se o site está rodando no seu computador (XAMPP)
$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1');

if ($isLocal) {
    // CONFIGURAÇÃO PARA XAMPP (LOCAL)
    return [
        'host' => 'localhost',         // No XAMPP o host é sempre localhost
        'user' => 'root',              // Usuário padrão do XAMPP
        'pass' => '',                  // Senha padrão do XAMPP é vazia
        'db'   => 'if0_41247895_bd_abraceumidoso', // COLOQUE O NOME DO SEU BANCO NO PHPMYADMIN LOCAL
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
    // 1. Descobre se está no computador (XAMPP) ou no servidor
// O __DIR__ aqui é 'C:/xampp/htdocs/abraceumidoso/connection'
// Usamos dirname(__DIR__) para subir um nível e chegar na raiz 'abraceumidoso'
