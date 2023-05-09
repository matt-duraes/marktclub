<?php

namespace App\Models\Api\SolicitacaoDeclaracao;

use App\Classes\SolicitacaoDeclaracao\Ordem;
use App\Classes\SolicitacaoDeclaracao\Status;
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
            ->campo(['cod', 'tipo', 'data_criacao', 'status'])
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

        /*$Tipo = new Tipo($this->request->get('tipo'));
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }*/

        $dataCriacaoDe = $this->request->get('data_criacao_de');
        $dataCriacaoAte = $this->request->get('data_criacao_ate');

        if (validarDataDate($dataCriacaoDe) && validarDataDate($dataCriacaoAte)) {
            $where[] = [
                'data_criacao',
                'between',
                [$dataCriacaoDe, $dataCriacaoAte]
            ];
        } else {
            if (validarDataDate($dataCriacaoDe)) {
                $where[] = ['data_criacao', '>=', dataBanco($dataCriacaoDe)];
            } else {
                if (validarDataDate($dataCriacaoAte)) {
                    $where[] = ['data_criacao', '<=', dataBanco($dataCriacaoAte) . ' 23:59:59'];
                }
            }
        }

        return $where;
    }

    /**
     * @param  array  $dado
     *
     * @return array
     */
    protected function montarRetorno(array $dado): array
    {
        if (empty($dado)) {
            return [];
        }

        $Status = new Status();
        //$Tipo = new Tipo();
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'           => $r->cod,
                'parceiro'     => $r->titulo,
                //'tipo'       => $Tipo->indice($r->tipo),
                'data_criacao' => dataHoraBr($r->data_criacao),
                'status'       => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
