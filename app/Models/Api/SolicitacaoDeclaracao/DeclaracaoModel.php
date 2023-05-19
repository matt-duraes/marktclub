<?php

namespace App\Models\Api\SolicitacaoDeclaracao;

use App\Classes\SolicitacaoDeclaracao\Ordem;
use App\Classes\SolicitacaoDeclaracao\Status;
use App\Classes\SolicitacaoDeclaracao\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
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
     * @param  Request  $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected Request $request
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
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['cod', 'tipo', 'status', 'data_criacao'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela('parceiro_novo')
            ->join('cod', 'vinculo')
            ->campo(['titulo'])
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

        $Status = new Status($this->request->get('status'));
        if ($Status->valido()) {
            $where[] = ['status', $Status->numero()];
        }

        $Tipo = new Tipo($this->request->get('tipo'));
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
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
     * @param  array  $dados
     *
     * @return array
     */
    protected function montarRetorno(array $dados): array
    {
        if (empty($dados)) {
            return [];
        }

        $Status = new Status();
        $Tipo = new Tipo();

        $retorno = [];
        foreach ($dados as $items) {
            $retorno[] = [
                'id'           => $items->cod,
                'parceiro'     => $items->titulo,
                'tipo'         => $Tipo->indice($items->tipo),
                'status'       => $Status->indice($items->status),
                'data_criacao' => dataHoraBr($items->data_criacao)
            ];
        }
        return $retorno;
    }
}
