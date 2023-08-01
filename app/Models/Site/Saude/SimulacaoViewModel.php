<?php

namespace App\Models\Site\Saude;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\RegiaoAmil;
use App\Classes\Saude\PlanoAmilBrasilia;
use App\Classes\Saude\PlanoAmilSaoPaulo;

final class SimulacaoViewModel
{
    public function __construct(
        private readonly string $operadora
    ) {
    }

    public function opcao()
    {
        if ($this->operadora == Operadora::AMIL) {
            return $this->montarOpcao(['Região', 'Plano', 'Simulação', 'Resultado']);
        }
    }

    private function montarOpcao($lista)
    {
        $i = 1;
        $retorno = [];
        foreach ($lista as $titulo) {
            $retorno[] = (object)[
                'numero' => $i,
                'titulo' => $titulo
            ];
            $i++;
        }
        return $retorno;
    }

    public function plano()
    {
        if ($this->operadora == Operadora::AMIL) {
            return [
                RegiaoAmil::BRASILIA  => (new PlanoAmilBrasilia())->select(),
                RegiaoAmil::SAO_PAULO => (new PlanoAmilSaoPaulo())->select(),
            ];
        }
    }

    public function regiao()
    {
        if ($this->operadora == Operadora::AMIL) {
            return (new RegiaoAmil())->select();
        }
    }

    public function passoPasso()
    {
        if ($this->operadora == Operadora::AMIL) {
            return $this->montarPassoPasso(['regiao', 'plano', 'simulacao', 'resultado']);
        }
    }

    private function montarPassoPasso($lista)
    {
        $retorno = [];
        $i = 1;
        foreach ($lista as $tipo) {
            $retorno[] = (object)[
                'tipo'   => $tipo,
                'numero' => $i
            ];
            $i++;
        }
        return $retorno;
    }
}
