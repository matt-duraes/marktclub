<?php

$Painel = new PainelConfig\Ajax();

return $Painel
    ->request(['usuario'])
    ->permissao('usuario_dependente_index')
    ->scope('usuario_dependente:listar')
    ->metodo('get')
    ->rota('/usuario-dependente');
