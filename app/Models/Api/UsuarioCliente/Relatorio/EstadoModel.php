<?php

namespace App\Models\Api\UsuarioCliente\Relatorio;

use ORM\ORM;
use Helpers\ListaHelper;

final class EstadoModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    private int $idEmpresa;
    public function __construct()
    {
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        parent::__construct();
    }
    public function estado()
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
                `uf`, COUNT(`uf`) AS `total`,
                COUNT(IF(`status` = 1, 1, NULL)) AS `ativo`,
                COUNT(IF(`status` = 2, 1, NULL)) AS `inativo`
            ')
            ->where($where)
            ->group('uf')
            ->order('uf', 'ASC')
            ->read();

        $listaUf = (new ListaHelper())->uf()->r();

        $relatorio = [];
        $relatorio['outro'] = [
            'uf' => 'outro',
            'total' => 0,
            'ativo' => 0,
            'inativo' => 0
        ];
        foreach ($listaUf as $uf) {
            $relatorio[$uf] = [
                'uf' => $uf,
                'total' => 0,
                'ativo' => 0,
                'inativo' => 0
            ];
        }

        $total = 0;
        foreach ($dado as $r) {
            if (empty($r->uf)) {
                $relatorio['outro'] = [
                    'uf' => 'outro',
                    'total' => $r->ativo + $r->inativo,
                    'ativo' => $r->ativo,
                    'inativo' => $r->inativo
                ];
                $total += $r->ativo + $r->inativo;
                continue;
            } elseif (!in_array($r->uf, $listaUf)) {
                continue;
            }
            $total += $r->total;
            $relatorio[$r->uf] = [
                'uf' => $r->uf,
                'total' => $r->total,
                'ativo' => $r->ativo,
                'inativo' => $r->inativo
            ];
        }
        return [
            'total' => $total,
            'lista' => array_values($relatorio)
        ];
    }
}
