<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use App\Models\Api\Analytics\Trait\WhereTrait;

final class UsuarioAcessoModel extends ORM
{
    use WhereTrait;
    protected string $_tabela = TABELA_ANALYTICS_DIA;

    private int $idEmpresa;
    public function __construct()
    {
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        parent::__construct();
    }

    public function acesso(string $de, string $ate)
    {
        $where = $this->pegarWherePadrao($de, $ate);
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

        $lista = $this
            ->campo(['quantidade_total', 'quantidade_unico', 'data_acesso'])
            ->where($where)
            ->read();

        foreach ($lista as $r) {
            $data = dataBr($r->data_acesso);
            $dado[$data]['unico'] = $r->quantidade_unico;
            $dado[$data]['total'] = $r->quantidade_total;
        }

        return array_values($dado);
    }
}
