<?php

use App\Classes\BannerLogin\Ordem;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('banner_login', new Ordem());

$Painel
    ->campo('titulo', 'Titulo', 'normal')
    ->status('status', 'Status', new Status());

return $Painel;
