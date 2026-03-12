<?php
$config = require __DIR__ . '/config.php';
 
$mysqli = new mysqli($config['host'], $config['user'], $config['pass'], $config['db']);
if ($mysqli->connect_error) {
  die('Erro de conexão: ' . $mysqli->connect_error);
}
$mysqli->set_charset($config['charset'] ?? 'utf8mb4');
 
function db(): mysqli {
  global $mysqli;
  return $mysqli;
}