<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Modules\Data;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\Analytics\Trait\WhereTrait;

final class OsModel extends ORM
{
    use ValidarEmpresaTrait;
    use WhereTrait;
    protected string $_tabela = TABELA_ANALYTICS_OS;

    public function __construct(
        protected Data $de,
        protected Data $ate,
        private ?EmpresaEntity $Empresa = null
    ) {
        parent::__construct();
        $this->validarEmpresa();
    }

    public function listarDado(): array
    {
        $lista = $this
            ->campo(['quantidade', 'os'])
            ->where($this->pegarWherePadrao())
            ->order('quantidade', 'DESC')
            ->limit(0, 20)
            ->read();

        return $this->montarDado($lista);
    }

    private function montarDado($lista)
    {
        $total = 0;
        foreach ($lista as $r) {
            $total += $r->quantidade;
        }

        $dado = [];
        foreach ($lista as $r) {
            if (array_key_exists($r->os, $dado)) {
                $dado[$r->os]['total'] += $r->quantidade;
                $dado[$r->os]['porcentagem'] = porcentagem($dado[$r->os]['total'], $total);
                continue;
            }

            $dado[$r->os] = [
                'os' => $r->os,
                'total' => $r->quantidade,
                'porcentagem' => porcentagem($r->quantidade, $total)
            ];
        }
        return array_values($dado);
    }
}
