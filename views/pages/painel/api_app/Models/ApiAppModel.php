<?php

namespace Painel\ApiApp\Models;

use stdClass;
use App\Models\Painel\AppGeral\AppGeralModel;

final class ApiAppModel extends AppGeralModel
{
    protected string $_tabela = TABELA_API_APP;

    public function listarIndex(
        ?string $pesquisa = null,
        ?array $filtro = null,
        ?string $ordem = null,
        ?int $pagina = 1,
        ?stdClass $config = null
    ) {
        $where = $this->pegarWhere($pesquisa, $filtro);
        $order = $this->pegarOrdem($ordem, $config->ordem->padrao, $config->ordem->lista);
        $order = is_string($order[0]) ? [$order] : $order;
        $pagina = $this->pegarPagina($pagina);
        $dado = $this->campo($config->index->campo)->where(
            where: $where,
            obrigatorio: false
        )->order($order)->pagina(
            pagina: $pagina,
            quantidade: $this->pegarQuantidade()
        )
            ->tabela(TABELA_ADMIN_EMPRESA)->campo(['nome_fantasia'])->join('id', 'id_admin_empresa')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /*
    |--------------------------------------------------------------------------
    | RETORNO DO WHERE
    |--------------------------------------------------------------------------
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
                'nome' => $r->nome,
                'empresa' => $r->empresa,
                'data' => dataBr($r->data_criacao),
                'status' => painelStatus(
                    valor: $r->status,
                    dado: [
                        1 => ['Liberado', 'verde'],
                        '' => ['Inativo', 'vermelho']
                    ]
                )
            ];
        }
        return $dado;
    }

    /*
    |--------------------------------------------------------------------------
    | MONTAR WHERE
    |--------------------------------------------------------------------------
    */
    protected function montarWhereDaPesquisa(string $pesquisa): array
    {
        return ['nome', 'like', '%' . $pesquisa . '%'];
    }

    protected function montarWhereDoFiltro(array $where): array
    {
        if (!is_array($where)) {
            return [];
        }
        $retorno = [];

        $status = $where['status'] ?? '';
        if ($status == 1) {
            $retorno[] = ['status', 1];
        } elseif ($status == 2) {
            $retorno[] = ['status', 'null'];
        }

        $nome = $where['nome'] ?? '';
        if (!empty($titulo)) {
            $retorno[] = ['nome_app', 'like', '%' . $nome . '%'];
        }

        $empresa = $where['empresa'] ?? '';
        if (!empty($titulo)) {
            $retorno[] = ['id_admin_empresa', $empresa];
        }

        return $retorno;
    }
}
