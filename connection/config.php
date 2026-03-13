<?php
 
$isLocal = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1');
 
if ($isLocal) {
    // CONFIGURAÇÃO PARA XAMPP (LOCAL)
    return [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db'   => 'abraceumidoso', // phpMyAdmin
        'charset' => 'utf8mb4',
    ];
} else {
    // CONFIGURAÇÃO PARA INFINITYFREE (ONLINE)
    return [
        'host' => 'sql211.infinityfree.com',
        'user' => 'if0_41248576',
        'pass' => '305079Mo',
        'db'   => 'if0_41248576_bd_abraceumidoso',
        'charset' => 'utf8mb4',
    ];
}