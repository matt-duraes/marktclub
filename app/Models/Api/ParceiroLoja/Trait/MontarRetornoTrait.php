<?php

namespace App\Models\Api\ParceiroLoja\Trait;

use App\Classes\ParceiroLoja\Tipo;
use App\Classes\ParceiroLoja\Status;

trait MontarRetornoTrait
{
    private function montarRetorno($lista): array
    {
        if (!$lista) {
            return [];
        }

        $retorno = [];
        $Status = new Status();
        $Tipo = new Tipo();

        foreach ($lista as $r) {
            $retorno[$r->id] = [
                'id'              => $r->cod,
                'titulo'          => $r->titulo,
                'desconto'        => $r->desconto,
                'imagem'          => LINK_ARQUIVO . '/parceiro/' . $r->imagem,
                'url'             => $r->url,
                'tipo'            => $Tipo->indice($r->tipo),
                'data_publicacao' => $r->data_publicacao,
                'estado'          => $r->estado,
                'favorito'        => !empty($r->favorito) ? 'sim' : 'nao',
                'status'          => $Status->indice($r->status)
            ];
        }
        if (object_key_exists('latitude', $lista[0])) {
            $retorno = $this->montarListaGeolocalizacao($retorno, $lista);
        }
        return array_values($retorno);
    }
}
