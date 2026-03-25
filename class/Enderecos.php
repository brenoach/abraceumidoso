<?php
require_once "../conexao/conexao.php";
class Enderecos {
    public string $cep, $estado, $cidade, $bairro, $numero,$nomeLogradouro, $tipoLogradouro;

    public function __construct($cep, $estado, $cidade, $bairro, $numero, $nomeLogradouro,$tipoLogradouro) {
        $this->cep = $cep;
        $this->estado = $estado;
        $this->cidade = $cidade;
        $this->bairro = $bairro;
        $this->nomeLogradouro = $nomeLogradouro;
        $this->numero = $numero;
        //$this->tipoLogradouro = $tipoLogradouro;
    }
    public function __get($valor){
        return $this->$valor;
    }
    public function __set($valor, $campo){
        return  $this->$valor = $campo;
    }
}
?>
