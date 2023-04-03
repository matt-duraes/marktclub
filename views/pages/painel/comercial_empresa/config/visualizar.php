<?php

use App\Classes\ComercialEmpresa\EnderecoLocal;

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
    $Painel->bloco(titulo: 'Endereço', callback: function () use ($Painel) {
        $Painel
            ->endereco('comercial_empresa', EnderecoLocal::PRINCIPAL);
    });
    $Painel->bloco(titulo: 'Endereço Secundario', callback: function () use ($Painel) {
        $Painel
            ->endereco('comercial_empresa', 'secundario');
    });
});

$Painel->css('painel_comercial_empresa_visualizar');
$Painel->js('painel_comercial_empresa_visualizar');

return $Painel;
