<?php

use Helpers\ListaHelper;
use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Visualizar('comercial_empresa');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados do cliente', callback: function () use ($Painel) {
        $Painel
            ->linha('titulo', 'Título')
            ->linha('nome_fantasia', 'Nome Fantasia')
            ->linha('razao_social', 'Razão Social')
            ->cnpj('cnpj', 'CNPJ');
    });

    $Painel->bloco(titulo: 'Dados do responsável', callback: function () use ($Painel) {
        $Painel
            ->linha('responsavel_nome', 'Nome')
            ->linha('responsavel_cpf', 'CPF')
            ->linha('responsavel_telefone', 'Telefone')
            ->linha('responsavel_email', 'E-mail');
    });

    $Painel->bloco(titulo: 'Renda do público', callback: function () use ($Painel) {
        $Painel
            ->linha('renda_media', 'Renda média')
            ->linha('valor_pib', 'Valor do PIB');
    });

    $Painel->bloco(titulo: 'Valor do contrato', callback: function () use ($Painel) {
        $Painel
            ->linha('tipo_pagamento', 'Tipo de pagamento')
            ->linha('valor_pago', 'Valor pago');
    });
    $Painel->bloco(titulo: 'Produtos do contrato', callback: function () use ($Painel) {
        $Painel
            ->checked('produto_clube', 'Clube de vantagens')
            ->checked('produto_ios', 'App para IOS')
            ->checked('produto_android', 'App para Android')
            ->checked('produto_site', 'Site pré-moldado');
    });
    $Painel->bloco(titulo: 'Outros dados', callback: function () use ($Painel) {
        $Painel
            ->linha('estado_principal', 'Estado principal')
            ->linha('status', 'Status');
    });
    // $Painel->bloco(titulo: 'Endereço', callback: function () use ($Painel) {
    //     $Painel
    //         ->endereco('comercial_empresa', EnderecoLocal::PRINCIPAL);
    // });
});

$Painel->replace('estado_principal', (new ListaHelper())->uf()->r());
$Painel->replace('status', (new Status())->select());

$Painel->css('painel_comercial_empresa_visualizar');
$Painel->js('painel_comercial_empresa_visualizar');

return $Painel;
