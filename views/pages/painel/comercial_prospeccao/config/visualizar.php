<?php

use Helpers\ListaHelper;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use App\Classes\ComercialEmpresa\FinalidadeSecundaria;
use App\Classes\ComercialEmpresa\Origem;
use App\Classes\ComercialEmpresa\CanalPreferencia;
use Modules\Botao;

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

    $Painel->bloco(titulo: 'Dados da pesquisa', callback: function () use ($Painel) {
        $Painel
            ->linha('parceiro_proprio', 'Parceiro próprio')
            ->linha('contratou_concorrente', 'Contratou concorrente')
            ->linha('qual_concorrente', 'Qual concorrente')
            ->linha('origem', 'Origem')
            ->linha('base_usuarios', 'Base de usuários');
    });

    $Painel->bloco(titulo: 'Dados de apresentação', callback: function () use ($Painel) {
        $Painel
            ->linha('canal_preferencia', 'Canal de preferencia')
            ->data('data_apresentacao', 'Data de apresentação')
            ->linha('formato_reuniao', 'Formato da reunião');
    });

    $Painel->div(class: "bloco_standby", id: "asdasd", callback: function () use ($Painel) {
        $Painel->bloco(titulo: 'Standby', callback: function () use ($Painel) {
            $Painel
                ->linha('motivo_standby', 'Motivo do standby')
                ->data('previsao_retorno', 'Previsão de retorno');
        });
    });
});

$Painel
    ->replace('parceiro_proprio', (new Botao())->select())
    ->replace('contratou_concorrente', (new Botao())->select())
    ->replace('canal_preferencia', (new CanalPreferencia())->select())
    ->replace('origem', (new Origem())->select())
    ->replace('finalidade_principal', (new FinalidadePrincipal())->select())
    ->replace('finalidade_secundaria', (new FinalidadeSecundaria())->select())
    ->replace('estado_principal', (new ListaHelper())->estado()->r());

$Painel->js('painel_comercial_prospeccao_visualizar');

return $Painel;
