<?php

namespace App\Models\Api\Saude\Contratacao\Proasa;

use Modules\Email;
use app\Models\Api\Saude\Contratacao\Proasa\ApiAbstract;

final class Buscar extends ApiAbstract
{
    public function __construct(
        Email $Email,
    )
    {
        parent::__construct();
        $this->buscarUsuario($Email->email());
    }

    private function buscarUsuario(string $email)
    {
        $buscar = $this
            ->header($this->headerAccept())
            ->get($this->uri('/contacts'));
        ppe($buscar);
    }
}
