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
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $pesquisa = null,
        private readonly ?string $empresa = null,
        private readonly ?string $titulo = null,
        private readonly Tipo $tipo = new Tipo(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Botao $publicado = new Botao(),
        private readonly Status $status = new Status()
    ) {
        $this->validarDados();
        $this->validarEmpresa();
        parent::__construct();
    }

    private function validarDados(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->tipo->vazio() && !$this->tipo->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo informado não é válido.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início informada não é válida.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de final informada não é válida.');
        }
        if (!$this->publicado->vazio() && !$this->publicado->valido()) {
            mensagemErro('Campo inválido!', 'O valor publicado não é válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    public function listarDados(): stdClass
    {
        $publicidades = $this
            ->campo([
                'uuid', 'titulo', 'link', 'imagem_desktop', 'imagem_mobile',
                'tipo', 'data_inicio', 'data_final', 'status'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), false)
            ->order($this->pegarOrdem())
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->where($this->pegarWhereParceiro(), false)
            ->campo([
                'uuid', 'titulo', 'url', 'tipo_loja', 'imagem_logo'
            ], 'parceiro')
            ->join('id', 'id_parceiro_loja')
            ->read();

        $publicidades->lista = $this->montarRetorno($publicidades->lista);
        return $publicidades;
    }

    private function pegarWhere(): array
    {
        $where = [];
        $publicadoVazio = $this->publicado->vazio();
        if (!$publicadoVazio && $this->publicado->valor() == Publicado::SIM) {
            $where[] = [
                ['data_inicio', '<=', hoje()],
                ['data_final', '>=', hoje()],
                ['status', 1]
            ];
        } elseif (!$publicadoVazio && $this->publicado->valor() == Publicado::NAO) {
            $where[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_final', '<', hoje()],
                ['status', '!=', 1]
            ];
        }
        if (!empty($this->pesquisa)) {
            $where[] = ['titulo', 'LIKE', "%{$this->pesquisa}%"];
        }
        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'LIKE', "%{$this->titulo}%"];
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

    private function montarRetorno(array $publicidades): array
    {
        $retorno = [];
        $Status = new Status();
        $Tipo = new Tipo();
        $TipoParceiro = new TipoLoja();
        foreach ($publicidades as $banner) {
            $statusAtual = $Status->indice($banner->status);
            $publicado = (new Publicado(
                new Data($banner->data_inicio),
                new Data($banner->data_final),
                $statusAtual == Status::ATIVO
            ))->indice();

            $retorno[] = [
                'id'          => $banner->uuid,
                'titulo'      => $banner->titulo,
                'parceiro'    => [
                    'id'     => $banner->parceiro_uuid,
                    'titulo' => $banner->parceiro_titulo,
                    'url'    => $banner->parceiro_url,
                    'tipo'   => $TipoParceiro->indice($banner->parceiro_tipo_loja),
                    'logo'   => arquivoPrivado($banner->parceiro_imagem_logo)
                ],
                'data_inicio'    => $banner->data_inicio,
                'data_final'     => $banner->data_final,
                'imagem_desktop' => arquivoPrivado($banner->imagem_desktop),
                'imagem_mobile'  => arquivoPrivado($banner->imagem_mobile),
                'link'           => $banner->link,
                'tipo'           => $Tipo->indice($banner->tipo),
                'publicado'      => $publicado,
                'status'         => $statusAtual
            ];
        }
        return $retorno;
    }
}
