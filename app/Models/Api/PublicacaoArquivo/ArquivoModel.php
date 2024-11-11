<?php

namespace App\Models\Api\PublicacaoArquivo;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Modules\Data;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\PublicacaoArquivo\Tipo;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\PublicacaoArquivo\Ordem;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Trait\ValidarRequestListar;

final class ArquivoModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use ValidarRequestListar;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_ARQUIVO;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $pesquisa
     * @param Tipo        $tipo
     * @param Botao       $site
     * @param Botao       $restrita
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
        private readonly ?string $pesquisa = null,
        private readonly Tipo $tipo = new Tipo(),
        private readonly Botao $site = new Botao(),
        private readonly Botao $restrita = new Botao(),
        private readonly Botao $publicado = new Botao(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarEmpresa();
        $this->validarRequestListar();
        parent::__construct();
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $arquivos = $this
            ->campo([
                'uuid', 'titulo', 'texto', 'imagem', 'arquivo', 'url',
                'permissao_site', 'permissao_restrita', 'tipo',
                'data_inicio', 'data_final', 'status', 'data_criacao',
                'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();
        $arquivos->lista = $this->montarRetorno($arquivos->lista);
        return $arquivos;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $Status = new Status(Status::ATIVO);
        $where = $this->ormWherePadrao;
        if (!empty($this->pesquisa)) {
            $where[] = [
                'OR',
                ['titulo', 'like', '%' . $this->pesquisa . '%'],
                ['texto', 'like', '%' . $this->pesquisa . '%']
            ];
        }
        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
        }
        if ($this->site->valido()) {
            $where[] = ['permissao_site', $this->site->numero()];
        }
        if ($this->restrita->valido()) {
            $where[] = ['permissao_restrita', $this->restrita->numero()];
        }
        if ($this->publicado->valido() && $this->publicado->valor() === Botao::SIM) {
            $where[] = [
                [
                    'OR',
                    ['data_inicio', 'null'],
                    ['data_inicio', ''],
                    ['data_inicio', '<=', hoje() . ' 23:59:59'],
                ],
                [
                    'OR',
                    ['data_final', 'null'],
                    ['data_final', ''],
                    ['data_final', '>=', hoje()],
                ],
                ['status', $Status->numero()]
            ];
        } elseif ($this->publicado->valido() && $this->publicado->valor() === Botao::NAO) {
            $where[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_final', '<', hoje()],
                ['status', '!=', $Status->numero()]
            ];
        }
        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_inicio', 'between', [
                    $this->dataInicio->date(), $this->dataFinal->date()
                ]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_inicio', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_final', '<=', $this->dataFinal->date()];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @param array $arquivos
     *
     * @return array
     */
    private function montarRetorno(array $arquivos): array
    {
        $Tipo = new Tipo();
        $Status = new Status();
        $retorno = [];
        foreach ($arquivos as $arquivo) {
            $statusIndice = $Status->indice($arquivo->status);
            $publicado = new Publicado(
                new Data($arquivo->data_inicio),
                new Data($arquivo->data_final),
                $statusIndice === Status::ATIVO
            );
            $retorno[] = [
                'id'                 => $arquivo->uuid,
                'titulo'             => $arquivo->titulo,
                'texto'              => $arquivo->texto,
                'imagem'             => imagemPrivada($arquivo->imagem),
                'arquivo'            => imagemPrivada($arquivo->arquivo),
                'url'                => $arquivo->url,
                'publicado'          => $publicado->indice(),
                'tipo'               => $Tipo->indice($arquivo->tipo),
                'permissao_site'     => ($arquivo->permissao_site === 1) ? 'sim' : 'nao',
                'permissao_restrita' => ($arquivo->permissao_restrita === 1) ? 'sim' : 'nao',
                'status'             => $statusIndice,
                'data_criacao'       => $arquivo->data_criacao,
                'data_atualizacao'   => $arquivo->data_atualizacao
            ];
        }
        return $retorno;
    }
}
