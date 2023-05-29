<?php

namespace App\Models\Api\SolicitacaoCredito;

use App\Classes\SolicitacaoDeclaracao\Ordem;
use App\Classes\SolicitacaoDeclaracao\Status;
use App\Classes\SolicitacaoDeclaracao\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
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

    /**
     * @param Request|null $request
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->validarEmpresa();
        $this->validarRequest();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $dataCriacaoDe = new Data($this->request->get('data_criacao_de'));
        if (!$dataCriacaoDe->vazio() && (!$dataCriacaoDe->valido() || !$dataCriacaoDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação de início não está no formato válido.');
        }
        $dataCriacaoAte = new Data($this->request->get('data_criacao_ate'));
        if (!$dataCriacaoAte->vazio() && (!$dataCriacaoAte->valido() || !$dataCriacaoAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação final não está no formato válido.');
        }
        $Tipo = new Tipo($this->request->get('tipo'));
        if (!$Tipo->vazio() && !$Tipo->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo informado não é válido.');
        }
        $Status = new Status($this->request->get('status'));
        if (!$Status->vazio() && !$Status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
        $Ordem = new Ordem($this->request->get('ordem'));
        if (!$Ordem->vazio() && !$Ordem->valido()) {
            mensagemErro('Campo inválido!', 'A ordem informada não é válida.');
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
                'codigo', 'operadora', 'tipo', 'valor', 'parcelas',
                'valor_parcelas', 'status', 'data_criacao'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere())
            ->order($this->pegarOrdem(new Ordem()))
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

        $Tipo = new Tipo($this->request->get('tipo'));
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }

        $Status = new Status($this->request->get('status'));
        if ($Status->valido()) {
            $where[] = ['status', $Status->numero()];
        }

        $dataCriacaoDe = $this->request->get('data_criacao_de');
        $dataCriacaoAte = $this->request->get('data_criacao_ate');

        if (validarDataDate($dataCriacaoDe) && validarDataDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', 'between', [$dataCriacaoDe, $dataCriacaoAte]];
        } elseif (validarDataDate($dataCriacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataCriacaoDe)];
        } elseif (validarDataDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataCriacaoAte) . ' 23:59:59'];
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

        $Tipo = new Tipo();
        $Status = new Status();

        $retorno = [];
        foreach ($solicitacoes as $solicitacao) {
            $retorno[] = [
                'id' => $solicitacao->cod,
                'parceiro' => $solicitacao->titulo,
                'tipo' => $Tipo->indice($solicitacao->tipo),
                'status' => $Status->indice($solicitacao->status),
                'data_criacao' => dataHoraBr($solicitacao->data_criacao)
            ];
        }
        return $retorno;
    }

    /**
     * @param string $codigo
     *
     * @return bool Caso exista solicitação retorna TRUE. Do contrário FALSE.
     * @throws Excecao
     */
    public function verificarExisteCodigo(string $codigo): bool
    {
        $solicitacaoCredito = $this
            ->tabela($this->ormTabela)
            ->campo(['uuid', 'codigo'])
            ->where(['codigo', '=', $codigo])
            ->read();
        return !empty($solicitacaoCredito);
    }
}
