<?php

use PainelConfig\Download;

$Painel = new Download('comercial_contrato');

$Painel
    ->bloco('Dados do Cliente', function () use ($Painel) {
        $Painel
            ->campo('titulo', 'Título')
            ->campo('nome_fantasia', 'Nome Fantasia')
            ->campo('razao_social', 'Razão Social')
            ->campo('cnpj', 'CNPJ');
    })
    ->bloco('Dados do Responsável', function () use ($Painel) {
        $Painel
            ->campo('responsavel_nome', 'Nome')
            ->campo('responsavel_cargo', 'Cargo')
            ->campo('responsavel_cpf', 'CPF')
            ->campo('responsavel_telefone', 'Telefone')
            ->campo('responsavel_email', 'E-mail');
    })
    ->bloco('Dados da Empresa', function () use ($Painel) {
        $Painel
            ->campo('finalidade_principal', 'Finalidade principal')
            ->campo('finalidade_secundaria', 'Finalidade secundária')
            ->campo('estado_principal', 'Estado principal');
    })
    ->bloco('Dados da Pesquisa', function () use ($Painel) {
        $Painel
            ->campo('parceiro_proprio', 'Parceiro próprio')
            ->campo('concorrente_status', 'Contratou concorrente')
            ->campo('concorrente_nome', 'Qual concorrente')
            ->campo('origem', 'Origem')
            ->campo('usuario_possivel', 'Base de usuários');
    })
    ->bloco('Dados de Apresentação', function () use ($Painel) {
        $Painel
            ->campo('contato_preferencial', 'Canal de preferencia')
            ->campo('data_apresentacao', 'Data de apresentação')
            ->campo('formato_reuniao', 'Formato da reunião');
    })
    ->bloco('Standby', function () use ($Painel) {
        $Painel
            ->campo('motivo_standby', 'Motivo do standby')
            ->campo('previsao_retorno', 'Previsão de retorno');
    })
    ->bloco('Motivo de Perder', function () use ($Painel) {
        $Painel->campo('motivo_perdido', 'Motivo de perder');
    });

return $Painel;
