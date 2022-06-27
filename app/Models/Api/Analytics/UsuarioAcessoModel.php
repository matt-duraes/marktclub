<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use App\Models\Api\Analytics\Trait\WhereTrait;

final class UsuarioAcessoModel extends ORM
{
    use WhereTrait;
    protected string $_tabela = 'analytics';

    private int $idEmpresa;
    public function __construct()
    {
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        parent::__construct();
    }

    public function acesso(string $de, string $ate)
    {
        $where = $this->pegarWherePadrao($de, $ate);
        $buscaGeral = $this
            ->campoTexto('DATE(`data_criacao`) as "data", COUNT(*) AS "quantidade"')
            ->where($where)
            ->groupTexto('DATE(`data_criacao`)')
            ->orderTexto('`data` ASC')
            ->read();

        $buscaUnica = $this
            ->selectTexto('
                SELECT COUNT(`data_criacao`) as `quantidade`, `data_criacao` as `data`
                FROM (
                    SELECT DISTINCT `usuario`, DATE(`data_criacao`) as `data_criacao`, `empresa` FROM `analytics`
                ) as analytics
            ')
            ->where($where)
            ->group('data_criacao')
            ->orderTexto('`data_criacao` ASC')
            ->read();

        $de = dataBr($de);
        $ate = dataBr($ate);

        $dado = [
            $de => [
                'total' => 0,
                'unico' => 0
            ]
        ];

        for ($i = 0; $i < 366; $i++) {
            $data = dataAdicionar($de, $i, 'dia', 'd/m/Y');
            $dado[$data] = [
                'data' => $data,
                'total' => 0,
                'unico' => 0,
            ];
            if ($data == $ate) {
                break;
            }
        }

        foreach ($buscaGeral as $r) {
            $data = dataBr($r->data);
            $dado[$data]['total'] = $r->quantidade;
        }
        foreach ($buscaUnica as $r) {
            $data = dataBr($r->data);
            $dado[$data]['unico'] = $r->quantidade;
        }

        return array_values($dado);
    }
}
