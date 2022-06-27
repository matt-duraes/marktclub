<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use App\Models\Api\Analytics\Trait\WhereTrait;
use App\Models\Api\Analytics\Trait\MontarTrait;

final class DispositivoModel extends ORM
{
    use WhereTrait;
    use MontarTrait;

    protected string $_tabela = 'analytics';

    private int $idEmpresa;
    public function __construct()
    {
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        parent::__construct();
    }

    public function porDispositivo($de, $ate)
    {
        $where = $this->pegarWherePadrao($de, $ate);

        $dado = $this
            ->campoTexto('`dispositivo` as "item", COUNT(*) AS "quantidade"')
            ->where($where)
            ->group('dispositivo')
            ->orderTexto('`quantidade` DESC')
            ->limit(0, 20)
            ->read();

        return $this->montarRelatorioLista($where, $dado);
    }

    public function porNavegador($de, $ate)
    {
        $where = $this->pegarWherePadrao($de, $ate);

        $dado = $this
            ->campoTexto('`browser` as "item", COUNT(*) AS "quantidade"')
            ->where($where)
            ->group('browser')
            ->orderTexto('`quantidade` DESC')
            ->limit(0, 20)
            ->read();

        return $this->montarRelatorioLista($where, $dado);
    }

    public function porOS($de, $ate)
    {
        $where = $this->pegarWherePadrao($de, $ate);

        $dado = $this
            ->campoTexto('`os` as "item", COUNT(*) AS "quantidade"')
            ->where($where)
            ->group('os')
            ->orderTexto('`quantidade` DESC')
            ->limit(0, 20)
            ->read();

        return $this->montarRelatorioLista($where, $dado);
    }
}
