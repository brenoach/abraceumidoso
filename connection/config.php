<?php

// 1. Definição do ROOT_PATH (Protegido para não repetir)
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__) . '/');
}

// 2. Definição do BASE_URL (AQUI ESTÁ O IF DENTRO DO IF!)
if (!defined('BASE_URL')) {
    // Se a constante não existe, o PHP entra aqui e faz a segunda pergunta:
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
        define('BASE_URL', 'http://localhost/abraceumidoso/');
    } else {
        define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/');
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