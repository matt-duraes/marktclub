<?php

namespace App\Models\Api\Cinema;

use Erro\Excecao;
use Modules\Data;
use ORM\Entity;

class CinemaEntity extends Entity
{
    private const LIMITE_RESGATE = 2;

    protected string $ormTabela = TABELA_CINEMA_INGRESSO;

    /**
     * @param  string  $uuidEmpresa
     * @param  string  $uuidUsuario
     *
     * @return array
     * @throws Excecao
     */
    public function consultaSaldo(string $uuidEmpresa, string $uuidUsuario): array
    {
        // phpcs:disable
        $semana = date(
            'Y-m-d',
            strtotime("-" . date("w", strtotime(date('Y-m-d'))) . " days", strtotime(date('Y-m-d')))
        );
        // phpcs:enable

        $resgate = $this
            ->select()
            ->where([
                ['empresa', $uuidEmpresa],
                ['usuario', $uuidUsuario],
                ['data_compra', $semana]
            ])
            ->contar();

        $saldo = self::LIMITE_RESGATE - $resgate;

        return [
            'usuario' => [
                'liberado'  => ($saldo > 0) ? $saldo : 0,
                'utilizado' => $resgate
            ],
            'saldo'   => $this
                ->select()
                ->where([
                    ['status', 1],
                    ['tipo', 1]
                ])
                ->contar()
        ];
    }


    /**
     * @param  string  $uuidEmpresa
     * @param  string  $uuidUsuario
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
