<?php

use App\Classes\TabelaUsuario\Ordem;
use App\Classes\TabelaUsuario\Status;
use App\Classes\TabelaUsuario\Tipo;

$Painel = new PainelConfig\Index('tabela_historico', new Ordem());

$Painel
    ->campo('usuario.nome', 'Usuário', 'normal')
    ->campo('empresa.nome', 'Empresa', 'normal')
    ->campo('erro', 'Erro', 'normal')
    ->campo('novo', 'Novo', 'normal')
    ->campo('atualizado', 'Atualizado', 'normal')
    ->campo('tipo', 'Tipo', 'normal')
    ->campo('data_criacao', 'Data de criação', 'normal', 'data')
    ->status('status', 'Status', new Status());

$Painel->replace('tipo', (new Tipo())->select());

return $Painel;
