<?php

namespace App\Models\Api\ParceiroLoja\Trait;

use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;

trait MontarRetornoTrait
{
    private function montarRetorno($lista): array
    {
        if (!$lista) {
            return [];
        }

        $retorno = [];
        $Status = new Status();
        $Tipo = new TipoLoja();

        foreach ($lista as $r) {
            $retorno[$r->id] = [
                'id'              => $r->uuid,
                'titulo'          => $r->titulo,
                'desconto'        => $r->texto_desconto,
                'imagem'          => arquivoPrivado($r->imagem_logo),
                'url'             => $r->url,
                'tipo'            => $Tipo->indice($r->tipo_loja),
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
