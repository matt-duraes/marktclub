<?php

namespace App\Models\Api\UsuarioCliente\Relatorio;

use ORM\ORM;

final class SituacaoModel extends ORM
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
                COUNT(IF(`situacao` = 1, 1, NULL)) AS `ativo`,
                COUNT(IF(`situacao` = 2, 1, NULL)) AS `aposentado`,
                COUNT(IF(`situacao` = 3, 1, NULL)) AS `pensionista`,
                COUNT(IF(`situacao` = 4, 1, NULL)) AS `cedido`,
                COUNT(IF(`situacao` = 5, 1, NULL)) AS `excedente`,
                COUNT(IF(`situacao` IS NULL OR `situacao` = "", 1, NULL)) AS `sem_dado`
            ')
            ->where($where)
            ->read();

        if (!array_key_exists(0, $dado)) {
            mensagemStatus(500);
        }
        $dado = $dado[0];

        $lista = [];
        if ($dado->ativo > 0) {
            $lista[] = [
                'situacao' => 'Ativo',
                'total' => $dado->ativo,
                'porcentagem' => $dado->ativo == 0 ? 0 : number_format(($dado->ativo * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->aposentado > 0) {
            $lista[] = [
                'situacao' => 'Aposentado',
                'total' => $dado->aposentado,
                'porcentagem' => $dado->aposentado == 0 ? 0 : number_format(($dado->aposentado * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->pensionista > 0) {
            $lista[] = [
                'situacao' => 'Pensionista',
                'total' => $dado->pensionista,
                'porcentagem' => $dado->pensionista == 0 ? 0 : number_format(($dado->pensionista * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->cedido > 0) {
            $lista[] = [
                'situacao' => 'Cedido',
                'total' => $dado->cedido,
                'porcentagem' => $dado->cedido == 0 ? 0 : number_format(($dado->cedido * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->excedente > 0) {
            $lista[] = [
                'situacao' => 'Excedente',
                'total' => $dado->excedente,
                'porcentagem' => $dado->excedente == 0 ? 0 : number_format(($dado->excedente * 100) / $dado->total, 2, '.'),
            ];
        }
        if ($dado->sem_dado > 0) {
            $lista[] = [
                'situacao' => 'Sem dado',
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
