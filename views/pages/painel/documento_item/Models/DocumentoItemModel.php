<?php

namespace Painel\DocumentoItem\Models;

use App\Models\Painel\AppGeral\AppGeralModel;

final class DocumentoItemModel extends AppGeralModel
{
    protected string $ormTabela = TABELA_DOCUMENTO_ITEM;

    /**
     * Pega a lista de itens e retorna como um array id => titulo
     * @param Null|String $titulo         Título para o array
     */
    public function pegarSelect(?string $titulo = null)
    {
        $lista = [];

        if (!empty($titulo)) {
            $lista[] = $titulo;
        }

        $dado = $this->campo(['id', 'titulo'])->where([
            ['status', 1]
        ])->order('titulo', 'ASC')->read();

        if (!$dado) {
            return $lista;
        }
        foreach ($dado as $r) {
            $lista[$r->id] = $r->titulo;
        }
        return $lista;
    }

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
        $dado = [];
        foreach ($lista as $r) {
            $dado[] = (object)[
                'uuid' => $r->uuid,
                'titulo' => $r->titulo,
                'data' => dataHoraBr(data: $r->data_criacao),
                'status' => painelStatus(
                    valor: $r->status,
                    dado: [
                        1 => ['Liberado', 'verde'],
                        2 => ['Inativo', 'vermelho']
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
        if (in_array($where['status'] ?? '', [1, 2, 3])) {
            $retorno[] = ['status', $where['status']];
        }
        if (!empty($where['titulo'] ?? '')) {
            $retorno[] = ['titulo', 'like', '%' . $where['titulo'] . '%'];
        }
        return $retorno;
    }
}
