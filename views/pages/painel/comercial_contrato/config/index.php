<?php

use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\Status;
use PainelConfig\Index;

$Painel = new Index('comercial_contrato', new Ordem());

$Painel
    ->campo('titulo', 'Titulo', Index::TIPO_GRANDE)
    ->campo('empresa_nome', 'Empresa Indicação (Usuário Indicou)', Index::TIPO_GRANDE)
    ->campo('equipe_nome', 'Gestor do Contrato', Index::TIPO_GRANDE)
    ->dataCriacao()
    ->status('status', 'Status', new Status());

return $Painel;
