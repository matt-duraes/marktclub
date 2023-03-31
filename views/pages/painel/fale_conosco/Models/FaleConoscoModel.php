<?php

namespace Painel\FaleConosco\Models;

use App\Models\Painel\AppGeral\AppGeralModel;

final class FaleConoscoModel extends AppGeralModel
{
    protected string $ormTabela = TABELA_FALE_CONOSCO;

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
                'nome' => strCaixaAlta($r->nome),
                'email' => strCaixaBaixa($r->email),
                'data' => dataHoraBr(data: $r->data_criacao),
                'status' => painelStatus(
                    valor: $r->status,
                    dado: [
                        1 => ['Novo', 'vermelho'],
                        2 => ['Em andamento', 'azul'],
                        3 => ['Finalizado', 'verde'],
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
        $dado = [
            ['nome', 'like', '%' . $pesquisa . '%'],
            ['email', 'like', '%' . $pesquisa . '%'],
        ];

        $telefone = soNumero($pesquisa);
        if (!empty($telefone)) {
            $dado[] = ['telefone', 'like', '%' . $pesquisa . '%'];
        }

        return $dado;
    }

    protected function montarWhereDoFiltro(array $where): array
    {
        $retorno = [];
        if (!is_array($where) || empty($where)) {
            return $retorno;
        }
        $status = $where['status'] ?? '';
        if (in_array($status, FaleConoscoHelper::STATUS_VALOR_INT)) {
            $retorno[] = ['status', $status];
        }
        $nome = $where['nome'] ?? '';
        if (!empty($nome)) {
            $retorno[] = ['nome', 'like', '%' . $nome . '%'];
        }
        $email = $where['email'] ?? '';
        if (!empty($email)) {
            $retorno[] = ['email', 'like', '%' . $email . '%'];
        }
        $telefone = $where['telefone'] ?? '';
        if (!empty($telefone)) {
            $retorno[] = ['telefone', 'like', '%' . soNumero($telefone) . '%'];
        }
        return $retorno;
    }
}
