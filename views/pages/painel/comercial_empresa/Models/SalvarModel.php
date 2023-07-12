<?php

namespace Painel\ComercialEmpresa\Models;

use stdClass;
use Helpers\ApiHelper;
use App\Classes\ComercialEmpresa\Status;
use System\Interface\PainelSalvarBuscarInterface;

final class SalvarModel implements PainelSalvarBuscarInterface
{
    public function buscar(string $uuid): stdClass
    {
        $dado = (new ApiHelper(token: true))
            ->validar(status: 404)
            ->get('/comercial-empresa/' . $uuid)
            ->object();
        $dado->dado = $this->montarDado($dado->dado);
        return $dado;
    }

    private function montarDado($dado): stdClass
    {
        if ($dado->status == Status::PROSPECCAO) {
            $dado->status = Status::ATIVO;
        }
        return $dado;
    }
}
