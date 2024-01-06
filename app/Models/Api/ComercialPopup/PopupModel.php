<?php

namespace App\Models\Api\ComercialPopup;

use App\Classes\UsuarioCliente\TipoUsuario;
use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Modules\Data;
use Modules\Link;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Publicado;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\ComercialPopup\Ordem;
use App\Classes\ComercialPopup\Status;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\ComercialPopup\BotaoTarget;
use App\Models\Api\Trait\ValidarEmpresaTrait;

class PopupModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMERCIAL_POPUP;

    /**
     *
     * @param Pagina      $pagina     Página
     * @param Quantidade  $quantidade Quantidade por Página
     * @param Ordem       $ordem      Ordem da Lista
     * @param string|null $empresa    Empresa
     * @param Data        $dataInicio Data Início do Popup
     * @param Data        $dataFinal  Data Final do Popup
     * @param Status      $status     Status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $titulo = null,
        private readonly ?string $empresa = null,
        private readonly ?string $uri = null,
        private readonly TipoUsuario $usuarioTipo = new TipoUsuario(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status(),
        private readonly Botao $publicado = new Botao(null),
    ) {
        parent::__construct();
        $this->validarEmpresa(json: true);
        $this->validarDados();
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A data de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A data de final não está no formato válido.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
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
        $dado = $this
            ->campo([
                'uuid', 'titulo_painel', 'slug', 'imagem', 'titulo', 'texto', 'uri', 'regulamento',
                'data_inicio', 'data_final', 'atualizar_dado', 'botao_texto', 'botao_link',
                'botao_target', 'status'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        $publicado = $this->publicado->valido();
        if (!empty($this->titulo)) {
            $where[] = ['titulo_painel', 'LIKE', '%' . $this->titulo . '%'];
        }

        if ($this->dataInicio->valido() && $this->dataFinal->valido() && !$publicado) {
            $where[] = [
                'data_inicio', 'between', [$this->dataInicio->date(), $this->dataFinal->date()]
            ];
        } elseif ($this->dataInicio->valido() && !$publicado) {
            $where[] = ['data_inicio', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido() && !$publicado) {
            $where[] = ['data_final', $this->dataFinal->date()];
        }

        if ($this->status->valido() && !$publicado) {
            $where[] = ['status', $this->status->numero()];
        }

        if ($this->usuarioTipo->valido()) {
            $where[] = [
                'OR',
                ['usuario_tipo', 'json', $this->usuarioTipo->numero()],
                ['usuario_tipo', 'null']
            ];
        }

        if ($this->uri) {
            $where[] = ['uri', $this->uri];
        }

        if ($publicado) {
            $where[] = [
                [
                    'OR',
                    ['data_inicio', 'null'],
                    ['data_inicio', ''],
                    ['data_inicio', '<=', hoje()],
                ],
                [
                    'OR',
                    ['data_final', 'null'],
                    ['data_final', ''],
                    ['data_final', '>=', hoje()],
                ],
                ['status', (new Status(Status::ATIVO))->numero()]
            ];
        }
        return $where;
    }

    /**
     * @param array $popups
     *
     * @return array
     */
    private function montarRetorno(array $popups): array
    {
        if (empty($popups)) {
            return $popups;
        }

        $BotaoTarget = new BotaoTarget();
        $Status = new Status();
        $retorno = [];
        foreach ($popups as $popup) {
            $statusIndice = $Status->indice($popup->status);
            $publicado = new Publicado(
                new Data($popup->data_inicio),
                new Data($popup->data_final),
                $statusIndice == Status::ATIVO
            );

            $retorno[] = [
                'id'             => $popup->uuid,
                'titulo_painel'  => $popup->titulo_painel,
                'uri'            => $popup->uri,
                'slug'           => $popup->slug,
                'imagem'         => arquivoPrivado($popup->imagem ?? ''),
                'titulo'         => $popup->titulo,
                'texto'          => $popup->texto,
                'regulamento'    => $popup->regulamento,
                'atualizar_dado' => $popup->atualizar_dado,
                'data_inicio'    => $popup->data_inicio,
                'data_final'     => $popup->data_final,
                'botao_texto'    => $popup->botao_texto,
                'botao_link'     => (new Link($popup->botao_link))->valor(),
                'botao_target'   => $BotaoTarget->indice($popup->botao_target),
                'publicado'      => $publicado->indice(),
                'status'         => $statusIndice
            ];
        }
        return $retorno;
    }

    /**
     * @throws Excecao
     */
    public function expirados(): void
    {
        $popups = $this
            ->campo([
                'uuid', 'data_inicio', 'data_final', 'status'
            ])
            ->where(['status', (new Status(Status::ATIVO))->numero()])
            ->read();

        foreach ($popups as $popup) {
            if (date('Y-m-d') > (new Data($popup->data_final))->date()) {
                $PopupEntity = new PopupEntity();
                $PopupEntity->uuid($popup->uuid);
                $PopupEntity->status = new Status(Status::EXPIRADO);
                $PopupEntity->salvar();
            }
        }
    }
}
