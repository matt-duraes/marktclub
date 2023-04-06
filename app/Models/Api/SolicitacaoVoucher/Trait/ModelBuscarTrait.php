<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\SolicitacaoVoucher\Ordem;

trait ModelBuscarTrait
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ModelWhereTrait;

    private function buscarVoucher()
    {
        $query = $this
        ->campo(['cod', 'tipo', 'data_criacao', 'data_vencimento', 'status'])
        ->pagina($this->pegarPagina(), $this->pegarQuantidade())
        ->where($this->pegarWhere(), obrigatorio: false)
        ->order($this->pegarOrdem(new Ordem()))
        ->tabela(TABELA_PARCEIRO_LOJA)->join('cod', 'vinculo')->campo(['titulo']);

        if ($this->idEmpresa == 1) {
            $query
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'empresa')
            ->campo(['nome_fantasia', 'cod'], 'empresa');
        }

        return $query->read();
    }
}
