<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\ORM;
use stdClass;
use Http\Request;
use Modules\Data;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Ordem;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class VoucherModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;


    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;
    private int $idEmpresa;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarEmpresa('empresa');
        $this->validarRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['cod', 'tipo', 'data_criacao', 'status'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem(new Ordem))
            ->tabela(TABELA_PARCEIRO_NOVO)->join('cod', 'vinculo')->campo(['titulo'])
            ->tabela(TABELA_EMPRESA_NOVO)->join('id', 'empresa')->campo(['nome_fantasia', 'cod'], 'empresa')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }
    private function validarRequest()
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

    protected function montarRetorno(array $dado): array
    {
        if (!$dado) {
            return [];
        }

        $Status = new Status();
        $Tipo = new Tipo();
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->cod,
                'empresa' => [
                    'id' => $r->empresa_cod,
                    'nome_fantasia' => $r->empresa_nome_fantasia
                ],
                'parceiro' => $r->titulo,
                'tipo' => $Tipo->indice($r->tipo),
                'data_criacao' => dataHoraBr($r->data_criacao),
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    protected function pegarWhere(): array
    {
        $where = $this->_wherePadrao;

        $Status = new Status($this->request->status);
        if ($Status->valido()) {
            $where[] = ['status', $Status->numero()];
        }

        $Tipo = new Tipo($this->request->tipo);
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }

        $dataCriacaoDe = $this->request->data_criacao_de;
        if (validarDataDate($dataCriacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataCriacaoDe)];
        }

        $dataCriacaoAte = $this->request->data_criacao_ate;
        if (validarDataDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataCriacaoAte) . ' 23:59:59'];
        }
        return $where;
    }
}
