<?php

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroLoja\Status;
use PainelConfig\Visualizar;

$Painel = new Visualizar('parceiro_externo');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Captador', function () use ($Painel) {
        $Painel
            ->vazioBreak('captador', 'Captador não encontrado')
            ->linha('captador->nome', 'Nome')
            ->botao(
                'captador_link',
                'Ver captador',
                link: LINK . '/app/visualizar/usuario-equipe/captador->id',
                permissao: 'parceiro_externo_equipe'
            );
    });

    $Painel->bloco('Parceiro', function () use ($Painel) {
        $Painel
            ->linha('titulo_interno', 'Título')
            ->linha('categoria_principal', 'Categoria')
            ->linha('tipo_indicador', 'Tipo indicador');
    });

    $Painel->bloco('Contato', function () use ($Painel) {
        $Painel
            ->vazioBreak('contato', 'Contato não encontrado')
            ->linha('contato->nome', 'Nome')
            ->linha('contato->email', 'E-mail')
            ->linha('contato->telefone', 'Telefone');
    });

    $Painel->bloco('Outras Informações', function () use ($Painel) {
        $Painel
            ->dataHora('data_criacao', 'Criado em')
            ->dataHora('data_atualizacao', 'Última atualização em')
            ->linha('status', 'Status');
    });
});

$Painel->replace('categoria_principal', (new Categoria())->select());
$Painel->replace('tipo_indicador', (new Indicador())->select());
$Painel->replace('status', (new Status())->select());

return $Painel;
