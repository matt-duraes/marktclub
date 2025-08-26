<?php

namespace App\Models\Api\Saude\Contratacao\Proasa\Negociacao;

use App\Models\Api\Saude\Contratacao\Proasa\ApiAbstract;
use App\Models\Api\Saude\Contratacao\Proasa\Contato\Salvar as Contato;

final class Buscar extends ApiAbstract
{
    public bool $existe = false;
    public string $id = '';
    public function __construct(
        Contato $Contato
    )
    {
        parent::__construct();
        $this->buscarNegociacao($Contato->id);
    }

    private function buscarNegociacao(string $contatoId)
    {
        $busca = $this
            ->header($this->headerAccept())
            ->parametro([
                'closed_at' => 'false',
                'user_id' => $contatoId
            ])
            ->get($this->uri('/deals'))
            ->array();
        $this->validarBuscaExiste($busca, 'deals');
    }
}
