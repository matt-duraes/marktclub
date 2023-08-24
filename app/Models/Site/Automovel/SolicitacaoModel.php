<?php

namespace App\Models\Site\Automovel;

use App\Helpers\ClubeApiHelper;

final class SolicitacaoModel extends ClubeApiHelper
{
    public function __construct(
        private string $enderecoEstado,
        private string $enderecoCidade,
        private string $montadora,
        private string $modelo,
        private string $versao,
        private string $cor,
        private string $mensagem,
    ) {
        parent::__construct();
    }

    public function salvar()
    {
        $dado = $this
            ->validar('Ocorreu um erro na sua solicitação, por favor, tente novamente.', login: true)
            ->body([
                'endereco_estado' => $this->enderecoEstado,
                'endereco_cidade' => $this->enderecoCidade,
                'montadora'       => $this->montadora,
                'modelo'          => $this->modelo,
                'versao'          => $this->versao,
                'cor'             => $this->cor,
                'mensagem'        => $this->mensagem,
            ])
            ->post('/solicitacao-automovel');
    }
}
