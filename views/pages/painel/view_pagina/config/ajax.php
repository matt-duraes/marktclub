<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->grupo('salvar-html', function () use ($Painel) {
        $Painel
            ->request(App\Classes\View\Lista\Helper::PARAMETROS_LISTAR)
            ->permissao('view_pagina_index')
            ->metodo('post')
            ->rota('/view-html');
    })
    ->grupo('atualizar-html', function () use ($Painel) {
        $Painel
            ->request(App\Classes\View\Lista\Helper::PARAMETROS_LISTAR)
            ->permissao('view_pagina_index')
            ->metodo('put')
            ->rota('/view-html/{id}');
    })
    ->grupo('deletar-html', function () use ($Painel) {
        $Painel
            ->permissao('view_pagina_index')
            ->metodo('delete')
            ->request(['id'])
            ->rota('/view-html/{id}');
    })
    ->grupo('listar-html', function () use ($Painel) {
        $Painel
            ->request(['pagina'])
            ->permissao('view_pagina_index')
            ->metodo('get')
            ->rota('/view-html');
    });
