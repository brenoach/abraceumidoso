<?php

require '../actions/cep_service.php';

header('Content-Type: application/json');

$cep = $_GET['cep'] ?? null;

if (!$cep) {
    echo json_encode(['erro' => true]);
    exit;
}

$cepService = new CepService();
$resultado = $cepService->buscarCep($cep);

if (!$resultado) {
    echo json_encode(['erro' => true]);
    exit;
}

echo json_encode($resultado);
