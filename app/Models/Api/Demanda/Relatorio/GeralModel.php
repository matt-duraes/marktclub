<?php

namespace App\Models\Api\Demanda\Relatorio;

use ORM\ORM;
use App\Models\Api\Demanda\Dado\RelatorioSprintModel;

abstract class GeralModel extends ORM
{
    private array $relatorio = [];
    protected RelatorioSprintModel $Relatorio;
    protected int $id;
    protected string $campoDemanda;
    protected string $campoBanco;
    protected string $campoNome;
    protected bool $temDemanda;

    public function __construct()
    {
        parent::__construct();
        if ($this->existe(['id_demanda_sprint', $this->id])) {
            return;
        }
        $this->montarRelatorio();
        if ($this->temDemanda) {
            $this->pegarQuantidadeDemanda();
        }
        $this->somarDificuldade();
        $this->salvar();
    }

    private function montarRelatorio()
    {
        $retorno = [];
        foreach ($this->Relatorio->tarefa as $r) {
            if (!array_key_exists($r[$this->campoBanco], $retorno)) {
                $retorno[$r[$this->campoBanco]] = $this->padrao($r);
            }
            $retorno[$r[$this->campoBanco]]['quantidade_tarefa']++;
            $retorno[$r[$this->campoBanco]]['quantidade_ponto'] += $r['dificuldade'];
        }
        $this->relatorio = $retorno;
    }

    private function pegarQuantidadeDemanda()
    {
        $this->relatorio = $this->Relatorio->pegarQuantidadeDemanda($this->relatorio, $this->campoDemanda);
    }

    private function padrao($r)
    {
        $dado = [
            'id_demanda_sprint'  => $this->id,
            $this->campoBanco    => $r[$this->campoBanco],
            $this->campoNome     => $r[$this->campoNome],
            'quantidade_tarefa'  => 0,
            'quantidade_ponto'   => 0
        ];
        if ($this->temDemanda) {
            $dado['quantidade_demanda'] = 0;
        }
        return $dado;
    }

    private function somarDificuldade()
    {
        $this->relatorio = $this->Relatorio->somarDificuldade($this->relatorio);
    }

    private function salvar()
    {
        foreach ($this->relatorio as $dado) {
            if(
                (array_key_exists('quantidade_demanda', $dado) && $dado['quantidade_demanda'] == 0) ||
                (array_key_exists('quantidade_tarefa', $dado) && $dado['quantidade_tarefa'] == 0)
            ) {
                continue;
            }
            $this->dado($dado)->insert();
        }
    }
}
