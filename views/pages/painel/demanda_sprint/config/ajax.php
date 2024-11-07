<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('demanda_aberta', function () use ($Painel) {
        $Painel
            ->request([])
            ->permissao('demanda_sprint_status')
            ->metodo('get')
            ->rota('/demanda-sprint/aberta');
    })
    ->grupo('status_inicial', function () use ($Painel) {
        $Painel
            ->request(['status', 'texto_inicio'])
            ->permissao('demanda_sprint_status')
            ->metodo('put')
            ->rota('/demanda-sprint/{id}');
    })
    ->grupo('status_andamento', function () use ($Painel) {
        $Painel
            ->request(['status', 'texto_final'])
            ->permissao('demanda_sprint_status')
            ->metodo('put')
            ->rota('/demanda-sprint/{id}');
    });
