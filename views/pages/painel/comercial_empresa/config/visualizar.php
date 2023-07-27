<?php

use Helpers\ListaHelper;
use App\Classes\ComercialEmpresa\Status;
use App\Classes\ComercialEmpresa\ContratoPrazo;
use App\Classes\ComercialEmpresa\TipoPagamento;
use App\Classes\ComercialEmpresa\CadastroUsuario;
use App\Classes\ComercialEmpresa\ContratoRenovacao;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use App\Classes\ComercialEmpresa\FinalidadeSecundaria;

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
            ->dinheiro('valor_usuario', 'Valor por usuário', vazio: false)
            ->dinheiro('valor_pago', 'Última fatura')
            ->data('contrato_data', 'Data do contrato')
            ->linha('contrato_prazo', 'Prazo do contrato')
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
            ->checked('comunicacao_email', 'E-mail')
            ->linha('email_dia', 'Dias para disparo')
            ->checked('comunicacao_whatsapp', 'WhatsApp')
            ->linha('whatsapp_dia', 'Dias para disparo')
            ->checked('comunicacao_rede_social', 'Rede Social')
            ->linha('rede_social_dia', 'Dias para disparo')
        ;
    });
    $Painel->bloco(titulo: 'Outros dados', callback: function () use ($Painel) {
        $Painel
            ->dinheiro('renda_media', 'Renda média')
            ->dinheiro('valor_pib', 'Valor do PIB')
            ->linha('status', 'Status');
    });
});

$Painel
    ->replace('finalidade_principal', (new FinalidadePrincipal())->select())
    ->replace('finalidade_secundaria', (new FinalidadeSecundaria())->select())
    ->replace('cadastro_usuario', (new CadastroUsuario())->select())
    ->replace('estado_principal', (new ListaHelper())->estado()->r())
    ->replace('contrato_prazo', (new ContratoPrazo())->select())
    ->replace('tipo_pagamento', (new TipoPagamento())->select())
    ->replace('contrato_renovacao', (new ContratoRenovacao())->select())
    ->replace('status', (new Status())->select());

$Painel->css('painel_comercial_empresa_visualizar');
$Painel->js('painel_comercial_empresa_visualizar');

return $Painel;
