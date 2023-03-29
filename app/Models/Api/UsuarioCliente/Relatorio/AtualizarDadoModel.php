<?php

namespace App\Models\Api\UsuarioCliente\Relatorio;

use ORM\ORM;

final class AtualizarDadoModel extends ORM
{
    protected string $_tabela = TABELA_USUARIO_CLIENTE;

    private int $idEmpresa;
    public function __construct()
    {
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        parent::__construct();
    }
    public function pegarRelatorio()
    {
        $data = date('Y-m-d');
        $data3Meses = dataRemover($data, 3, 'meses', 'Y-m-d');
        $data6Meses = dataRemover($data, 6, 'meses', 'Y-m-d');
        $data1Ano = dataRemover($data, 1, 'ano', 'Y-m-d');

        $where3Meses = [
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2]],
            ['data_dado', '!null'],
            ['data_dado', '>=', $data3Meses],
            ['tipo', 'in', [1, 3]]
        ];
        $where6Meses = [
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2]],
            ['data_dado', '!null'],
            ['data_dado', '<', $data3Meses . ' 23:59:59'],
            ['data_dado', '>=', $data6Meses],
            ['tipo', 'in', [1, 3]]
        ];
        $where1Ano = [
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2]],
            ['data_dado', '!null'],
            ['data_dado', '<', $data6Meses . ' 23:59:59'],
            ['data_dado', '>=', $data1Ano],
            ['tipo', 'in', [1, 3]]
        ];
        $whereTotal = [
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2]],
            ['tipo', 'in', [1, 3]]
        ];

        if ($this->idEmpresa == 153) {
            $where3Meses[] = ['data_ativacao', 'notnull'];
            $where6Meses[] = ['data_ativacao', 'notnull'];
            $where1Ano[] = ['data_ativacao', 'notnull'];
            $whereTotal[] = ['data_ativacao', 'notnull'];
        }

        $numero3Meses = $this->contar($where3Meses);
        $numero6Meses = $this->contar($where6Meses);
        $numero1Ano = $this->contar($where1Ano);

        $total = $this->contar($whereTotal);

        $relatorio = [];
        if ($numero3Meses > 0) {
            $relatorio[] = [
                'tempo' => '3 meses',
                'total' => $numero3Meses,
                'porcentagem' => number_format(($numero3Meses * 100) / $total, 2, '.')
            ];
        }
        if ($numero6Meses > 0) {
            $relatorio[] = [
                'tempo' => '6 meses',
                'total' => $numero6Meses,
                'porcentagem' => number_format(($numero6Meses * 100) / $total, 2, '.')
            ];
        }
        if ($numero1Ano > 0) {
            $relatorio[] = [
                'tempo' => '1 ano',
                'total' => $numero1Ano,
                'porcentagem' => number_format(($numero1Ano * 100) / $total, 2, '.')
            ];
        }

        return [
            'total' => $total,
            'lista' => $relatorio
        ];
    }
}
