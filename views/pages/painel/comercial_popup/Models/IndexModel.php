<?php

namespace Painel\ComercialPopup\Models;

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
                'id'                      => $item->id,
                'titulo_painel'           => $item->titulo_painel ? $item->titulo_painel : $item->titulo,
                'data_inicio'             => $item->data_inicio,
                'data_final'              => $item->data_final,
                'publicado'               => $item->publicado,
                'status'                  => $item->status,

            ];
        }

        return $retorno;
    }
}
