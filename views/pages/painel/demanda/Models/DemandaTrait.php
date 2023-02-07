<?php

namespace Painel\Demanda\Models;

use Helpers\ApiHelper;

trait DemandaTrait
{
    private function criarDemanda($titulo, $tipo)
    {
        $Api = new ApiHelper(token: true);
        $this->Demanda = $Api->body([
            'empresa' => $this->empresa,
            'titulo' => $titulo,
            'tipo' => $tipo
        ])->post('/demanda-dado')->object();
    }

    private function verificarSeSalvouDemanda()
    {
        $Demanda = $this->Demanda;
        if (!is_object($Demanda) || !object_key_exists('status', $Demanda)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar sua demanda, por favor, tente novamente.');
        } else if ($this->Demanda->status != 'sucesso') {
            mensagemErro($Demanda->erro->titulo, $Demanda->erro->mensagem);
        }
    }
}
