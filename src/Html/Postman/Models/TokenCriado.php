<?php

namespace System\Html\Postman\Models;

use System\Html\Postman\Models\Trait\TokenConstrutorTrait;

final class TokenCriado
{
    use TokenConstrutorTrait;

    private array $arquivo = [];
    private array $classe = [];
    public string $token = '';

    public function __construct(
        private string $valor
    ) {
        $this->buscarPathArquivo();
        if (empty($this->arquivo)) {
            return;
        }
        $this->setarClasse();
        if (!array_key_exists($valor, $this->classe)) {
            return;
        }
        $this->pegarToken();
    }

    private function pegarToken()
    {
        $retorno = $this->classe[$this->valor]->retornarToken();
        if (is_array($retorno) && array_key_exists('erro', $retorno) && array_key_exists('mensagem', $retorno)) {
            mensagemErro('Erro!', $retorno['mensagem']);
        }
        $this->token = is_string($retorno) ? $retorno : '';
    }
}
