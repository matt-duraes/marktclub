<?php

namespace App\Models\Api\SolicitacaoCredito;

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use ORM\ORM;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class CreditoModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_CREDITO;

    /**
     * @param  Request|null  $request
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $dataCriacaoDe = new Data($this->request->data_criacao_de ?? '');
        if (!$dataCriacaoDe->vazio() && (!$dataCriacaoDe->valido() || !$dataCriacaoDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação de início não está no formato válido.');
        }
        $dataCriacaoAte = new Data($this->request->data_criacao_ate ?? '');
        if (!$dataCriacaoAte->vazio() && (!$dataCriacaoAte->valido() || !$dataCriacaoAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação final não está no formato válido.');
        }
        $Tipo = new Tipo($this->request->tipo ?? '');
        if (!$Tipo->vazio() && !$Tipo->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo informado não é válido.');
        }
        $Status = new Status($this->request->status ?? '');
        if (!$Status->vazio() && !$Status->valido()) {
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
                'uuid', 'codigo', 'operadora', 'tipo', 'valor', 'parcelas',
                'valor_parcelas', 'observacao', 'status', 'data_criacao'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), false)
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

        $Tipo = new Tipo($this->request->tipo ?? '');
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }

        $Status = new Status($this->request->status ?? '');
        if ($Status->valido()) {
            $where[] = ['status', $Status->numero()];
        }

        $dataCriacaoDe = $this->request->data_criacao_de ?? '';
        $dataCriacaoAte = $this->request->data_criacao_ate ?? '';

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
     * @param  array  $solicitacoes
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
                'uuid'           => $solicitacao->uuid,
                'codigo'         => $solicitacao->codigo,
                'operadora'      => $Operadora->indice($solicitacao->operadora),
                'tipo'           => $Tipo->indice($solicitacao->tipo),
                'valor'          => $solicitacao->valor,
                'parcelas'       => $solicitacao->parcelas,
                'valor_parcelas' => $solicitacao->valor_parcelas,
                'observacao'     => $solicitacao->observacao,
                'status'         => $Status->indice($solicitacao->status),
                'data_criacao'   => dataHoraBr($solicitacao->data_criacao)
            ];
        }
        return $retorno;
    }

    /**
     * @param  string  $codigo
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
