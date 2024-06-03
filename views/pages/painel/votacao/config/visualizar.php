<?php

$Painel = new PainelConfig\Visualizar(isset($enqueteApp) ? $enqueteApp : 'votacao');

$Painel->include('votacao', app: 'votacao');
$Painel->css('painel_votacao_votacao');
$Painel->js('painel_votacao_votacao');

return $Painel;
