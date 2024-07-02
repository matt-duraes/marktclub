<?php

namespace Painel\Demanda\Models;

use Helpers\ApiHelper;

trait DemandaTrait
{
    private function criarDemanda($titulo, $texto, $tipo, $area, $dataEntrega = null)
    {
        $Api = new ApiHelper(token: true);
        $this->Demanda = $Api->body([
            'empresa'      => $this->empresa,
            'titulo'       => $titulo,
            'texto'        => $texto,
            'tipo'         => $tipo,
            'area'         => $area,
            'data_entrega' => $dataEntrega,
            'com_prazo'    => empty($dataEntrega) ? '' : 'sim'
        ])->post('/demanda-dado')->object();
    }

    private function verificarSeSalvouDemanda()
    {
        $Demanda = $this->Demanda;
        if (!is_object($Demanda) || !object_key_exists('status', $Demanda)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar sua demanda, por favor, tente novamente.');
        } elseif ($Demanda->status != 'sucesso') {
            mensagemErro($Demanda->erro->titulo ?? 'Erro', $Demanda->erro->mensagem);
        }
    }
}
