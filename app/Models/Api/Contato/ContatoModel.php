<?php

namespace App\Models\Api\Contato;

use App\Classes\Contato\Ordem;
use App\Classes\Contato\Status;
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

class ContatoModel extends ORM implements ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_MENSAGEM_CONTATO_NOVO;

    /**
     * @param  Request  $request
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
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'nome', 'telefone', 'email', 'status', 'mensagem',  'descoberta_site', 'data_criacao'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), false)
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo(['cod', 'nome_fantasia'], 'empresa')
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

        $retorno = [];
        foreach ($dados as $items) {
            $retorno[] = [
                'id'           => $items->uuid,
                'nome'         => $items->nome,
                'telefone'     => $items->telefone,
                'email'        => $items->email,
                'mensagem'     => $items->mensagem,
                'descoberta_site' => $items->descoberta_site,
                'empresa' => [
                    'nome' => $items->empresa_nome_fantasia
                ],
                'status'       => $Status->indice($items->status),
                'data_criacao' => dataHoraBr($items->data_criacao)
            ];
        }
        return $retorno;
    }
}
