<?php

$Painel = new PainelConfig\Ajax();

$Painel
    ->grupo('traduzir', function () use ($Painel) {
        $Painel
            ->request(['texto'])
            ->permissao('painel_tradutor_add')
            ->metodo('post')
            ->rota('/traduzir');
    });

return $Painel;
