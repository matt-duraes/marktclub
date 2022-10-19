<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use App\Models\Api\Analytics\Trait\WhereTrait;
use App\Models\Api\Analytics\Trait\MontarTrait;
use App\Models\Api\ParceiroAcessado\AcessoEntity;

final class MaisAcessadoModel extends ORM
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

    public function paginaMaisAcessada($de, $ate)
    {
        $where = $this->pegarWherePadrao($de, $ate);
        $dado = $this
            ->campoTexto('`url` as "item", COUNT(*) AS "quantidade"')
            ->where($where)
            ->group('url')
            ->orderTexto('`quantidade` DESC')
            ->limit(0, 20)
            ->read();

        return $this->montarRelatorioLista($where, $dado);
    }

    public function parceiroMaisAcessada($de, $ate)
    {
        $where = $this->pegarWherePadrao($de, $ate);
        $where[] = ['vinculo', 'notnull'];
        $where[] = ['url', 'like', '/convenios/%'];

        $dado = $this
            ->campoTexto('`analytics`.`vinculo`, COUNT(*) AS "quantidade"')
            ->where($where)
            ->group('vinculo')
            ->orderTexto('`quantidade` DESC')
            ->tabela('parceiro_novo')
            ->campoTexto('`parceiro_novo`.`titulo` as "item"')
            ->innerJoin('id', 'vinculo')
            ->limit(0, 20)
            ->read();

        $this->salvarParceiroMaisAcessado($dado);

        return $this->montarRelatorioLista($where, $dado);
    }
    private function salvarParceiroMaisAcessado($dado)
    {
        if (empty($dado)) {
            return;
        }
        $id = [];
        foreach ($dado as $r) {
            $id[] = $r->vinculo;
        }

        // try {
        $MaisAcessado = new AcessoEntity($id);
        $MaisAcessado->salvar();
        // } catch (\Throwable) {
        // }
    }

    public function usuarioComMaisAcesso($de, $ate)
    {
        $where = $this->pegarWherePadrao($de, $ate);
        $dado = $this
            ->campoTexto('`analytics`.`usuario`, COUNT(*) AS "quantidade"')
            ->where($where)
            ->group('usuario')
            ->orderTexto('`quantidade` DESC')
            ->tabela('usuario_novo')
            ->innerJoin('id', 'usuario')
            ->campoTexto('`usuario_novo`.`nome` as "item"')
            ->limit(0, 20)
            ->read();

        return $this->montarRelatorioLista($where, $dado);
    }
}
