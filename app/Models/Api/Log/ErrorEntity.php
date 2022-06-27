<?php

namespace App\Models\Api\Log;

use ORM\Entity;

final class ErrorEntity extends Entity
{
    protected string $_tabela = TABELA_LOG_ERRO;

    protected array $_insert = ['hash', 'mensagem', 'codigo', 'status_http', 'arquivo', 'linha', 'trace', 'status'];
    protected array $_buscar = ['hash'];

    public string $hash;

    public function __construct(
        private ?string $mensagem = null,
        private ?string $codigo = null,
        private ?string $status_http = null,
        private ?string $arquivo = null,
        private ?string $linha = null,
        private ?string $trace = null,
    ) {
        parent::__construct();
    }

    protected function regraInsert()
    {
        $hash = md5($this->mensagem . $this->arquivo . $this->linha);
        if ($this->existe([
            ['hash', $hash],
            ['status', 1]
        ])) {
            $this->hash = $hash;
            mensagemErro('erro_duplicado', 'erro_duplicado');
        }
        $this->hash = $hash;
        $this->status = 1;
    }
}
