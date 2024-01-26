<?php

namespace App\Models\Api\SolicitacaoLoja;

use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Origem;
use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class SolicitacaoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $nome
     * @param Data        $dataInicio
     * @param Data        $dataFinal
     * @param Origem      $origem
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $nome = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Origem $origem = new Origem(),
        private readonly Status $status = new Status()
    ) {
        $this->validarDado();
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarDado(): void
    {
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data final não está no formato válido.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->origem->vazio() && !$this->origem->valido()) {
            mensagemErro('Campo inválido!', 'A Origem informada não é válida.');
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
                'uuid', 'nome', 'email', 'telefone',
                'origem', 'status', 'data_criacao'
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

        if (!empty($this->nome)) {
            $where[] = ['nome', 'LIKE', '%' . $this->nome . '%'];
        }

        if ($this->origem->valido()) {
            $where[] = ['origem', $this->origem->numero()];
        }

        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [$this->dataInicio->date(), $this->dataFinal->date() . ' 23:59:59']
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFinal->date() . ' 23:59:59'];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }

        return $where;
    }

    /**
     * @param array $solicitacoes
     *
     * @return array
     */
    private function montarRetorno(array $solicitacoes): array
    {
        if (empty($solicitacoes)) {
            return $solicitacoes;
        }

        $Origem = new Origem();
        $Status = new Status();
        $retorno = [];
        foreach ($solicitacoes as $solicitacao) {
            $retorno[] = [
                'id'           => $solicitacao->uuid,
                'nome'         => $solicitacao->nome,
                'email'        => $solicitacao->email,
                'telefone'     => $solicitacao->telefone,
                'origem'       => $Origem->indice($solicitacao->origem),
                'status'       => $Status->indice($solicitacao->status),
                'data_criacao' => $solicitacao->data_criacao
            ];
        }
        return $retorno;
    }
}
