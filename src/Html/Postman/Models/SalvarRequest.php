<?php

final class SalvarRequest
{
    public function __construct(
        string $id,
        string $token,
        string $metodo,
        string $uri,
        array $parametro,
        array $body,
        private array $header,
        array $json
    ) {
        $dado = [
            "uri" => $uri,
            "metodo" => $metodo,
            "token" => $token,
            "parametro" => $parametro,
            "header" => $header,
            "body" => $body,
            "json" => $json
        ];
        $this->criarArquivo($id, $dado);
    }

    private function criarArquivo($id, $dado)
    {
        if (!criarArquivo(ROOT . '/postman/' . $id . '.json', jsonEncode($dado))) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar a requisição.');
        }
    }

    public function retorno()
    {
        return jsonEncode(['status' => 'sucesso']);
    }
}
