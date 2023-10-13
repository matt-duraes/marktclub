<?php

namespace App\Models\Api\ComunicacaoPopup;

use App\Classes\ComunicacaoPopup\BotaoTarget;
use App\Classes\ComunicacaoPopup\Ordem;
use App\Classes\ComunicacaoPopup\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
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

    protected string $ormTabela = TABELA_COMUNICACAO_POPUP;
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
        private readonly Status $status = new Status()
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
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'nome_fantasia'
            ], 'empresa')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'LIKE', '%' . $this->titulo . '%'];
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
     * @return array
     */
    protected function pegarWhereEmpresa(): array
    {
        $where = [];
        if (!empty($this->empresa)) {
            $where[] = ['cod', $this->empresa];
        }
        return $where;
    }

    /**
     * @param array $popups
     *
     * @return array
     */
    protected function montarRetorno(array $popups): array
    {
        if (empty($popups)) {
            return $popups;
        }

        $BotaoTarget = new BotaoTarget();
        $Status = new Status();
        $retorno = [];
        foreach ($popups as $popup) {
            $retorno[] = [
                'id'             => $popup->uuid,
                'empresa'        => [
                    'nome' => $popup->empresa_nome_fantasia
                ],
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
                'status'         => $Status->indice($popup->status)
            ];
        }
        return $retorno;
    }
}
