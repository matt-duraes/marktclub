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
            $tipo = $Tipo->indice($r->tipo_loja);
            $desconto = $r->desconto;
            if ($tipo == TipoLoja::CASHBACK) {
                $desconto = !empty($r->comissao_minima) ? number_format($r->comissao_minima, 2, '.') : '';
            }
            $retorno[$r->id] = [
                'id'              => $r->uuid,
                'titulo'          => $r->titulo,
                'titulo_interno'  => $r->titulo_interno,
                'desconto'        => $desconto,
                'imagem_logo'     => arquivoPrivado($r->imagem_logo),
                'url'             => $r->url,
                'tipo_loja'       => $tipo,
                'data_criacao'    => $r->data_criacao,
                'data_publicacao' => $r->data_publicacao,
                'data_prospeccao' => $r->data_prospeccao,
                'data_auditoria'  => $r->data_auditoria,
                'data_problema'  => $r->data_problema,
                'endereco_estado' => $r->endereco_estado,
                'status'          => $Status->indice($r->status)
            ];
        }
        if (object_key_exists('latitude', $lista[0])) {
            $retorno = $this->montarListaGeolocalizacao($retorno, $lista);
        }
        return array_values($retorno);
    }
}
