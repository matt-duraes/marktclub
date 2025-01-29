<?php

namespace App\Models\Api\AlbumDado;

use App\Classes\AlbumDado\Ordem;
use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class AlbumModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_ALBUM_DADO;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $pesquisa
     * @param string|null $empresa
     * @param string|null $equipe
     * @param string|null $titulo
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
        private readonly ?string $pesquisa = null,
        private readonly ?string $empresa = null,
        private readonly ?string $equipe = null,
        private readonly ?string $titulo = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarRequest();
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
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
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A data de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A data de final não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $albuns = $this
            ->campo([
                'uuid', 'titulo', 'texto', 'imagem', 'url', 'permissao_restrita',
                'permissao_site', 'data_inicio', 'data_final', 'status'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'nome_fantasia'
            ], 'empresa')
            ->tabela(TABELA_USUARIO_EQUIPE)
            ->join('id', 'id_usuario_equipe')
            ->campo([
                'nome_real'
            ], 'equipe')
            ->read();

        if (empty($albuns)) {
            return $this->paginacaoZero();
        }

        $albuns->lista = $this->montarRetorno($albuns->lista);
        return $albuns;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if (!empty($this->pesquisa)) {
            $where[] = [
                ['titulo', 'like', '%' . $this->pesquisa . '%'],
                ['texto', 'like', '%' . $this->pesquisa . '%']
            ];
        }

        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'like', '%' . $this->titulo . '%'];
        }

        if (!empty($this->empresa)) {
            $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
            $where[] = ['id_admin_empresa', '=', $ormHelper->pegarIdPeloUuid($this->empresa)];
        }

        if (!empty($this->equipe)) {
            $ormHelper = new OrmHelper(TABELA_USUARIO_EQUIPE);
            $where[] = ['id_usuario_equipe', '=', $ormHelper->pegarIdPeloUuid($this->equipe)];
        }

        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_inicio', 'between', [$this->dataInicio->date(), $this->dataFinal->date()]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_inicio', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_final', $this->dataFinal->date()];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @param array $albuns
     *
     * @return array
     */
    private function montarRetorno(array $albuns): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($albuns as $album) {
            $retorno[] = [
                'id'                 => $album->uuid,
                'empresa'            => $album->empresa_nome_fantasia,
                'equipe'             => $album->equipe_nome_real,
                'titulo'             => $album->titulo,
                'texto'              => $album->texto,
                'url'                => $album->url,
                'imagem'             => arquivoPrivado($album->imagem),
                'permissao_restrita' => $album->permissao_restrita,
                'permissao_site'     => $album->permissao_site,
                'data_inicio'        => $album->data_inicio,
                'data_final'         => $album->data_final,
                'status'             => $Status->indice($album->status)
            ];
        }
        return $retorno;
    }
}
