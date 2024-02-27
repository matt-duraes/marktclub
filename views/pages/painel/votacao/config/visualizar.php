<?php

$Painel = new PainelConfig\Visualizar('votacao');

$Painel->include('votacao');
$Painel->css('painel_votacao_votacao');
$Painel->js('painel_votacao_votacao');

return $Painel;
