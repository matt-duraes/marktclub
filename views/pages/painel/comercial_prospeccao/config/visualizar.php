<?php

use Helpers\ListaHelper;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use App\Classes\ComercialEmpresa\FinalidadeSecundaria;

$Painel = new PainelConfig\Visualizar('comercial_prospeccao');

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
            ->linha('responsavel_cargo', 'Cargo')
            ->linha('responsavel_cpf', 'CPF')
            ->linha('responsavel_telefone', 'Telefone')
            ->linha('responsavel_email', 'E-mail');
    });

    $Painel->bloco(titulo: 'Dados da empresa', callback: function () use ($Painel) {
        $Painel
            ->linha('finalidade_principal', 'Finalidade principal')
            ->linha('finalidade_secundaria', 'Finalidade secundária')
            ->linha('estado_principal', 'Estado principal');
    });
});

$Painel
    ->replace('finalidade_principal', (new FinalidadePrincipal())->select())
    ->replace('finalidade_secundaria', (new FinalidadeSecundaria())->select())
    ->replace('estado_principal', (new ListaHelper())->estado()->r());

$Painel->css('painel_comercial_empresa_visualizar');
$Painel->js('painel_comercial_empresa_visualizar');

return $Painel;
