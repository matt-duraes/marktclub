<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use Modules\Data;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Analytics\Trait\WhereTrait;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class DispositivoModel extends ORM
{
    use ValidarEmpresaTrait;
    use WhereTrait;

    protected string $ormTabela = TABELA_ANALYTICS_DISPOSITIVO;

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
            ->campo(['quantidade', 'dispositivo'])
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
            if (array_key_exists($r->dispositivo, $dado)) {
                $dado[$r->dispositivo]['total'] += $r->quantidade;
                $dado[$r->dispositivo]['porcentagem'] = porcentagem($dado[$r->dispositivo]['total'], $total);
                continue;
            }
            $dado[$r->dispositivo] = [
                'dispositivo' => $r->dispositivo,
                'total'       => $r->quantidade,
                'porcentagem' => porcentagem($r->quantidade, $total)
            ];
        }
        return array_values($dado);
    }
}
