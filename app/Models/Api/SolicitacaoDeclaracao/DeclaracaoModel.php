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
     * @param Request $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected Request $request
    ) {
        $this->validarEmpresa();
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $dataCriacaoDe = new Data($this->request->data_criacao_de);
        if (!$dataCriacaoDe->vazio() && (!$dataCriacaoDe->valido() || !$dataCriacaoDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação de início não está no formato válido.');
        }
        $dataCriacaoAte = new Data($this->request->data_criacao_ate);
        if (!$dataCriacaoAte->vazio() && (!$dataCriacaoAte->valido() || !$dataCriacaoAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação final não está no formato válido.');
        }
        $Tipo = new Tipo($this->request->tipo);
        if (!$Tipo->vazio() && !$Tipo->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo informado não é válido.');
        }
        $Status = new Status($this->request->status);
        if (!$Status->vazio() && !$Status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
        $Ordem = new Ordem($this->request->ordem);
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
            ->campo([
                'uuid', 'tipo', 'status', 'data_criacao'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_PARCEIRO_LOJA)
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

        $Status = new Status($this->request->status);
        if ($Status->valido()) {
            $where[] = ['status', $Status->numero()];
        }

        $Tipo = new Tipo($this->request->tipo);
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }

        $dataCriacaoDe = $this->request->data_criacao_de;
        $dataCriacaoAte = $this->request->data_criacao_ate;

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
     * @param array $declaracoes
     *
     * @return array
     */
    protected function montarRetorno(array $declaracoes): array
    {
        if (empty($declaracoes)) {
            return $declaracoes;
        }

        $Status = new Status();
        $Tipo = new Tipo();

        $retorno = [];
        foreach ($declaracoes as $declaracao) {
            $retorno[] = [
                'uuid'         => $declaracao->uuid,
                'titulo'       => $declaracao->titulo,
                'tipo'         => $Tipo->indice($declaracao->tipo),
                'status'       => $Status->indice($declaracao->status),
                'data_criacao' => dataHoraBr($declaracao->data_criacao)
            ];
        }
        return $retorno;
    }
}
