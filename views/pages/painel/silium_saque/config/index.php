<?php

use PainelConfig\Index;
use App\Classes\SiliumDeposito\Ordem;
use App\Classes\SiliumDeposito\Status;
use App\Classes\SiliumDeposito\TipoResgate;

$Painel = new Index('silium_saque', new Ordem());

$TipoResgate = new TipoResgate();
$Painel
    ->campo('usuario->nome', 'Usuário', Index::TIPO_NORMAL)
    ->campo('tipo_resgate', 'Tipo de Resgate', Index::TIPO_PEQUENO)
    ->campo('saque->pontuacao', 'Pontuação', Index::TIPO_PEQUENO)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

$Painel->replace('tipo_resgate', $TipoResgate);

return $Painel;
