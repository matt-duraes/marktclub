<?php

namespace App\Models\Api\Automovel\Modelo;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Helpers\OrmHelper;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\Automovel\Modelo\Ordem;
use System\Trait\Model\QuantidadeTrait;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Classes\ParceiroLoja\Status as ParceiroStatus;

final class ModeloModel extends ORM
{
    use ValidarEmpresaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use PaginaTrait;

    protected string $ormTabela = TABELA_AUTOMOVEL_MODELO;
    private int $idEmpresa;

    public function __construct(
        private Pagina $pagina,
        private Quantidade $quantidade = new Quantidade(20),
        private Botao $publicado = new Botao(null),
        private Data $dataInicio = new Data(null),
        private Data $dataFinal = new Data(null),
        private null|int|string $parceiro = null,
        private Status $status = new Status(null),
        private Ordem $ordem = new Ordem(null)
    ) {
        parent::__construct();
        $this->validarEmpresa();
        $this->validarCampos();
        $this->pegarParceiro();
    }

    private function validarCampos()
    {
        if ($this->pagina->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo pagina é obrigatório.');
        } elseif (!$this->pagina->valido()) {
            mensagemErro('Campo obrigatório!', 'O campo pagina não é valido.');
        } elseif (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo obrigatório!', 'O campo quantidade não é valido.');
        } elseif (!$this->publicado->vazio() && !$this->publicado->valido()) {
            mensagemErro('Campo obrigatório!', 'O campo publicado não é valido.');
        } elseif (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo obrigatório!', 'O campo data de início não é valida.');
        } elseif (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo obrigatório!', 'O campo data de início não é valida.');
        } elseif (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo obrigatório!', 'O campo status não é valido.');
        } elseif (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo obrigatório!', 'O campo ordem não é valido.');
        }
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'imagem', 'url', 'status', 'data_criacao', 'data_inicio', 'data_final'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem())
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->where($this->pegarWhereLoja(), obrigatorio: false)
            ->campo(['uuid', 'titulo', 'status'], 'parceiro')
            ->join('id', 'id_parceiro_loja')
            ->order('titulo')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);

        return $dado;
    }

    /**
     * @param  array $dado
     * @return array
     */
    protected function montarRetorno(array $dado): array
    {
        $retorno = [];
        $Status = new Status();
        $ParceiroStatus = new ParceiroStatus();
        foreach ($dado as $r) {
            $dataInicio = new Data($r->data_inicio);
            $dataFinal = new Data($r->data_final);
            $retorno[] = (object)[
                'id'        => $r->uuid,
                'titulo'    => $r->titulo,
                'parceiro'  => [
                    'id'     => $r->parceiro_uuid,
                    'titulo' => $r->parceiro_titulo,
                ],
                'imagem'       => arquivoPrivado($r->imagem),
                'url'          => $r->url,
                'data_inicio'  => $dataInicio->date(),
                'data_final'   => $dataFinal->date(),
                'data_criacao' => $r->data_criacao,
                'publicado'    => (new Publicado(
                    $dataInicio,
                    $dataFinal,
                    $ParceiroStatus->indice($r->parceiro_status) == $ParceiroStatus::CONCLUIDO
                ))->indice(),
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = [];
        $publicado = $this->publicado->valido();

        if (is_int($this->parceiro)) {
            $where[] = ['id_parceiro_loja', $this->parceiro];
        }
        if ($this->status->valido() && !$publicado) {
            $where[] = ['status', $this->status->numero()];
        }

        if ($publicado && $this->publicado->valor() == Botao::SIM) {
            $where[] = [
                ['data_inicio', '<=', hoje()],
                ['data_final', '>=', hoje()],
                ['status', (new Status(Status::ATIVO))->numero()]
            ];
        } elseif ($publicado && $this->publicado->valor() == Botao::NAO) {
            $where[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_final', '<', hoje()],
                ['status', '!=', (new Status(Status::ATIVO))->numero()]
            ];
        }
        return $where;
    }

    private function pegarWhereLoja(): array
    {
        if (!$this->publicado->valido()) {
            return [];
        }
        if ($this->publicado->valor() == Botao::SIM) {
            return ['status', (new ParceiroStatus(ParceiroStatus::CONCLUIDO))->numero()];
        }
        return ['status', '!=', (new ParceiroStatus(ParceiroStatus::CONCLUIDO))->numero()];
    }

    private function pegarParceiro()
    {
        if (empty($this->parceiro)) {
            return;
        }
        $Loja = new OrmHelper(TABELA_PARCEIRO_LOJA);
        if (validarUuid($this->parceiro, false)) {
            $this->parceiro = $Loja->pegarIdPeloUuid($this->parceiro);
            return;
        }
        $this->parceiro = $Loja->pegarCampoPor(
            campo: 'id',
            where: ['url', $this->parceiro],
            padrao: 0
        );
    }
}
