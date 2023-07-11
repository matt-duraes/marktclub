<?php

namespace Painel\Demanda\Models;

use Helpers\ApiHelper;

trait DemandaTrait
{
    private function criarDemanda($titulo, $tipo, $area)
    {
        $Api = new ApiHelper(token: true);
        $this->Demanda = $Api->body([
            'empresa' => $this->empresa,
            'titulo'  => $titulo,
            'tipo'    => $tipo,
            'area'    => $area
        ])->post('/demanda-dado')->object();
    }

    private function verificarSeSalvouDemanda()
    {
        $Demanda = $this->Demanda;
        if (!is_object($Demanda) || !object_key_exists('status', $Demanda)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar sua demanda, por favor, tente novamente.');
        } elseif ($this->Demanda->status != 'sucesso') {
            mensagemErro($Demanda->erro->titulo, $Demanda->erro->mensagem);
        }
    }
}
