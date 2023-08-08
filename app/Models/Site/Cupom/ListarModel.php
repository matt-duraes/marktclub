<?php

namespace App\Models\Site\Cupom;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;

final class ListarModel extends ClubeApiHelper implements ListarInterface
{
    public function listarDados(string $pesquisa = null): stdClass
    {
        $dado = $this->json([
            'pesquisa' => $pesquisa ?? '',
        ])->get('/parceiro-cupom')->object();
        return (object)[
            'tipo'  => 'cupom',
            'lista' => $this->montarRetorno($dado->dado ?? [])
        ];
    }

    private function montarRetorno($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'         => $r->id,
                'titulo'     => $r->parceiro->nome,
                'imagem'     => $r->parceiro->imagem,
                'texto'      => $r->descricao,
                'validade'   => dataHoraBr($r->validade),
                'tipo'       => 'cupom'
            ];
        }
        return $retorno;
    }
}
