<?php

$host = 'sql100.infinityfree.com';
$db   = 'if0_41247895_bd_abraceumidoso';
$user = 'if0_41247895';
$pass = 'WNnKXcjoHjsDHjN';


try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>