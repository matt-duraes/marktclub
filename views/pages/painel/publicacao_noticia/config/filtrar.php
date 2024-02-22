<?php

use App\Classes\Geral\Status;

$Painel = new PainelConfig\Filtrar('publicacao_noticia');

$Painel
    ->input(name: 'pesquisa', label: 'Pesquisa', placeholder: 'Digite sua pesquisa')
    ->switch(name: 'home', label: 'Aparecer na home?')
    ->switch(name: 'site', label: 'Aparecer no site?')
    ->switch(name: 'restrita', label: 'Aparecer na área restrita')
    ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'));

$Painel->replace('status', (new Status())->select());

return $Painel;
