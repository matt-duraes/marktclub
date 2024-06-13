<?php

namespace App\Models\Api\Parceiro\Externo;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\ParceiroLoja\Status;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Parceiro\Externo\Trait\WhereTrait;
use App\Models\Api\Parceiro\Externo\Trait\ValidarTrait;
use App\Models\Api\Parceiro\Externo\Trait\PropriedadeTrait;

final class ExternoModel extends ORM implements ModelListarInterface
{
    use PropriedadeTrait;
    use WhereTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    public Pagina $pagina;
    public Quantidade $quantidade;

    public function listarDados(): stdClass
    {
        $this->validarRequest();
        $dado = $this
            ->campo(['uuid', 'titulo_interno', 'data_criacao', 'status'])
            ->where($this->pegarWhere())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        if (!existeErro($dado, 'dado')) {
            return $this->paginacaoZero();
        }

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function montarRetorno($dado)
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'             => $r->uuid,
                'titulo_interno' => $r->titulo_interno,
                'data_criacao'   => $r->data_criacao,
                'status'         => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
