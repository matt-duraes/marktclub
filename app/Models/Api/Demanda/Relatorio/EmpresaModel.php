<?php

namespace App\Models\Api\Demanda\Relatorio;

use ORM\ORM;
use App\Models\Api\Demanda\Dado\RelatorioSprintModel;

final class EmpresaModel extends ORM
{
    private array $relatorio = [];

    public function __construct(
        private int $id,
        private RelatorioSprintModel $Relatorio
    ) {
        $this->montarRelatorio();
        $this->pegarQuantidadeDemanda();
    }

    private function montarRelatorio()
    {
        $retorno = [];
        foreach ($this->Relatorio->tarefa as $r) {
            if (!array_key_exists($r['empresa_valor'], $retorno)) {
                $retorno[$r['empresa_valor']] = $this->padrao($r);
            }
            $retorno[$r['empresa_valor']]['quantidade_tarefa']++;
            $retorno[$r['empresa_valor']]['quantidade_ponto'] += $r['dificuldade'];
        }
        $this->relatorio = $retorno;
    }

    private function pegarQuantidadeDemanda()
    {
        foreach ($this->Relatorio->demanda as $r) {
            if (!array_key_exists($r->id_admin_empresa, $this->relatorio)) {
                continue;
            }
            $this->relatorio[$r->id_admin_empresa]['quantidade_demanda']++;
        }
        ppe($this->relatorio);
    }

    private function padrao($r)
    {
        return [
            'id_demanda_sprint'  => $this->id,
            'id_admin_empresa'   => $r['empresa_valor'],
            'empresa_nome'       => $r['empresa_nome'],
            'quantidade_demanda' => 0,
            'quantidade_tarefa'  => 0,
            'quantidade_ponto'   => 0
        ];
    }
}
