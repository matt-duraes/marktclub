<?php

use App\Classes\SolicitacaoChequeBonus\Ordem;
use App\Classes\Solicitacao\Status;
use App\Classes\UsuarioCliente\TipoUsuario;

$Painel = new PainelConfig\Index('solicitacao_cheque_bonus', new Ordem());

$Painel
    ->campo('nome', 'Nome', 'normal')
    ->campo('tipo_usuario', 'Tipo de usuário', 'pequeno')
    ->dataCriacao()
    ->status('status', 'Status', new Status());

$Painel->replace('tipo_usuario', (new TipoUsuario())->select());

return $Painel;
