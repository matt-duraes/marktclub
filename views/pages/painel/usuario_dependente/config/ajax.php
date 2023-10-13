<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->request(['usuario'])
    ->permissao('usuario_dependente_index')
    ->metodo('get')
    ->rota('/usuario-dependente');
