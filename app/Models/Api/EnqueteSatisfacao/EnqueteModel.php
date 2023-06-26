<?php

namespace App\Models\Api\EnqueteSatisfacao;

use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Suporte;
use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Ordem;
use App\Classes\EnqueteSatisfacao\Status;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use ORM\ORM;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class EnqueteModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_ENQUETE;

    /**
     * @param  Request|null  $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected Request $request
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
        $dataCriacaoDe = new Data($this->request->data_criacao_de);
        if (!$dataCriacaoDe->vazio() && (!$dataCriacaoDe->valido() || !$dataCriacaoDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação de início não está no formato válido.');
        }
        $dataCriacaoAte = new Data($this->request->data_criacao_ate);
        if (!$dataCriacaoAte->vazio() && (!$dataCriacaoAte->valido() || !$dataCriacaoAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação final não está no formato válido.');
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
     * @return object
     * @throws Excecao
     */
    public function listarDados(): object
    {
        $dado = $this
            ->campo([
                'uuid', 'navegar', 'procura', 'suporte', 'comentario',
                'atendimento', 'sistemas', 'status', 'data_criacao'
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
    protected function montarRetorno(array $respostas): array
    {
        if (empty($respostas)) {
            return $respostas;
        }

        $Suporte = new Suporte();
        $Navegar = new Navegar();
        $Procura = new Procura();
        $Atendimento = new Atendimento();
        $Status = new Status();

        $retorno = [];
        foreach ($respostas as $item) {
            $retorno[] = [
                'uuid'           => $item->uuid,
                'codigo'         => $Navegar->indice($item->navegar),
                'suporte'        => $Suporte->indice($item->suporte),
                'atendimento'    => $Atendimento->indice($item->atendimento),
                'procura'        => $Procura->indice($item->procura),
                'sistemas'       => $item->sistemas,
                'comentario'     => $item->comentario,
                'status'         => $Status->indice($item->status),
                'data_criacao'   => dataHoraBr($item->data_criacao)
            ];
        }
        return $retorno;
    }
}
