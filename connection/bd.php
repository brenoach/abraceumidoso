<?php

$host = 'localhost';
$db   = 'u142555398_abraceumidoso';
$user = 'u142555398_abraceumidoso';
$pass = 'Abraceumidoso123';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>