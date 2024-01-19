<?php

namespace Painel\TextoClube\Models;

use stdClass;
use System\Interface\PainelIndexRetornoInterface;

final class IndexModel implements PainelIndexRetornoInterface
{
    public function retorno(stdClass $dado): stdClass
    {
        $dado->dado->lista = $this->montarRetorno($dado->dado->lista);
        return $dado;
    }

    private function montarRetorno($dado)
    {
        $retorno = [];

        foreach ($dado as $item) {
            $retorno[] = [
                'id'              => $item->id,
                'titulo_painel'   => $item->titulo_painel ? $item->titulo_painel : $item->titulo,
                'status'          => $item->status,
                'tipo'            => $item->tipo,
                'data_criacao'    => $item->data_criacao
            ];
        }

        return $retorno;
    }
}
