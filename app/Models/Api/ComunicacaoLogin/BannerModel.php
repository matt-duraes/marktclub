<?php

namespace App\Models\Api\ComunicacaoLogin;

use App\Classes\ComunicacaoLogin\Ordem;
use App\Classes\Geral\Publicado;
use App\Classes\Geral\Status;
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

class BannerModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMUNICACAO_LOGIN;

    /**
     *
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param Botao       $publicado
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
        private readonly Botao $publicado = new Botao(),
        private readonly ?string $titulo = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarDados();
        parent::__construct();
    }

    /**
     * @return void
     * @throws Excecao
     */
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
        if (!$this->publicado->vazio() && !$this->publicado->valido()) {
            mensagemErro('Campo inválido!', 'O Publicado informado não é válido.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início informada não é válida.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de final informada não é válida.');
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
        $banners = $this
            ->campo([
                'id_admin_empresa', 'uuid', 'titulo', 'arquivo_1',
                'arquivo_2', 'arquivo_3', 'data_inicio', 'data_fim', 'status',
                'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('padrao', !$this->publicado->vazio() ? 'asc' : 'desc')
            ->read();

        $banners->lista = $this->montarRetorno($banners->lista);
        return $banners;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = [];
        $wherePublicado = [];
        $publicadoVazio = $this->publicado->vazio();
        if (!$publicadoVazio && $this->publicado->valor() == 'sim') {
            $wherePublicado[] = [
                ['data_inicio', '<=', hoje()],
                ['data_fim', '>=', hoje()],
                ['status', 1]
            ];
        } elseif (!$publicadoVazio && $this->publicado->valor() == 'nao') {
            $wherePublicado[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_fim', '<', hoje()],
                ['status', '!=', 1]
            ];
        }
        if (!empty($this->empresa)) {
            $ormEmpresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
            $id = $ormEmpresa->pegarIdPeloUuid($this->empresa);

            if ($id && !empty($this->publicado)) {
                $where[] = [
                    'OR',
                    [
                        ['id_admin_empresa', 'json', jsonEncode($id)],
                        $wherePublicado
                    ],
                    ['padrao', 1]
                ];
            } else {
                $where[] = ['id_admin_empresa', 'json', $id];
            }
        } else {
            if (!empty($wherePublicado)) {
                $where[] = $wherePublicado;
            }
        }
        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'LIKE', "%$this->titulo%"];
        }
        if ($this->dataInicio->valido() && $publicadoVazio) {
            $where[] = ['data_inicio', '<=', $this->dataInicio->date()];
        }
        if ($this->dataFinal->valido() && $publicadoVazio) {
            $where[] = ['data_fim', '>=', $this->dataFinal->date()];
        }
        if ($this->status->valido() && $publicadoVazio) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @param array $banners
     *
     * @return array
     */
    private function montarRetorno(array $banners): array
    {
        if (empty($banners)) {
            return $banners;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($banners as $banner) {
            $statusAtual = $Status->indice($banner->status);
            $publicado = (new Publicado(
                new Data($banner->data_inicio),
                new Data($banner->data_fim),
                $statusAtual == Status::ATIVO
            ))->indice();

            $retorno[] = [
                'id'               => $banner->uuid,
                'titulo'           => $banner->titulo,
                'url'              => [
                    arquivoPrivado($banner->arquivo_1),
                    arquivoPrivado($banner->arquivo_2),
                    arquivoPrivado($banner->arquivo_3)
                ],
                'data_inicio'      => $banner->data_inicio,
                'data_fim'         => $banner->data_fim,
                'publicado'        => $publicado,
                'status'           => $statusAtual,
                'data_criacao'     => $banner->data_criacao,
                'data_atualizacao' => $banner->data_atualizacao
            ];
        }
        return $retorno;
    }
}
