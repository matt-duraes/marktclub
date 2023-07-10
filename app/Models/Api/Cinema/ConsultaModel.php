<?php

namespace App\Models\Api\Cinema;

use Erro\Excecao;
use ORM\Entity;

class ConsultaModel extends Entity
{
    private const LIMITE_RESGATE = 2;

    protected string $ormTabela = TABELA_CINEMA_INGRESSO;

    /**
     * @param string $uuidEmpresa
     * @param string $uuidUsuario
     *
     * @return array
     * @throws Excecao
     */
    public function consultaSaldo(string $uuidEmpresa, string $uuidUsuario): array
    {
        // phpcs:disable
        $semana = date(
            'Y-m-d',
            strtotime('-' . date('w', strtotime(date('Y-m-d'))) . ' days', strtotime(date('Y-m-d')))
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
}
