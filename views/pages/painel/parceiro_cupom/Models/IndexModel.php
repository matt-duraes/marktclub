<?php

namespace Painel\ParceiroCupom\Models;

use System\Interface\PainelIndexRetornoInterface;
use stdClass;

final class IndexModel implements PainelIndexRetornoInterface
{
    public function retorno(stdClass $dado): stdClass
    {
        $dado->dado->lista = $this->montarRetorno($dado->dado->lista);
        return $dado;
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];

        foreach ($dado as $item) {
            $retorno[] = [
                'id'               => $item->id,
                'titulo'           => $item->titulo,
                'status'           => $item->status,
                'cupom_link'       => $item->cupom ? $item->cupom : $item->link,
                'data_validade'    => $item->data_validade,
            ];
        }

        return $retorno;
    }
}
