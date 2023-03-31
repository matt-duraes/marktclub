<?php

namespace App\Models\Api\UsuarioCliente\Relatorio;

use ORM\ORM;

final class EstadoCivilModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    private int $idEmpresa;
    public function __construct()
    {
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        parent::__construct();
    }
    public function pegarRelatorio()
    {
        $where = [
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2]],
            ['tipo', 'in', [1, 3]]
        ];
        if ($this->idEmpresa == 153) {
            $where[] = ['data_ativacao', 'notnull'];
        }

        $dado = $this
            ->campoTexto('
                COUNT(`id`) AS `total`,
                COUNT(IF(`estado_civil` = 1, 1, NULL)) AS `solteiro`,
                COUNT(IF(`estado_civil` = 2, 1, NULL)) AS `casado`,
                COUNT(IF(`estado_civil` = 3, 1, NULL)) AS `divorciado`,
                COUNT(IF(`estado_civil` = 5, 1, NULL)) AS `separado`,
                COUNT(IF(`estado_civil` IS NULL OR `estado_civil` = "", 1, NULL)) AS `sem_dado`
            ')
            ->where($where)
            ->read();

        if (!array_key_exists(0, $dado)) {
            mensagemStatus(500);
        }
        $dado = $dado[0];

        $lista = [];
        if ($dado->solteiro > 0) {
            $lista[] = [
                'estado' => 'Solteiro',
                'total' => $dado->solteiro,
                'porcentagem' => $dado->solteiro == 0 ? 0 : number_format(($dado->solteiro * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->casado > 0) {
            $lista[] = [
                'estado' => 'Casado(a)',
                'total' => $dado->casado,
                'porcentagem' => $dado->casado == 0 ? 0 : number_format(($dado->casado * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->divorciado > 0) {
            $lista[] = [
                'estado' => 'Divorciado(a)',
                'total' => $dado->divorciado,
                'porcentagem' => $dado->divorciado == 0 ? 0 : number_format(($dado->divorciado * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->separado > 0) {
            $lista[] = [
                'estado' => 'Separado(a)',
                'total' => $dado->separado,
                'porcentagem' => $dado->separado == 0 ? 0 : number_format(($dado->separado * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->sem_dado > 0) {
            $lista[] = [
                'estado' => 'Sem dado',
                'total' => $dado->sem_dado,
                'porcentagem' => $dado->sem_dado == 0 ? 0 : number_format(($dado->sem_dado * 100) / $dado->total, 2, '.'),
            ];
        }

        return [
            'total' => $dado->total,
            'lista' => $lista
        ];
    }
}
