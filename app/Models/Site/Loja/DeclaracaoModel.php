<?php

namespace App\Models\Site\Loja;

use App\Helpers\ClubeApiHelper;

final class DeclaracaoModel extends ClubeApiHelper
{
    private array $body = [];

    public function __construct(
        private string $parceiro,
        private ?string $modelo = null,
        private ?string $versao = null,
    ) {
        parent::__construct();
    }

    public function salvar()
    {
        $this
            ->validar('Erro ao solicitar sua declaração, por favor, tente novamente.')
            ->body([
                'parceiro' => $this->parceiro,
                'modelo'   => $this->modelo,
                'versao'   => $this->versao,
            ])
            ->post('/solicitacao-declaracao');
    }
}
