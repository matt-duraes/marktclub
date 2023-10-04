<?php

namespace App\Models\Api\SolicitacaoCredito;

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Ordem;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use Modules\Dinheiro;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class CreditoModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_CREDITO;
    protected ?int $idEmpresa;

    /**
     * @param Request|null $request
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $nome = null,
        private readonly Operadora $operadora = new Operadora(),
        private readonly Tipo $tipo = new Tipo(),
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
        if (!$this->dataInicio->vazio() && $this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A data de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A data final não está no formato válido.');
        }
        if (!$this->operadora->vazio() && !$this->operadora->valido()) {
            mensagemErro('Campo inválido!', 'A Operadora informada não é válida.');
        }
        if (!$this->tipo->vazio() && !$this->tipo->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo informado não é válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return object
     * @throws Excecao
     */
    public function listarDados(): object
    {
        $dado = $this
            ->campo([
                'uuid', 'operadora', 'tipo', 'valor_total', 'parcela',
                'valor_parcela', 'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->where($this->pegarWhereUsuario(), false)
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'uuid', 'nome'
            ], 'usuario')
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

        if ($this->operadora->valido()) {
            $where[] = ['operadora', $this->operadora->numero()];
        }

        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
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
     * @return array
     */
    protected function pegarWhereUsuario(): array
    {
        $where = [];
        if (!empty($this->nome)) {
            $where[] = ['nome', 'LIKE', '%' . $this->nome . '%'];
        }
        return $where;
    }

    /**
     * @param array $solicitacoes
     *
     * @return array
     */
    protected function montarRetorno(array $solicitacoes): array
    {
        if (empty($solicitacoes)) {
            return $solicitacoes;
        }

        $Operadora = new Operadora();
        $Tipo = new Tipo();
        $Status = new Status();
        $retorno = [];
        foreach ($solicitacoes as $solicitacao) {
            $retorno[] = [
                'id'            => $solicitacao->uuid,
                'usuario'       => [
                    'id'   => $solicitacao->usuario_uuid,
                    'nome' => $solicitacao->usuario_nome
                ],
                'operadora'     => $Operadora->indice($solicitacao->operadora),
                'tipo'          => $Tipo->indice($solicitacao->tipo),
                'parcela'       => $solicitacao->parcela,
                'valor_parcela' => (new Dinheiro((string)$solicitacao->valor_parcela))->dinheiro(),
                'valor_total'   => (new Dinheiro((string)$solicitacao->valor_total))->dinheiro(),
                'status'        => $Status->indice($solicitacao->status),
                'data_criacao'  => $solicitacao->data_criacao
            ];
        }
        return $retorno;
    }
}
