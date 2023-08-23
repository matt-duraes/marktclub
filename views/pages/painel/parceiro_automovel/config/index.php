<?php

use Modules\Botao;
use App\Classes\Geral\Status;

$Painel = new PainelConfig\Index('parceiro_automovel');

$Painel
    ->campo('titulo', 'Título', 'grande')
    ->campo('parceiro->titulo', 'Parceiro', 'normal')
    ->campo('publicado', 'Publicado', 'pequeno')
    ->status('status', 'Status', new Status());

$Painel->replace('publicado', new Botao());
return $Painel;
