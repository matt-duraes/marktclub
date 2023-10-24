<?php

namespace App\Models\Api\ComercialPopup;

use App\Classes\ComercialPopup\BotaoTarget;
use App\Classes\ComercialPopup\Ordem;
use App\Classes\ComercialPopup\Status;
use App\Classes\Geral\Publicado;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Botao;
use Modules\Data;
use Modules\Link;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class PopupModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMERCIAL_POPUP;
    protected ?int $idEmpresa;

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
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status(),
        private readonly Botao $publicado = new Botao(null),
    ) {
        $this->validarEmpresa();
        $this->validarDados();
        parent::__construct();
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
                'uuid', 'slug', 'imagem', 'titulo', 'texto', 'regulamento', 'data_inicio',
                'data_final', 'atualizar_dado', 'botao_texto', 'botao_link',
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
            $where[] = ['titulo', 'LIKE', '%' . $this->titulo . '%'];
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

        if ($publicado) {
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
                'slug'           => $popup->slug,
                'imagem'         => arquivoPublico(LINK_ARQUIVO_PUBLICO, $popup->imagem ?? ''),
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
     * @param EmpresaEntity $empresaEntity
     *
     * @return array
     * @throws Excecao
     */
    public function pegarPopupDoDia(EmpresaEntity $empresaEntity): array
    {
        $Status = new Status();
        $hoje = date('Y-m-d');
        $popup = $this
            ->campo([
                'uuid', 'slug', 'imagem', 'titulo', 'texto', 'regulamento', 'data_inicio',
                'data_final', 'atualizar_dado', 'botao_texto', 'botao_link',
                'botao_target'
            ])
            ->where([
                ['id_admin_empresa', $empresaEntity->get('id')],
                ['data_inicio', '<=', $hoje],
                ['data_final', '>=', $hoje],
                ['status', $Status->numero(Status::ATIVO)]
            ])
            ->read();
        return $this->montarPopup($popup);
    }

    /**
     * @param array $popup
     *
     * @return array
     */
    private function montarPopup(array $popup): array
    {
        if (empty($popup)) {
            return $popup;
        }

        $BotaoTarget = new BotaoTarget();
        $retorno = [];
        foreach ($popup as $item) {
            $retorno[] = [
                'id'             => $item->uuid,
                'slug'           => $item->slug,
                'imagem'         => arquivoPublico('00a9c7ca-7dd9-43a1-9dc7-20297a26d49d', $item->imagem ?? ''),
                'titulo'         => $item->titulo,
                'texto'          => $item->texto,
                'regulamento'    => $item->regulamento,
                'atualizar_dado' => $item->atualizar_dado,
                'data_inicio'    => $item->data_inicio,
                'data_final'     => $item->data_final,
                'botao_texto'    => $item->botao_texto,
                'botao_link'     => (new Link($item->botao_link))->valor(),
                'botao_target'   => $BotaoTarget->indice($item->botao_target)
            ];
        }
        return $retorno;
    }
}
