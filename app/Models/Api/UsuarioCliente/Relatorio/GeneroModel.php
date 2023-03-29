<?php

namespace App\Models\Api\UsuarioCliente\Relatorio;

use ORM\ORM;

final class GeneroModel extends ORM
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
                COUNT(IF(`sexo` = 1, 1, NULL)) AS `masculino`,
                COUNT(IF(`sexo` = 2, 1, NULL)) AS `feminino`,
                COUNT(IF(`sexo` = 3, 1, NULL)) AS `outro`,
                COUNT(IF(`sexo` = 4, 1, NULL)) AS `nao_informado`,
                COUNT(IF(`sexo` IS NULL, 1, NULL)) AS `sem_dado`
            ')
            ->where($where)
            ->read();

        if (!array_key_exists(0, $dado)) {
            mensagemStatus(500);
        }
        $dado = $dado[0];

        $lista = [];
        if ($dado->masculino > 0) {
            $lista[] = [
                'genero' => 'Masculino',
                'total' => $dado->masculino,
                'porcentagem' => $dado->masculino == 0 ? 0 : number_format(($dado->masculino * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->feminino > 0) {
            $lista[] = [
                'genero' => 'feminino',
                'total' => $dado->feminino,
                'porcentagem' => $dado->feminino == 0 ? 0 : number_format(($dado->feminino * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->outro > 0) {
            $lista[] = [
                'genero' => 'Outro',
                'total' => $dado->outro,
                'porcentagem' => $dado->outro == 0 ? 0 : number_format(($dado->outro * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->nao_informado > 0) {
            $lista[] = [
                'genero' => 'Não informado',
                'total' => $dado->nao_informado,
                'porcentagem' => $dado->nao_informado == 0 ? 0 : number_format(($dado->nao_informado * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->sem_dado > 0) {
            $lista[] = [
                'genero' => 'Sem dado',
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
