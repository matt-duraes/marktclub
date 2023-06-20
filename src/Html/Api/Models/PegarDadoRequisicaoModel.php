<?php

final class PegarDadoRequisicaoModel
{
    private array $retorno = [];
    public function __construct(
        private string $id
    ) {
        $this->buscarRequisicao();
    }
    public function retorno()
    {
        if (empty($this->retorno)) {
            return $this->retornoPadrao();
        }
        return $this->retorno;
    }
    private function buscarRequisicao()
    {
        $path = ROOT . '/postman/' . $this->id . '.json';
        if (!file_exists($path)) {
            return;
        }
        $this->retorno = jsonDecode(file_get_contents($path), true, true);
    }
    private function retornoPadrao()
    {
        return [
            'uri' => '{{LINK}}/',
            'metodo' => 'GET',
            'token' => 'sem_token',
            'parametro' => [],
            'header' => [],
            'body' => [],
            'json' => '',
        ];
    }
}
