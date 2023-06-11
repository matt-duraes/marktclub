<?php

use Helpers\ApiHelper;
use App\Classes\ComercialEmpresa\Status;
use App\Classes\ComercialEmpresa\TipoSite;
use App\Classes\ComercialEmpresa\EmailDisparo;
use App\Classes\ComercialEmpresa\ContratoPrazo;
use App\Classes\ComercialEmpresa\TipoPagamento;
use App\Classes\ComercialEmpresa\CadastroUsuario;
use App\Classes\ComercialEmpresa\ContratoRenovacao;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;

$Painel = new PainelConfig\Add(app: 'comercial-empresa', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do contrato', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título para o cliente')
            ->select(
                name: 'finalidade_principal',
                label: 'Finalidade da empresa',
                lista: (new FinalidadePrincipal())->select('Escolha uma opção'),
                change: 'finalidadePrincipal'
            )
            ->select(
                name: 'finalidade_secundaria',
                label: 'Finalidade secundária',
                lista: ['' => 'Escolha uma finalidade principal']
            )
            ->data(
                name: 'data_eleicao',
                label: 'Data da eleição',
                placeholder: 'Data da eleição',
                id: 'bloco_data_eleicao',
                class: 'display_none'
            );
    });
    $Painel->fieldset('Dados da empresa', function () use ($Painel) {
        $Painel
            ->input(name: 'nome_fantasia', label: 'Nome Fantasia')
            ->input(name: 'razao_social', label: 'Razão social')
            ->cnpj(name: 'cnpj', label: 'CNPJ', placeholder: 'CNPJ')
            ->url(name: 'site', label: 'Site', placeholder: 'Site');
    });
    $Painel->fieldset('Dados do responsável', function () use ($Painel) {
        $Painel
            ->input(name: 'responsavel_nome', label: 'Nome do responsavel')
            ->cpf(name: 'responsavel_cpf', label: 'CPF do responsavel')
            ->email(name: 'responsavel_email', label: 'E-mail do responsavel')
            ->telefone(name: 'responsavel_telefone', label: 'Telefone do responsavel');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados interno', callback: function () use ($Painel) {
        $equipe = (new ApiHelper(token: true))
            ->json(['titulo' => 'Escolha um usuário'])
            ->get('/usuario-equipe/select')
            ->array();

        $Painel
            ->select(
                name: 'cadastro_usuario',
                label: 'Como será o cadastro?',
                lista: (new CadastroUsuario())->select('Escolha como será o cadastro')
            )
            ->select(
                name: 'equipe',
                label: 'Responsável pelo contrato',
                lista: $equipe['dado'] ?? []
            )
            ->input(name: 'renda_media', label: 'Renda média', placeholder: 'Renda média', mascara: 'dinheiro')
            ->input(name: 'valor_pib', label: 'Valor do PIB', placeholder: 'Valor do PIB', mascara: 'dinheiro')
            ->select(name: 'estado_principal', label: 'Estado principal', lista: 'estado');
    });

    $Painel->fieldset('Dados do contrato', function () use ($Painel) {
        $Painel
            ->switch(name: 'produto_clube', label: 'Clube de vantagens')
            ->select(
                name: 'tipo_site',
                label: 'Tipo de site do clube',
                lista: (new TipoSite())->select('Escolha o tipo do site do clube'),
                id: 'bloco_tipo_site',
                class: 'display_none'
            )
            ->switch(name: 'produto_ios', label: 'APP para IOS')
            ->switch(name: 'produto_android', label: 'APP para Android')
            ->switch(
                name: 'produto_webview',
                label: 'APP via webview',
                ajuda: 'Quando o cliente coloca o site dentro do próprio APP como no exemplo do Digio'
            )
            ->switch(name: 'produto_site', label: 'Site pré-moldado')
            ->switch(name: 'produto_api', label: 'API de login')
            ->select(
                name: 'status',
                label: 'Status',
                lista: (new Status())->select('Escolha uma opção'),
                acao: 'editar'
            );
    });

    $Painel->fieldset('Financeiro', callback: function () use ($Painel) {
        $Painel
            ->select(
                name: 'tipo_pagamento',
                label: 'Tipo pagamento',
                lista: (new TipoPagamento())->select('Escolha uma opção')
            )
            ->data(name: 'contrato_inicio', label: 'Início do contrato', placeholder: 'Início do contrato')
            ->select(
                name: 'contrato_prazo',
                label: 'Prazo do contrato',
                lista: (new ContratoPrazo())->select('Escolha um prazo')
            )
            ->select(
                name: 'contrato_renovacao',
                label: 'Renovação do contrato',
                lista: (new ContratoRenovacao())->select('Escolha um tipo de renovação')
            );
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Comunicação - Email', function () use ($Painel) {
        $Painel->switch(name: 'comunicacao_email', label: 'Precisa fazer e-mail?');
        $Painel->blocoCheckbox(
            titulo: 'Qual dia será enviado o e-mail?',
            id: 'bloco_email_dia',
            class: 'display_none',
            callback: function () use ($Painel) {
                $Painel
                    ->checkbox(name: 'email_dia[]', label: 'Segunda-Feira', value: 'segunda-feira')
                    ->checkbox(name: 'email_dia[]', label: 'Terça-Feira', value: 'terca-feira')
                    ->checkbox(name: 'email_dia[]', label: 'Quarta-Feira', value: 'quarta-feira')
                    ->checkbox(name: 'email_dia[]', label: 'Quinta-Feira', value: 'quinta-feira')
                    ->checkbox(name: 'email_dia[]', label: 'Sexta-Feira', value: 'sexta-feira');
            }
        );
        $Painel
            ->select(
                name: 'email_disparo',
                label: 'Quem dispara o e-mail?',
                lista: (new EmailDisparo())->select('Escolha quem enviará os e-mails'),
                id: 'bloco_email_disparo',
                class: 'display_none'
            );
    });
    $Painel->fieldset('Comunicação - Whatsapp', function () use ($Painel) {
        $Painel->switch(name: 'comunicacao_whatsapp', label: 'Precisa fazer peça para WhatsApp?');
        $Painel->blocoCheckbox(
            titulo: 'Qual dia será enviado as peças?',
            id: 'bloco_whatsapp_dia',
            class: 'display_none',
            callback: function () use ($Painel) {
                $Painel
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Segunda-Feira', value: 'segunda-feira')
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Terça-Feira', value: 'terca-feira')
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Quarta-Feira', value: 'quarta-feira')
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Quinta-Feira', value: 'quinta-feira')
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Sexta-Feira', value: 'sexta-feira');
            }
        );
    });
    $Painel->fieldset('Comunicação - Rede Social', function () use ($Painel) {
        $Painel->switch(name: 'comunicacao_rede_social', label: 'Precisa fazer peça para Rede Social?');
        $Painel->blocoCheckbox(
            titulo: 'Qual dia será enviado as peças?',
            id: 'bloco_rede_social_dia',
            class: 'display_none',
            callback: function () use ($Painel) {
                $Painel
                    ->checkbox(name: 'rede_social_dia[]', label: 'Segunda-Feira', value: 'segunda-feira')
                    ->checkbox(name: 'rede_social_dia[]', label: 'Terça-Feira', value: 'terca-feira')
                    ->checkbox(name: 'rede_social_dia[]', label: 'Quarta-Feira', value: 'quarta-feira')
                    ->checkbox(name: 'rede_social_dia[]', label: 'Quinta-Feira', value: 'quinta-feira')
                    ->checkbox(name: 'rede_social_dia[]', label: 'Sexta-Feira', value: 'sexta-feira');
            }
        );
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Observações para TI', function () use ($Painel) {
        $Painel->editorBalao(name: 'observacao_ti', placeholder: 'Digite uma observação');
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Observações para comunicação', function () use ($Painel) {
        $Painel->editorBalao(name: 'observacao_comunicacao', placeholder: 'Digite uma observação');
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Observações para o financeiro', function () use ($Painel) {
        $Painel->editorBalao(name: 'observacao_financeiro', placeholder: 'Digite uma observação');
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Restrições', function () use ($Painel) {
        $Painel->fieldsetCheckbox(callback: function () use ($Painel) {
            $restricao = (new ApiHelper(token: true))->get('/comercial-restricao/select')->array()['dado'] ?? [];
            foreach ($restricao as $id => $titulo) {
                $Painel->checkbox(name: 'restricao_lista', label: $titulo, value: $id);
            }
        });
    });
});

$Painel->js('painel_comercial_empresa_add');

return $Painel;
