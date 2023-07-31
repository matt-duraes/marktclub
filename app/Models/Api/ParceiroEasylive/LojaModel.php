<?php

namespace App\Models\Api\ParceiroEasylive;

use ORM\ORM;
use stdClass;
use System\Trait\Model\OrdemTrait;
use App\Classes\Geral\Status;
use System\Trait\Model\PaginaTrait;
use App\Classes\ParceiroEasylive\Tipo;
use App\Classes\ParceiroEasylive\Ordem;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

final class LojaModel extends ORM implements ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PARCEIRO_EASYLIVE;

    public function __construct(
        private int $pagina,
        private Tipo $tipo,
        private Status $status,
        private Ordem $ordem
    ) {
        parent::__construct();
        $this->validarDados();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'tipo', 'imagem', 'data_validade', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('id', 'DESC')
            ->read();

        $dado->lista = $this->montarDado($dado->lista);
        return $dado;
    }

    private function pegarWhere(): array
    {
        $idEmpresa = TOKEN['empresa']->id;
        $where = $idEmpresa == 1 ? [] : [['id_admin_empresa', 'json', $idEmpresa]];

        $tipo = $this->tipo;
        if ($tipo->valido()) {
            $where[] = ['tipo', $tipo->numero()];
        }
        $status = $this->status;
        if ($status->indice() == Status::ATIVO) {
            $where[] = ['data_validade', '>=', hoje()];
            $where[] = ['status', 1];
        } elseif ($status->indice() == Status::INATIVO) {
            $where[] = [
                'OR',
                ['status', 2],
                ['data_validade', '<', hoje()]
            ];
        }
        return $where;
    }

    private function validarDados()
    {
        if (!$this->tipo->vazio() && !$this->tipo->valido()) {
            mensagemErro('Campo inválido!', 'O campo Tipo não é válido!');
        } elseif (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O campo Status não é válido!');
        } elseif (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'O campo Ordem não é válido!');
        }
    }

    private function montarDado($dado)
    {
        $retorno = [];
        $Tipo = new Tipo();
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'            => $r->uuid,
                'titulo'        => $r->titulo,
                'tipo'          => $Tipo->indice($r->tipo),
                'imagem'        => arquivoPrivado($r->imagem),
                'data_validade' => $r->data_validade,
                'status'        => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
