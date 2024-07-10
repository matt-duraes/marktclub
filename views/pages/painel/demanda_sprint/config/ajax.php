<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('status_inicial', function () use ($Painel) {
        $Painel
            ->request(['status', 'texto_inicio'])
            ->permissao('demanda_sprint_editar')
            ->metodo('put')
            ->rota('/demanda-sprint/{id}');
    })
    ->grupo('status_andamento', function () use ($Painel) {
        $Painel
            ->request(['status', 'texto_final'])
            ->permissao('demanda_sprint_editar')
            ->metodo('put')
            ->rota('/demanda-sprint/{id}');
    });
