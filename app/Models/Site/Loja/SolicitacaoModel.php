<?php

namespace App\Models\Site\Loja;

use App\Helpers\ClubeApiHelper;
use App\Classes\SolicitacaoLoja\Origem;

final class SolicitacaoModel extends ClubeApiHelper
{
    public function __construct(
        private string $nome,
        private string $telefone,
        private string $email,
        private string $mensagem
    ) {
        parent::__construct();
        $this->salvar();
    }

    private function salvar(): void
    {
        $this
            ->validar('Ocorre um erro ao indicar a loja, por favor, tente novamente.', login: true)
            ->body([
                'nome'     => $this->nome,
                'telefone' => soNumero($this->telefone),
                'email'    => $this->email,
                'mensagem' => $this->mensagem,
                'usuario'  => $this->idUsuario,
            ])->post('/solicitacao-loja');
    }
}
