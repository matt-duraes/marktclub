<?php

namespace Painel\ComercialEmpresa\Models;

use stdClass;
use System\Interface\PainelVisualizarRetornoInterface;

final class VisualizarModel implements PainelVisualizarRetornoInterface
{
    public function retorno(stdClass $dado): stdClass
    {
        $dado->valor_usuario = '';
        $dado->valor_cobranca = '';
        $dado->email_dia = $this->converterDiaSemana($dado->email_dia);
        $dado->whatsapp_dia = $this->converterDiaSemana($dado->whatsapp_dia);
        $dado->rede_social_dia = $this->converterDiaSemana($dado->rede_social_dia);
        return $dado;
    }

    private function converterDiaSemana($dia)
    {
        $replace = [
            'segunda' => 'Segunda',
            'terça'   => 'Terça',
            'quarta'  => 'Quarta',
            'quinta'  => 'Quinta',
            'sexta'   => 'Sexta',
            'feira'   => 'Feira',
        ];
        return str_replace(array_keys($replace), array_values($replace), strImplodeVirgula($dia));
    }
}
