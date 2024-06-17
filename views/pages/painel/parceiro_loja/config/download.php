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
            ->campo('documento_cpf', 'CPF')
            ->campo('equipe', 'Gestor responsável');
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
            ->campo('empresa', 'Empresas')
            ->campo('endereco_estado', 'Estados')
            ->campo('link_site', 'Site')
            ->campo('url', 'URL')
            ->campo('desconto', 'Desconto')
            ->campo('texto_descricao', 'Descrição')
            ->campo('texto_desconto', 'Texto de desconto')
            ->campo('texto_procedimento', 'Procedimento')
            ->campo('texto_restricao', 'Restrição')
            ->campo('texto_outro', 'Outro')
            ->campo('texto_voucher', 'Voucher')
            ->campo('comissao_minima', 'Comissão mínima')
            ->campo('comissao_maxima', 'Comissão máxima')
            ->campo('data_publicacao', 'Data de publicação')
            ->campo('pontuacao', 'Pontuação');
    })
    ->bloco('Dados do contrato', function () use ($Painel) {
        $Painel
            ->campo('data_contrato_inicio', 'Data de início')
            ->campo('data_contrato_vencimento', 'Data de vencimento')
            ->campo('data_auditoria', 'Data de auditoria')
            ->campo('data_cancelado', 'Data de cancelamento')
            ->campo('cancelar_motivo', 'Motivo de cancelamento');
    })
    ->bloco('Outros', function () use ($Painel) {
        $Painel
            ->campo('categoria_principal', 'Categoria principal')
            ->campo('tipo_loja', 'Tipo de loja')
            ->campo('status', 'Status');
    });

return $Painel;
