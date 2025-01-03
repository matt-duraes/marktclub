<?php

$Painel = new PainelConfig\Download(app: 'galapagos_lead');

$Painel
    ->bloco('Dados', function () use ($Painel) {
        $Painel
            ->campo('nome', 'Nome')
            ->campo('email', 'E-mail')
            ->campo('celular', 'Celular')
            ->campo('empresa', 'Empresa')
            ->campo('status', 'Status');
    });

return $Painel;
