<?php

namespace App\Models\Api\Cinema;

use Erro\Excecao;
use Modules\Data;
use ORM\ORM;

class ExtratoModel extends ORM
{
    protected string $ormTabela = TABELA_CINEMA_INGRESSO;

    /**
     * @param string $uuidEmpresa
     * @param string $uuidUsuario
     *
     * @return array
     * @throws Excecao
     */
    public function extrato(string $uuidEmpresa, string $uuidUsuario): array
    {
        $extrato = $this
            ->select()
            ->campo([
                'cod', 'cupom', 'data_criacao', 'status'
            ])
            ->where([
                ['empresa', $uuidEmpresa],
                ['usuario', $uuidUsuario]
            ])
            ->get();

        $novoExtrato = [];
        foreach ($extrato as $transacao) {
            $novoExtrato[] = [
                'id'      => $transacao->id,
                'produto' => 'Cupom digital para cinema',
                'data'    => [
                    'compra' => (new Data($transacao))->data()
                ],
                'cupom'   => $transacao->status === 3 ? json_decode($transacao->cupom) : ''
            ];
        }

        return $novoExtrato;
    }
}
