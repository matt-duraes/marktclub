<?php

use App\Classes\BannerLogin\Ordem;

$Painel = new PainelConfig\Index('banner_login', new Ordem());

$Painel
    ->campo('titulo', 'Titulo', 'normal');

$Painel->js('painel_banner_login_index');

return $Painel;
