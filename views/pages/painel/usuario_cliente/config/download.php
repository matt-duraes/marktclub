<?php

$Painel = new PainelConfig\Download('usuario_cliente');

$Painel
    ->bloco('Dados pessoais', function () use ($Painel) {
        $Painel
            ->campo('nome', 'Nome')
            ->campo('rg', 'RG')
            ->campo('cpf', 'CPF')
            ->campo('siape', 'SIAPE')
            ->campo('matricula', 'Matrícula')
            ->campo('data_nascimento', 'Data de nascimento')
            ->campo('genero', 'Gênero')
            ->campo('estado_civil', 'Estado Civil');
    })
    ->bloco('Dados de contato', function () use ($Painel) {
        $Painel
            ->campo('telefone_pessoal', 'Telefone pessoal')
            ->campo('telefone_trabalho', 'Telefone trabalho')
            ->campo('email_pessoal', 'E-mail pessoal')
            ->campo('email_trabalho', 'E-mail de trabalho')
            ->campo('email_funcional', 'E-mail funcional');
    })
    ->bloco('Endereço', function () use ($Painel) {
        $Painel
            ->campo('endereco_cep', 'CEP')
            ->campo('endereco_logradouro', 'Logradouro')
            ->campo('endereco_numero', 'Número')
            ->campo('endereco_complemento', 'Complemento')
            ->campo('endereco_bairro', 'Bairro')
            ->campo('endereco_cidade', 'Cidade')
            ->campo('endereco_estado', 'Estado');
    })
    ->bloco('Datas', function () use ($Painel) {
        $Painel
            ->campo('data_criacao', 'Data de criação')
            ->campo('data_atualizacao', 'Última atualização')
            ->campo('data_upload', 'Data de upload')
            ->campo('data_acesso', 'Último acesso');
    })
    ->bloco('Outros dados', function () use ($Painel) {
        $Painel
            ->campo('tipo', 'Tipo de usuário')
            ->campo('federacao', 'Federação')
            ->campo('status', 'Status')
            ->campo('lead', 'Veio do lead')
            ->campo('grupo', 'Grupo');
    });

return $Painel;
