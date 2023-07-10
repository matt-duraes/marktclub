<?php

namespace App\Models\Api\UsuarioCliente\Relatorio;

use ORM\ORM;

final class StatusModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    private int $idEmpresa;

    public function __construct()
    {
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        parent::__construct();
    }

    public function status()
    {
        $where = [
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2, 3]],
            ['tipo', 'in', [1, 3]]
        ];
        if ($this->idEmpresa == 153) {
            $where[] = ['data_ativacao', 'notnull'];
        }

        $dado = $this
            ->campoTexto('
                COUNT(`id`) AS `total`,
                COUNT(IF(`status` = 1, 1, NULL)) AS `ativo`,
                COUNT(IF(`status` = 2, 1, NULL)) AS `inativo`,
                COUNT(IF(`status` = 3, 1, NULL)) AS `bloqueado`
            ')
            ->where($where)
            ->read();

        if (!array_key_exists(0, $dado)) {
            mensagemStatus(500);
        }

        $total = $dado[0]->total;
        $ativo = $dado[0]->ativo;
        $inativo = $dado[0]->inativo;
        $bloqueado = $dado[0]->bloqueado;

        return [
            'total' => $total,
            'lista' => [
                [
                    'status'      => 'ativo',
                    'total'       => $ativo,
                    'porcentagem' => $ativo == 0 ? 0 : number_format(($ativo * 100) / $total, 2, '.'),
                ],
                [
                    'status'      => 'inativo',
                    'total'       => $inativo,
                    'porcentagem' => $inativo == 0 ? 0 : number_format(($inativo * 100) / $total, 2, '.'),
                ],
                [
                    'status'      => 'bloqueado',
                    'total'       => $bloqueado,
                    'porcentagem' => $bloqueado == 0 ? 0 : number_format(($bloqueado * 100) / $total, 2, '.'),
                ]
            ]
        ];
    }
}
