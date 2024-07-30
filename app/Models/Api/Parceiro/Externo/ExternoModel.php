<?php

namespace App\Models\Api\Parceiro\Externo;

use App\Classes\ParceiroLoja\Status;
use App\Models\Api\Parceiro\Externo\Trait\PropriedadeTrait;
use App\Models\Api\Parceiro\Externo\Trait\ValidarTrait;
use App\Models\Api\Parceiro\Externo\Trait\WhereTrait;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class ExternoModel extends ORM implements ModelListarInterface
{
    use PropriedadeTrait;
    use WhereTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarTrait;

    public Pagina $pagina;
    public Quantidade $quantidade;
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $this->validarRequest();
        $dado = $this
            ->campo([
                'uuid', 'titulo_interno', 'data_criacao', 'status', 'tipo_indicador'
            ])
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

    private function montarRetorno($dado): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'             => $r->uuid,
                'titulo_interno' => $r->titulo_interno,
                'tipo_indicador' => $r->tipo_indicador,
                'data_criacao'   => $r->data_criacao,
                'status'         => $Status->indice($r->status),
            ];
        }
        return $retorno;
    }
}
