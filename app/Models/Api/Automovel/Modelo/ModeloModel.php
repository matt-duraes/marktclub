<?php

namespace App\Models\Api\Automovel\Modelo;

use App\Classes\Automovel\Modelo\Ordem;
use App\Classes\Geral\Publicado;
use App\Classes\Geral\Status;
use App\Classes\ParceiroLoja\Status as ParceiroStatus;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class ModeloModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_AUTOMOVEL_MODELO;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $parceiro
     * @param string|null $pesquisa
     * @param Botao       $publicado
     * @param Data        $dataInicio
     * @param Data        $dataFinal
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private ?string $parceiro = null,
        private readonly ?string $pesquisa = null,
        private readonly Botao $publicado = new Botao(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarDados();
        $this->validarEmpresa();
        $this->pegarParceiro();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->publicado->vazio() && !$this->publicado->valido()) {
            mensagemErro('Campo inválido!', 'O campo publicado não é valido.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de final não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @throws Excecao
     */
    private function pegarParceiro(): void
    {
        if (empty($this->parceiro)) {
            return;
        }
        $Loja = new OrmHelper(TABELA_PARCEIRO_LOJA);
        if (validarUuid($this->parceiro, false)) {
            $this->parceiro = $Loja->pegarIdPeloUuid($this->parceiro);
            return;
        }
        $this->parceiro = $Loja->pegarCampoPor('id', ['url', $this->parceiro]);
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $modelos = $this
            ->campo([
                'uuid', 'titulo', 'imagem', 'url', 'data_inicio', 'data_final',
                'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->where($this->pegarWhereLoja(), false)
            ->campo([
                'uuid', 'titulo', 'status'
            ], 'parceiro')
            ->join('id', 'id_parceiro_loja')
            ->order('titulo')
            ->read();

        $modelos->lista = $this->montarRetorno($modelos->lista);
        return $modelos;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = [];

        if (is_int($this->parceiro)) {
            $where[] = ['id_parceiro_loja', $this->parceiro];
        }

        if (!empty($this->pesquisa)) {
            $where[] = ['titulo', 'LIKE', "%$this->pesquisa%"];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }

        $status = (new Status(Status::ATIVO))->numero();
        if ($this->publicado->valido() && ($this->publicado->valor() === Botao::SIM)) {
            $where[] = [
                ['data_inicio', '<=', hoje()],
                ['data_final', '>=', hoje()],
                ['status', $status]
            ];
        } elseif ($this->publicado->valido() && ($this->publicado->valor() === Botao::NAO)) {
            $where[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_final', '<', hoje()],
                ['status', '!=', $status]
            ];
        }
        return $where;
    }

    /**
     * @return array
     */
    private function pegarWhereLoja(): array
    {
        $where = [];
        $status = (new ParceiroStatus(ParceiroStatus::CONCLUIDO))->numero();
        if (!$this->publicado->valido()) {
            return $where;
        }
        if ($this->publicado->valor() === Botao::SIM) {
            return ['status', $status];
        }
        return ['status', '!=', $status];
    }

    /**
     * @param array $modelos
     *
     * @return array
     */
    private function montarRetorno(array $modelos): array
    {
        $Status = new Status();
        // Comentando para caso seja necessário
        //$ParceiroStatus = new ParceiroStatus();
        $retorno = [];
        foreach ($modelos as $modelo) {
            $dataInicio = new Data($modelo->data_inicio);
            $dataFinal = new Data($modelo->data_final);
            $ativo = $Status->indice($modelo->status) === Status::ATIVO;
            $publicado = (new Publicado($dataInicio, $dataFinal, $ativo))->indice();
            $retorno[] = [
                'id'               => $modelo->uuid,
                'titulo'           => $modelo->titulo,
                'parceiro'         => [
                    'id'     => $modelo->parceiro_uuid,
                    'titulo' => $modelo->parceiro_titulo
                ],
                'imagem'           => arquivoPrivado($modelo->imagem),
                'url'              => $modelo->url,
                'data_inicio'      => $dataInicio->date(),
                'data_final'       => $dataFinal->date(),
                'publicado'        => $publicado,
                'status'           => $Status->indice($modelo->status),
                'data_criacao'     => $modelo->data_criacao,
                'data_atualizacao' => $modelo->data_atualizacao
            ];
        }
        return $retorno;
    }
}
