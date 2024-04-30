<?php

namespace App\Models\Api\ComunicacaoPublicidade;

use ORM\ORM;
use stdClass;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Helpers\OrmHelper;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Models\Site\ListarInterface;
use App\Classes\ParceiroLoja\TipoLoja;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\ComunicacaoPublicidade\Tipo;
use App\Classes\ComunicacaoPublicidade\Ordem;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class PublicidadeModel extends ORM implements ListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMUNICACAO_PUBLICIDADE;
    private int $idEmpresa;

    public function __construct(
        private Pagina $pagina,
        private Quantidade $quantidade,
        protected ?string $pesquisa = null,
        protected Tipo $tipo = new Tipo(null),
        protected Data $dataInicio = new Data(null),
        protected Data $dataFinal = new Data(null),
        protected Status $status = new Status(null),
        private Botao $publicado = new Botao(null),
        private ?string $empresa = null,
        private Ordem $ordem = new Ordem(null),
    ) {
        parent::__construct();
        $this->validarEmpresa();
        $this->validarDado();
    }

    private function validarDado()
    {
        if (!$this->tipo->vazio() && !$this->tipo->valido()) {
            mensagemErro('Dado inválido', 'O tipo não é válido.');
        } elseif (!$this->dataInicio->vazio() && !$this->dataInicio->valido()) {
            mensagemErro('Dado inválido', 'A data de inicio não é válida.');
        } elseif (!$this->dataFinal->vazio() && !$this->dataFinal->valido()) {
            mensagemErro('Dado inválido', 'A data final não é válida.');
        } elseif (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Dado inválido', 'O status não é válido.');
        } elseif (!$this->publicado->vazio() && !$this->publicado->valido()) {
            mensagemErro('Dado inválido', 'O valor publicado não é válido.');
        } elseif (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Dado inválido', 'A ordem não é válida.');
        }
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'link', 'imagem_desktop', 'imagem_mobile', 'tipo',
                'data_inicio', 'data_final', 'status'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem())
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->where($this->pegarWhereParceiro(), obrigatorio: false)
            ->campo(['uuid', 'titulo', 'url', 'tipo_loja', 'imagem_logo'], as: 'parceiro')
            ->join('id', 'id_parceiro_loja')
            ->read();

        $dado->lista = $this->montarDado($dado->lista);
        return $dado;
    }

    private function montarDado(array $dado): array
    {
        $retorno = [];
        $Status = new Status();
        $Tipo = new Tipo();
        $TipoParceiro = new TipoLoja();
        foreach ($dado as $r) {
            $statusAtual = $Status->indice($r->status);
            $publicado = (new Publicado(
                new Data($r->data_inicio),
                new Data($r->data_final),
                $statusAtual == Status::ATIVO
            ))->indice();

            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $r->titulo,
                'parceiro'    => [
                    'id'     => $r->parceiro_uuid,
                    'titulo' => $r->parceiro_titulo,
                    'url'    => $r->parceiro_url,
                    'tipo'   => $TipoParceiro->indice($r->parceiro_tipo_loja),
                    'logo'   => arquivoPrivado($r->parceiro_imagem_logo)
                ],
                'data_inicio'    => $r->data_inicio,
                'data_final'     => $r->data_final,
                'imagem_desktop' => arquivoPrivado($r->imagem_desktop),
                'imagem_mobile'  => arquivoPrivado($r->imagem_mobile),
                'link'           => $r->link,
                'tipo'           => $Tipo->indice($r->tipo),
                'publicado'      => $publicado,
                'status'         => $statusAtual
            ];
        }
        return $retorno;
    }

    private function pegarWhere(): array
    {
        $where = [];
        $publicadoVazio = $this->publicado->vazio();
        if (!$publicadoVazio && $this->publicado->valor() == 'sim') {
            $where[] = [
                ['data_inicio', '<=', hoje()],
                ['data_final', '>=', hoje()],
                ['status', 1]
            ];
        } elseif (!$publicadoVazio && $this->publicado->valor() == 'nao') {
            $where[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_final', '<', hoje()],
                ['status', '!=', 1]
            ];
        }
        if (!empty($this->pesquisa)) {
            $where[] = ['titulo', 'like', $this->pesquisa . '%'];
        }
        if ($this->dataInicio->valido() && $publicadoVazio) {
            $where[] = ['data_inicio', '<=', $this->dataInicio->date()];
        }
        if ($this->dataFinal->valido() && $publicadoVazio) {
            $where[] = ['data_final', '>=', $this->dataFinal->date()];
        }
        if ($this->status->valido() && $publicadoVazio) {
            $where[] = ['status', $this->status->numero()];
        }
        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
        }
        return $where;
    }

    private function pegarWhereParceiro()
    {
        if ($this->idEmpresa != 1) {
            return ['id_admin_empresa', 'json', $this->idEmpresa];
        } elseif (empty($this->empresa)) {
            return [];
        }

        $id = (new OrmHelper(TABELA_PARCEIRO_LOJA))->pegarIdPeloUuid($this->empresa);
        return ['id_admin_empresa', 'json', $id];
    }
}
