<?php

$Painel = new PainelConfig\Download('parceiro_loja');

$Painel
    ->bloco('Dados gerais', function () use ($Painel) {
        $Painel
            ->campo('titulo', 'Título do clube')
            ->campo('titulo_interno', 'Título interno')
            ->campo('razao_social', 'Razão social')
            ->campo('nome_fantasia', 'Nome fantasia')
            ->campo('documento_cnpj', 'CNPJ')
            ->campo('documento_cpf', 'CPF');
    })
    ->bloco('Dados do responsável', function () use ($Painel) {
        $Painel
            ->campo('responsavel_nome', 'Nome')
            ->campo('responsavel_cargo', 'Cargo')
            ->campo('responsavel_cpf', 'CPF')
            ->campo('responsavel_telefone', 'Telefone')
            ->campo('responsavel_email', 'E-mail');
    })
    ->bloco('Dados da parceria', function () use ($Painel) {
        $Painel
            ->campo('desconto', 'Desconto')
            ->campo('texto_descricao', 'Descrição')
            ->campo('texto_desconto', 'Texto de desconto')
            ->campo('texto_procedimento', 'Procedimento')
            ->campo('texto_restricao', 'Restrição')
            ->campo('texto_outro', 'Outro')
            ->campo('texto_voucher', 'Voucher')
            ->campo('comissao_minima', 'Comissão mínima')
            ->campo('comissao_maxima', 'Comissão máxima');
    })
    ->bloco('Dados do contrato', function () use ($Painel) {
        $Painel
            ->campo('data_contrato_inicio', 'Data de início')
            ->campo('data_contrato_vencimento', 'Data de vencimento');
    })
    ->bloco('Outros', function () use ($Painel) {
        $Painel
            ->campo('categoria_principal', 'Categoria principal')
            ->campo('tipo_loja', 'Tipo de loja')
            ->campo('status', 'Status');
    });

return $Painel;
