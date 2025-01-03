<?php

$Painel = new PainelConfig\Ajax();

$Painel
    ->grupo('nomeIndice', function () use ($Painel) {
        $Painel
            ->request([])
            ->permissao('permissao_painel')
            ->metodo('metodo')
            ->rota('uri');
});

return $Painel;
