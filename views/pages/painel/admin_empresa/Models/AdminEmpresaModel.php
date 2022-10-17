<?php

namespace Painel\AdminEmpresa\Models;

use App\Models\Painel\AppGeral\AppGeralModel;

final class AdminEmpresaModel extends AppGeralModel
{
    protected string $_tabela = TABELA_ADMIN_EMPRESA;

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PÚBLICOS
    |--------------------------------------------------------------------------
    */

    /**
     * Pega a lista de prefeituras e retorna no padrão id => Estado-Cidade
     *
     * @param null|string   $titulo     Valor inicial e vazio do select. Ex <option value="">Valor do $titulo</option>
     * @param null|array    $lista      Array com uma lista de itens para ser adicionado no select
     * @return array
     */
    public function pegarPrefeiturasParaSelect(?string $titulo = null, ?array $lista = null): array
    {
        $dado = $this
            ->campo(['id', 'endereco_cidade', 'endereco_estado'])
            ->where(['status', 1])
            ->order([
                ['endereco_estado', 'ASC'],
                ['endereco_cidade', 'ASC']
            ])
            ->read();

        $select = montarSelect(titulo: $titulo, lista: $lista);

        if (!$dado) {
            return [];
        }

        foreach ($dado as $r) {
            $select[$r->id] = $r->endereco_estado . ' - ' . $r->endereco_cidade;
        }

        return $select;
    }
    /**
     * Pega a lista de prefeituras e retorna no padrão id => Estado-Cidade
     *
     * @param null|string   $titulo     Valor inicial e vazio do select. Ex <option value="">Valor do $titulo</option>
     * @param null|array    $lista      Array com uma lista de itens para ser adicionado no select
     * @return array
     */
    public function pegarEmpresaParaSelect(?string $titulo = null, ?array $lista = null): array
    {
        $dado = $this
            ->campo(['id', 'nome_fantasia'])
            ->where(['status', 1])
            ->order('nome_fantasia', 'ASC')
            ->read();

        $select = montarSelect(titulo: $titulo, lista: $lista);

        if (!$dado) {
            return [];
        }

        foreach ($dado as $r) {
            $select[$r->id] = $r->nome_fantasia;
        }

        return $select;
    }

    /**
     * Pega a lista de prefeituras e retorna no padrão id => Estado-Cidade
     *
     * @param null|string   $titulo     Valor inicial e vazio do select. Ex <option value="">Valor do $titulo</option>
     * @param null|array    $lista      Array com uma lista de itens para ser adicionado no select
     * @return array
     */
    public function pegarTituloParaSelect(?string $titulo = null, ?array $lista = null): array
    {
        $dado = $this
            ->campo(['id', 'razao_social'])
            ->where(['status', 1])
            ->order('razao_social', 'ASC')
            ->read();

        $select = montarSelect(titulo: $titulo, lista: $lista);

        if (!$dado) {
            return [];
        }

        foreach ($dado as $r) {
            $select[$r->id] = $r->razao_social;
        }

        return $select;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PADRÕES DO PAINEL
    |--------------------------------------------------------------------------
    */

    /**
     * Monta o retorno
     */
    protected function montarRetorno(array $lista): array
    {
        if (!$lista) {
            return [];
        }
        $dado = [];
        foreach ($lista as $r) {
            $dado[] = (object)[
                'uuid' => $r->uuid,
                'nome' => strCaixaAlta($r->nome_fantasia),
                'cnpj' => strCnpj($r->documento_cnpj),
                'email' => strCaixaBaixa($r->responsavel_email),
                'data' => dataHoraBr(data: $r->data_criacao),
                'status' => painelStatus(
                    valor: $r->status,
                    dado: [
                        1 => ['Liberado', 'verde'],
                        2 => ['Inativo', 'vermelho'],
                        3 => ['Em prospecção', 'azul']
                    ]
                )
            ];
        }
        return $dado;
    }

    /**
     * Monta o where
     */
    protected function montarWhereDaPesquisa(string $pesquisa): array
    {
        $retorno = [
            'OR',
            ['razao_social', 'like', '%' . $pesquisa . '%'],
            ['nome_fantasia', 'like', '%' . $pesquisa . '%'],
            ['responsavel_email', 'like', '%' . $pesquisa . '%'],
        ];
        $cnpj = preg_replace('/[^0-9]/', '', $pesquisa);
        if (!empty($cnpj)) {
            $retorno[] = ['documento_cnpj', 'like', $cnpj . '%'];
        }
        return $retorno;
    }

    protected function montarWhereDoFiltro(array $where): array
    {
        if (!is_array($where)) {
            return [];
        }
        $retorno = [];
        if (in_array($where['status'] ?? '', [1, 2, 3])) {
            $retorno[] = ['status', $where['status']];
        }
        if (!empty($where['item'] ?? '') && is_array($where['item'])) {
            $item = [];
            foreach ($where['item'] as $val) {
                $item[] = ['item_contratado', 'like', '%"' . $val . '"%'];
            }
            $retorno[] = $item;
        }
        if (!empty($where['tag'] ?? '') && is_array($where['tag'])) {
            $item = [];
            foreach ($where['tag'] as $val) {
                $item[] = ['tag', 'like', '%"' . $val . '"%'];
            }
            $retorno[] = $item;
        }
        if (!empty($where['nome'] ?? '')) {
            $nome = $where['nome'];
            $retorno[] = [
                'OR',
                ['razao_social', 'like', '%' . $nome . '%'],
                ['nome_fantasia', 'like', '%' . $nome . '%'],
            ];
        }
        if (!empty($where['cnpj'] ?? '')) {
            $retorno[] = ['documento_cnpj', 'like', preg_replace('/[^0-9]/', '', $where['cnpj']) . '%'];
        }
        return $retorno;
    }
}
