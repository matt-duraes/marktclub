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
        $this->token = $this->classe[$this->valor]->retornarToken();
    }
}
