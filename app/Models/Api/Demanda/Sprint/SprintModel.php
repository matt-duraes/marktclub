<?php

namespace App\Models\Api\Demanda\Sprint;

use ORM\ORM;
use stdClass;
use Where\Where;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\PaginaTrait;
use App\Classes\Demanda\Sprint\Status;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Parceiro\Externo\Trait\WhereTrait;

final class SprintModel extends ORM implements ModelListarInterface
{
    use WhereTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_DEMANDA_SPRINT;
    public Pagina $pagina;
    public string $titulo;
    public Quantidade $quantidade;
    public Data $data_inicio;
    public Data $data_final;
    public Status $status;

    private function validarRequest()
    {
        $this->validarPropriedade('
            pagina|Página|obrigatorio|vazio|valido
            quantidade|Quantidade|valido
            data_inicio|Data de início|valido
            data_final|Data final|valido
            status|Status|valido
        ');
    }

    public function listarDados(): stdClass
    {
        $this->validarRequest();
        $dado = $this
            ->campo(['uuid', 'titulo', 'data_inicio', 'data_final', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('status', 'ASC')
            ->read();

        if (!existeErro($dado, 'dado')) {
            return $this->paginacaoZero();
        }

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function pegarWhere(): Where
    {
        $Where = new Where($this);
        $Where
            ->data('data_inicio', 'data_final')
            ->linha(propriedade: 'titulo', condicao: 'like%%')
            ->linha(propriedade:'status');
        return $Where;
    }

    private function montarRetorno($dado)
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $r->titulo,
                'data_inicio' => $r->data_inicio,
                'data_final'  => $r->data_final,
                'status'      => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
