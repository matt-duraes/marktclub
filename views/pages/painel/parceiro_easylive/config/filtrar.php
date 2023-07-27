<?php

use App\Classes\StatusGeral\Status;
use App\Classes\ParceiroEasylive\Tipo;

$tipo = (new Tipo())->select('Escolha um tipo');
$status = (new Status())->select('Escolha um status');

$Painel = new PainelConfig\Filtrar('parceiro_easylive');
$Painel
    ->select(name: 'tipo', titulo: 'Tipo', label: 'Tipo', lista: $tipo)
    ->select(name: 'status', titulo: 'Status', label: 'Status', lista: $status);

$Painel->replace('tipo', $tipo);
$Painel->replace('status', $status);
return $Painel;
