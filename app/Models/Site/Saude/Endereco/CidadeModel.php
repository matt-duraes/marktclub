<?php

namespace App\Models\Site\Saude\endereco;

use Modules\EnderecoEstado;
use App\Helpers\ClubeApiHelper;

final class CidadeModel extends ClubeApiHelper
{
    public array $retorno = [];
    public function __construct(
        EnderecoEstado $Estado
    )
    {
        parent::__construct();
        $this->buscarCidade($Estado->valor());
    }

    private function buscarCidade(string $uf)
    {
        $busca = $this
            ->parametro([
                'endereco_estado' => $uf
            ])
            ->get('/saude-convenio/cidade')
            ->array();
        if(!validarIndiceExiste($busca, 'dado') || empty($busca['dado'])) {
            return;
        }
        $this->retorno = $busca['dado'];
    }
}
