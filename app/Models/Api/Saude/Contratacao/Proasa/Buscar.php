<?php

namespace App\Models\Api\Saude\Contratacao\Proasa;

use Modules\Email;
use Modules\Telefone;
use App\Models\Api\Saude\Contratacao\Proasa\ApiAbstract;

final class Buscar extends ApiAbstract
{
    public bool $existe = false;

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
        $this->existe = $this->validarExiste($busca);
    }

    private function validarExiste(array $busca): bool
    {
        if(!validarIndiceExiste($busca, 'total') || $busca['total'] === 0) {
            return false;
        }
        $data = $busca['contacts'][0]['created_at'] ?? '';
        $hoje = date('Y-m-d');
        return str_starts_with($data, $hoje);
    }
}
