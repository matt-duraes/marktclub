<?php

namespace Painel\DocumentoNoticia\Models;

use Helpers\DataHelper;
use App\Models\Painel\AppGeral\AppGeralModel;

final class DocumentoNoticiaModel extends AppGeralModel
{
    protected string $ormTabela = TABELA_DOCUMENTO_NOTICIA;

    /*
    |--------------------------------------------------------------------------
    | RETORNO DO WHERE
    |--------------------------------------------------------------------------
    */
    protected function montarRetorno(array $lista): array
    {
        if (!$lista) {
            return [];
        }
        $Data = new DataHelper();

        $dado = [];
        foreach ($lista as $r) {
            $dado[] = (object)[
                'uuid' => $r->uuid,
                'titulo' => $r->titulo,
                'data' => (object)[
                    'ano' => $Data->valor($r->data_publicacao)->formato('Y'),
                    'mes' => $Data->valor($r->data_publicacao)->nomeMes(),
                ],
                'status' => painelStatus(
                    valor: $r->status,
                    dado: [
                        1 => ['Liberado', 'verde'],
                        '' => ['Inativo', 'vermelho']
                    ]
                )
            ];
        }
        return $dado;
    }

    /*
    |--------------------------------------------------------------------------
    | MONTAR WHERE
    |--------------------------------------------------------------------------
    */
    protected function montarWhereDaPesquisa(string $pesquisa): array
    {
        return ['titulo', 'like', '%' . $pesquisa . '%'];
    }

    protected function montarWhereDoFiltro(array $where): array
    {
        if (!is_array($where)) {
            return [];
        }
        $retorno = [];
        $status = $where['status'] ?? '';
        if ($status == 1) {
            $retorno[] = ['status', 1];
        } elseif ($status == 2) {
            $retorno[] = ['status', 'null'];
        }
        $tag = $where['tag'] ?? '';
        if (!empty($tag) && is_array($tag)) {
            $item = [];
            foreach ($tag as $val) {
                $item[] = ['tag', 'like', '%"' . $val . '"%'];
            }
            $retorno[] = $item;
        }
        $titulo = $where['titulo'] ?? '';
        if (!empty($titulo)) {
            $retorno[] = ['titulo', 'like', '%' . $titulo . '%'];
        }
        return $retorno;
    }
}
