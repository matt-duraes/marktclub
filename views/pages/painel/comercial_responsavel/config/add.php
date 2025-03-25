<?php

use App\Classes\Geral\Status;
use App\Classes\UsuarioEquipe\Tipo;
use Helpers\ApiHelper;
use PainelConfig\Add;

$Painel = new Add(app: 'comercial_responsavel', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do Contrato', function () use ($Painel) {
        $Painel
            ->select(
                name: 'equipe',
                lista: 'usuario',
                label: 'Responsável pelo Contrato',
                tipoEquipe: Tipo::COMERCIAL
            );
    });
});

return $Painel;
