<?php

namespace App\Models\Api\SolicitacaoDeclaracao;

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoDeclaracao\Ordem;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class DeclaracaoModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_DECLARACAO;

    /**
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private Data $dataCriacaoDe = new Data(null),
        private Data $dataCriacaoAte = new Data(null),
        private Status $status = new Status(null),
        private ?string $empresa = null,
        private Ordem $ordem = new Ordem(null)
    ) {
        $this->validarEmpresa();
        $this->validarDado();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarDado(): void
    {
        if (!$this->dataCriacaoDe->vazio() && !$this->dataCriacaoDe->eDate()) {
            mensagemErro('Campo inválido!', 'A data de criação de início não está no formato válido.');
        }
        if (!$this->dataCriacaoAte->vazio() && !$this->dataCriacaoAte->eDate()) {
            mensagemErro('Campo inválido!', 'A data de criação final não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A ordem informada não é válida.');
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
                'uuid', 'status', 'data_criacao', 'data_atualizacao'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), false)
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join('id', 'id_parceiro_loja')
            ->campo(['titulo'], as: 'parceiro')
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

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }

        if ($this->dataCriacaoDe->valido() && $this->dataCriacaoAte->valido()) {
            $where[] = [
                'data_criacao', 'between', [$this->dataCriacaoDe->date(), $this->dataCriacaoAte->date() . ' 23:59:59']
            ];
        } elseif ($this->dataCriacaoDe->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataCriacaoDe->date()];
        } elseif ($this->dataCriacaoAte->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataCriacaoAte->date() . ' 23:59:59'];
        }

        return $where;
    }

    /**
     * @param array $declaracoes
     *
     * @return array
     */
    protected function montarRetorno(array $dado): array
    {
        if (empty($dado)) {
            return [];
        }

        $Status = new Status();
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'               => $r->uuid,
                'parceiro'         => $r->parceiro_titulo,
                'data_criacao'     => $r->data_criacao,
                'data_atualizacao' => $r->data_atualizacao,
                'status'           => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
