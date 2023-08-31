<?php

namespace Painel\SolicitacaoChequeBonus\Models;

use stdClass;
use System\Interface\PainelVisualizarRetornoInterface;

final class VisualizarModel implements PainelVisualizarRetornoInterface
{
    public function retorno(stdClass $dado): stdClass
    {
        if (!empty($dado->dependente)) {
            return $dado;
        }
        $dado->dependente = $this->pegarDependenteVazio();
        return $dado;
    }

    private function pegarDependenteVazio(): array
    {
        return [
            'nome'            => '',
            'email_pessoal'   => '',
            'rg'              => '',
            'cpf'             => '',
            'grau_parentesco' => '',
            'data_nascimento' => '',
        ];
    }
}
