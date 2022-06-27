<?php

namespace App\Models\Api\UsuarioPagamento;

use stdClass;
use Http\Request;
use App\Models\Api\GeralModel;
use App\Classes\UsuarioPagamento\Status;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class PagamentoModel extends GeralModel
{
    protected string $_tabela = TABELA_USUARIO_PAGAMENTO;

    protected Status $status;

    public function __construct(
        protected ?Request $request = null
    ) {
        parent::__construct();
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR PAGAMENTO
    |--------------------------------------------------------------------------
    */
    public function buscarPagamento($id)
    {
        $Cliente = new ClienteEntity();
        $Cliente->buscar(['cod', $id]);
        $idUsuario = $Cliente->get('id');

        $dado = $this
            ->campo([
                'uuid', 'data_cobranca', 'valor_debito',
            ])->where([
                ['id_usuario_cliente', $idUsuario],
                ['status', 1]
            ])
            ->order('id', 'ASC')
            ->read();

        return $this->montarRetornoBusca($dado);
    }

    private function montarRetornoBusca($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'data' => dataBr($r->data_cobranca),
                'valor' => number_format($r->valor_debito, 2, ',', '.')
            ];
        }
        return $retorno;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR DADOS
    |--------------------------------------------------------------------------
    */
    public function listarDados(): stdClass
    {

        $dado = $this
            ->campo([
                'data_cobranca', 'status'
            ])->where($this->pegarWherePagamento())
            ->group('id_usuario_cliente')
            ->order('id', 'ASC')
            // Usuario
            ->tabela(TABELA_USUARIO_NOVO)->join('id', 'id_usuario_cliente')
            ->campo([
                'cod', 'nome', 'documento'
            ])
            ->where($this->pegarWhereUsuario(), false)
            ->pagina($this->pegarPagina(), 50)
            ->read();

        if (existeErro($dado, 'lista')) {
            mensagemStatus(500, 'Ocorreu um erro ao buscar lista.');
        }

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno(array $dado): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->cod,
                'nome' => $r->nome,
                'cpf' => strCpf($r->documento),
                'data' => dataBr($r->data_cobranca),
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarWherePagamento(): array
    {
        $where = [['id_admin_empresa', $this->idEmpresa]];

        $status = new Status($this->request->status);
        if (!empty($status) && $status->valido()) {
            $where[] = ['status', $status->numero()];
        }

        $dataCobrancaDe = $this->request->data_cobranca_de;
        if (!empty($dataCobrancaDe)) {
            $where[] = ['data_cobranca', '>=', dataBanco($dataCobrancaDe)];
        }

        $dataCobrancaAte = $this->request->data_cobranca_ate;
        if (!empty($dataCobrancaAte)) {
            $where[] = ['data_cobranca', '<=', dataBanco($dataCobrancaAte)];
        }

        $dataPagamentoDe = $this->request->data_pagamento_de;
        if (!empty($dataPagamentoDe)) {
            $where[] = ['data_pagamento', '>=', dataBanco($dataPagamentoDe)];
        }

        $dataPagamentoAte = $this->request->data_pagamento_ate;
        if (!empty($dataPagamentoAte)) {
            $where[] = ['data_pagamento', '<=', dataBanco($dataPagamentoAte)];
        }

        return $where;
    }

    private function pegarWhereUsuario(): array
    {
        $where = [];

        $pesquisa = $this->request->pesquisa;
        if (!empty($pesquisa)) {
            $wherePesquisa = [
                'OR',
                ['nome', 'like', '%' . $pesquisa . '%'],
                ['email_trabalho', 'like', $pesquisa . '%'],
                ['email_pessoal', 'like', $pesquisa . '%'],
                ['email_funcional', 'like', $pesquisa . '%'],
            ];

            $pesquisaCpf = preg_replace("/[^0-9]/", "", $pesquisa);
            if (!empty($pesquisaCpf)) {
                $wherePesquisa[] = ['documento', 'like', $pesquisaCpf . '%'];
            }
            $where[] = $wherePesquisa;
        }

        $nome = $this->request->nome;
        if (!empty($nome)) {
            $where[] = ['nome', 'like', $nome . '%'];
        }

        $cpf = !empty($this->request->cpf) ? preg_replace("/[^0-9]/", "", $this->request->cpf) : '';
        if (!empty($cpf)) {
            $where[] = ['documento', $cpf];
        }

        return $where;
    }
}
