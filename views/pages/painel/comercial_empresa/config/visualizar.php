<?php

use Helpers\ListaHelper;
use App\Classes\ComercialEmpresa\Status;

$Painel = new PainelConfig\Visualizar('comercial_empresa');

$Painel->coluna(callback: function () use ($Painel) {
    // $Painel->bloco(titulo: 'Endereço', callback: function () use ($Painel) {
    //     $Painel
    //         ->endereco('comercial_empresa', 'principal');
    // });

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

    $Painel->bloco(titulo: 'Dados da empresa', callback: function () use ($Painel) {
        $Painel
            ->linha('finalidade_principal', 'Finalidade principal')
            ->linha('finalidade_secundaria', 'Finalidade secundária')
            ->linha('cadastro_usuario', 'Quem irá cadastrar?')
            ->linha('estado_principal', 'Estado principal');
    });

    $Painel->bloco(titulo: 'Financeiro', callback: function () use ($Painel) {
        $Painel
            ->linha('tipo_pagamento', 'Tipo de pagamento')
            ->linha('valor_usuario', 'Valor por usuário', vazio: false)
            ->linha('valor_cobranca', 'Valor a cobrar')
            ->data('data_contrato', 'Início do contrato')
            ->linha('prazo_contrato', 'Prazo do contrato')
            ->linha('contrato_renovacao', 'Tipo de renovação');
    });

    $Painel->bloco(titulo: 'Produtos do contrato', callback: function () use ($Painel) {
        $Painel
            ->checked('produto_clube', 'Clube de vantagens')
            ->checked('produto_ios', 'App para IOS')
            ->checked('produto_android', 'App para Android')
            ->checked('produto_webview', 'Site via webview')
            ->checked('produto_site', 'Site pré-moldado')
            ->checked('produto_api', 'API de login');
    });
    $Painel->bloco(titulo: 'Comunicação', callback: function () use ($Painel) {
        $Painel
            ->linha('comunicacao_email', 'E-mail')
            ->linha('comunicacao_whatsapp', 'WhatsApp')
            ->linha('comunicacao_rede_social', 'Rede Social')
        ;
    });
    $Painel->bloco(titulo: 'Outros dados', callback: function () use ($Painel) {
        $Painel
            ->linha('renda_media', 'Renda média')
            ->linha('valor_pib', 'Valor do PIB')
            ->linha('estado_principal', 'Estado principal')
            ->linha('status', 'Status');
    });
});

$Painel->replace('estado_principal', (new ListaHelper())->uf()->r());
$Painel->replace('status', (new Status())->select());

$Painel->css('painel_comercial_empresa_visualizar');
$Painel->js('painel_comercial_empresa_visualizar');

return $Painel;
