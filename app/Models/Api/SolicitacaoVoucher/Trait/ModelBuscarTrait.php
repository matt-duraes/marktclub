<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use Erro\Excecao;
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

    /**
     * @return mixed
     * @throws Excecao
     */
    private function buscarVoucher(): mixed
    {
        return $this
            ->campo([
                'cod', 'titulo', 'tipo', 'tipo_usuario', 'data_criacao',
                'data_vencimento', 'status'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), false)
            ->order($this->pegarOrdem(new Ordem()))
            ->read();
    }
}
