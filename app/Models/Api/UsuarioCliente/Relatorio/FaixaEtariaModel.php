<?php

namespace App\Models\Api\UsuarioCliente\Relatorio;

use ORM\ORM;

final class FaixaEtariaModel extends ORM
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

        $where = [
            ['status', 'in', [1, 2]],
            ['empresa', $this->idEmpresa],
            ['tipo', 'in', [1, 3]]
        ];
        if ($this->idEmpresa == 153) {
            $where[] = ['data_ativacao', 'notnull'];
        }

        $dado = $this->campo(['aniversario'])->where($where)->read();

        $total = 0;
        $semDado = 0;
        $ate20 = 0;
        $ate30 = 0;
        $ate40 = 0;
        $ate50 = 0;
        $ate60 = 0;
        $mais60 = 0;

        foreach ($dado as $r) {
            $total++;
            if (empty($r->aniversario)) {
                $semDado++;
                continue;
            }

            $idade = dataIdade($r->aniversario);
            if ($idade <= 20) {
                $ate20++;
            } else if ($idade <= 30) {
                $ate30++;
            } else if ($idade <= 40) {
                $ate40++;
            } else if ($idade <= 50) {
                $ate50++;
            } else if ($idade <= 60) {
                $ate60++;
            } else if ($idade > 60) {
                $mais60++;
            }
        }
        return [
            'total' => $total,
            'lista' => [
                [
                    'faixa' => 'Sem dados',
                    'total' => $semDado,
                    'porcentagem' => $semDado == 0 ? 0 : number_format(($semDado * 100) / $total, 2, '.')
                ],
                [
                    'faixa' => 'Até 20 anos',
                    'total' => $ate20,
                    'porcentagem' => $ate20 == 0 ? 0 : number_format(($ate20 * 100) / $total, 2, '.')
                ],
                [
                    'faixa' => 'Até 30 anos',
                    'total' => $ate30,
                    'porcentagem' => $ate30 == 0 ? 0 : number_format(($ate30 * 100) / $total, 2, '.')
                ],
                [
                    'faixa' => 'Até 40 anos',
                    'total' => $ate40,
                    'porcentagem' => $ate40 == 0 ? 0 : number_format(($ate40 * 100) / $total, 2, '.')
                ],
                [
                    'faixa' => 'Até 50 anos',
                    'total' => $ate50,
                    'porcentagem' => $ate50 == 0 ? 0 : number_format(($ate50 * 100) / $total, 2, '.')
                ],
                [
                    'faixa' => 'Até 60 anos',
                    'total' => $ate60,
                    'porcentagem' => $ate60 == 0 ? 0 : number_format(($ate60 * 100) / $total, 2, '.')
                ],
                [
                    'faixa' => 'Mais de 60 anos',
                    'total' => $mais60,
                    'porcentagem' => $mais60 == 0 ? 0 : number_format(($mais60 * 100) / $total, 2, '.')
                ],
            ]
        ];
    }
}
