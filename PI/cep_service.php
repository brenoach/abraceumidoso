<?php

class CepService
{
    private string $baseUrl = "https://viacep.com.br/ws/";

    public function buscarCep(string $cep): ?array
    {
        $cep = preg_replace('/\D/', '', $cep);

        if (strlen($cep) !== 8) {
            return null;
        }

        $url = $this->baseUrl . $cep . "/json/";

        $response = file_get_contents($url);

        if ($response === false) {
            return null;
        }

        $dados = json_decode($response, true);

        if (isset($dados['erro'])) {
            return null;
        }

        return $dados;
    }
}

