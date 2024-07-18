<?php

use App\Classes\SiliumSaldo\Ordem;
use PainelConfig\Index;

$Painel = new Index('silium_saldo', new Ordem());

$Painel
    ->campo('usuario->nome', 'Usuário', Index::TIPO_NORMAL)
    ->campo('pontuacao', 'Pontuação', Index::TIPO_PEQUENO)
    ->dataCriacao()
    ->dataAtualizacao();

return $Painel;
