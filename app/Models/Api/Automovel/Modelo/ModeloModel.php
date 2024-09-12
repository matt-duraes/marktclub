<?php

namespace App\Models\Api\Automovel\Modelo;

use App\Classes\Automovel\Modelo\Ordem;
use App\Classes\Geral\Publicado;
use App\Classes\Geral\Status;
use App\Classes\ParceiroLoja\Status as ParceiroStatus;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Trait\ValidarRequestListar;
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
    use ValidarRequestListar;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_AUTOMOVEL_MODELO;

    /**
     * @param Pagina          $pagina
     * @param Quantidade      $quantidade
     * @param Ordem           $ordem
     * @param string|int|null $parceiro
     * @param string|int|null $modelo
     * @param string|null     $pesquisa
     * @param string|null     $titulo
     * @param Botao           $publicado
     * @param Data            $dataInicio
     * @param Data            $dataFinal
     * @param Status          $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private string|int|null $parceiro = null,
        private string|int|null $modelo = null,
        private readonly ?string $pesquisa = null,
        private readonly ?string $titulo = null,
        private readonly Botao $publicado = new Botao(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarRequestListar();
        $this->validarEmpresa(json: true);
        //$this->pegarParceiro();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $modelos = $this
            ->campo([
                'uuid', 'titulo', 'imagem', 'url', 'data_inicio',
                'data_final', 'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->where($this->pegarWhereLoja(), false)
            ->campo([
                'uuid', 'titulo', 'titulo_interno', 'status'
            ], 'parceiro')
            ->join('id', 'id_parceiro_loja')
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

        if (!empty($this->modelo)) {
            $where[] = ['url', $this->modelo];
        }

        if (!empty($this->pesquisa)) {
            $where[] = ['titulo', 'LIKE', "%$this->pesquisa%"];
        }

        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'LIKE', "%$this->titulo%"];
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

        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_final', 'between', [$this->dataInicio->date(), $this->dataFinal->date()]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_inicio', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_final', $this->dataFinal->date()];
        }

        return $where;
    }

    /**
     * @return array
     * @throws Excecao
     */
    private function pegarWhereLoja(): array
    {
        $where = $this->ormWherePadrao;
        $status = (new ParceiroStatus(ParceiroStatus::CONCLUIDO))->numero();

        if (!empty($this->parceiro) && is_numeric($this->parceiro)) {
            $where[] = ['id', $this->parceiro];
        }
        if (!empty($this->parceiro) && !validarUuid($this->parceiro, false)) {
            $where[] = ['url', $this->parceiro];
        }
        if (!empty($this->parceiro) && validarUuid($this->parceiro, false)) {
            $where[] = ['uuid', $this->parceiro];
        }
        if (!$this->publicado->valido()) {
            return $where;
        }
        if ($this->publicado->valor() === Botao::SIM) {
            $where[] = ['status', $status];
        } else {
            $where[] = ['status', '!=', $status];
        }
        return $where;
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

    /**
     * @throws Excecao
     */
    private function pegarParceiro(): void
    {
        if (empty($this->parceiro)) {
            return;
        }
        $where = $this->ormWherePadrao;
        $Loja = new OrmHelper(TABELA_PARCEIRO_LOJA);
        if (validarUuid($this->parceiro, false)) {
            $where[] = ['uuid', $this->parceiro];
            $this->parceiro = $Loja->pegarCampoPor('id', $where);
            return;
        }
        $where[] = ['url', $this->parceiro];
        $this->parceiro = $Loja->pegarCampoPor('id', $where);
    }
}
