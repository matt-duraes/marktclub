<?php

namespace App\Models\Api\Saude\Contratacao\Proasa\Contato;

use Modules\Telefone;
use App\Models\Api\Saude\Contratacao\Proasa\ApiAbstract;

final class Buscar extends ApiAbstract
{
    public bool $existe = false;
    public string $id = '';

    public function __construct(
        Telefone $Telefone,
    )
    {
        parent::__construct();
        $this->buscarUsuario($Telefone->numero());
    }

    private function buscarUsuario(string $telefone)
    {
        $busca = $this
            ->header($this->headerAccept())
            ->parametro([
                'phone' => $telefone
            ])
            ->get($this->uri('/contacts'))
            ->array();
        $this->validarBuscaExiste($busca, 'contacts');
    }
}
