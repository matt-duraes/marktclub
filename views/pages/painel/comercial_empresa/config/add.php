<?php

use App\Classes\UsuarioEquipe\Tipo;
use Helpers\ApiHelper;
use App\Classes\ComercialEmpresa\TipoSite;
use App\Classes\ComercialEmpresa\EmailDisparo;
use App\Classes\ComercialEmpresa\ContratoPrazo;
use App\Classes\ComercialEmpresa\TipoPagamento;
use App\Classes\ComercialEmpresa\CadastroUsuario;
use App\Classes\ComercialEmpresa\ContratoRenovacao;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use PainelConfig\Add;

$Painel = new Add(app: 'comercial-empresa', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do contrato', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título para o cliente')
            ->select(
                name: 'finalidade_principal',
                lista: (new FinalidadePrincipal())->select('Escolha uma opção'),
                label: 'Finalidade da empresa',
                change: 'finalidadePrincipal'
            )
            ->select(
                name: 'finalidade_secundaria',
                lista: ['' => 'Escolha uma finalidade principal'],
                label: 'Finalidade secundária'
            )
            ->data(
                name: 'data_eleicao',
                label: 'Data da eleição',
                placeholder: 'Data da eleição',
                class: 'display_none',
                id: 'bloco_data_eleicao'
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
        $Painel
            ->select(
                name: 'cadastro_usuario',
                lista: (new CadastroUsuario())->select('Escolha como será o cadastro'),
                label: 'Como será o cadastro?'
            )
            ->select(
                name: 'equipe',
                lista: 'usuario',
                label: 'Responsável pelo contrato',
                tipoEquipe: Tipo::COMERCIAL
            )
            ->dinheiro(name: 'renda_media', label: 'Renda média', placeholder: 'Renda média')
            ->select(name: 'estado_principal', lista: 'estado', label: 'Estado principal');
    });

    $Painel->fieldset('Dados do contrato', function () use ($Painel) {
        $Painel
            ->switch(name: 'produto_clube', label: 'Clube de vantagens')
            ->select(
                name: 'tipo_site',
                lista: (new TipoSite())->select('Escolha o tipo do site do clube'),
                label: 'Tipo de site do clube',
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
            ->hidden(
                name: 'status',
                acao: 'editar'
            );
    });

    $Painel->fieldset('Financeiro', callback: function () use ($Painel) {
        $Painel
            ->select(
                name: 'tipo_pagamento',
                lista: (new TipoPagamento())->select('Escolha uma opção'),
                label: 'Tipo pagamento',
                change: 'tipoPagamento'
            )
            ->dinheiro(
                name: 'contrato_valor_minimo',
                label: 'Valor mínimo do contrato',
                placeholder: 'Valor mínimo do contrato',
                class: 'display_none',
                id: 'bloco_valor_minimo'
            )
            ->numero(
                name: 'contrato_usuario_minimo',
                label: 'Número minimo de usuário',
                placeholder: 'Número minimo de usuário',
                class: 'display_none',
                id: 'bloco_usuario_minimo',
                mascara: 'numero'
            )
            ->dinheiro(name: 'contrato_valor', label: 'Valor do contrato', placeholder: 'Valor do contrato')
            ->numero(name: 'contrato_dia_pagamento', label: 'Dia do pagamento', placeholder: 'Dia do pagamento', ajuda: 'Dia do Mês que o cliente deve pagar')
            ->numero(name: 'contrato_dia_fechamento', label: 'Dia do fechamento', placeholder: 'Dia do fechamento', ajuda: 'Dia que o sistema deve fazer a contagem de usuário')
            ->switch(name: 'cobrar_aposentado', label: 'Irá cobrar aposentado?')
            ->data(name: 'contrato_data', label: 'Data do contrato', placeholder: 'Data do início do contrato')
            ->select(
                name: 'contrato_prazo',
                lista: (new ContratoPrazo())->select('Escolha um prazo'),
                label: 'Prazo do contrato'
            )
            ->select(
                name: 'contrato_renovacao',
                lista: (new ContratoRenovacao())->select('Escolha um tipo de renovação'),
                label: 'Renovação do contrato'
            );
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Comunicação - Email', function () use ($Painel) {
        $Painel->switch(name: 'comunicacao_email', label: 'Precisa fazer e-mail?');
        $Painel->blocoCheckbox(
            titulo: 'Qual dia será enviado o e-mail?',
            callback: function () use ($Painel) {
                $Painel
                    ->checkbox(name: 'email_dia[]', label: 'Segunda-Feira', value: 'segunda-feira')
                    ->checkbox(name: 'email_dia[]', label: 'Terça-Feira', value: 'terca-feira')
                    ->checkbox(name: 'email_dia[]', label: 'Quarta-Feira', value: 'quarta-feira')
                    ->checkbox(name: 'email_dia[]', label: 'Quinta-Feira', value: 'quinta-feira')
                    ->checkbox(name: 'email_dia[]', label: 'Sexta-Feira', value: 'sexta-feira');
            },
            class: 'display_none',
            id: 'bloco_email_dia'
        );
        $Painel
            ->select(
                name: 'email_disparo',
                lista: (new EmailDisparo())->select('Escolha quem enviará os e-mails'),
                label: 'Quem dispara o e-mail?',
                id: 'bloco_email_disparo',
                class: 'display_none'
            );
    });
    $Painel->fieldset('Comunicação - Whatsapp', function () use ($Painel) {
        $Painel->switch(name: 'comunicacao_whatsapp', label: 'Precisa fazer peça para WhatsApp?');
        $Painel->blocoCheckbox(
            titulo: 'Qual dia será enviado as peças?',
            callback: function () use ($Painel) {
                $Painel
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Segunda-Feira', value: 'segunda-feira')
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Terça-Feira', value: 'terca-feira')
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Quarta-Feira', value: 'quarta-feira')
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Quinta-Feira', value: 'quinta-feira')
                    ->checkbox(name: 'whatsapp_dia[]', label: 'Sexta-Feira', value: 'sexta-feira');
            },
            class: 'display_none',
            id: 'bloco_whatsapp_dia'
        );
    });
    $Painel->fieldset('Comunicação - Rede Social', function () use ($Painel) {
        $Painel->switch(name: 'comunicacao_rede_social', label: 'Precisa fazer peça para Rede Social?');
        $Painel->blocoCheckbox(
            titulo: 'Qual dia será enviado as peças?',
            callback: function () use ($Painel) {
                $Painel
                    ->checkbox(name: 'rede_social_dia[]', label: 'Segunda-Feira', value: 'segunda-feira')
                    ->checkbox(name: 'rede_social_dia[]', label: 'Terça-Feira', value: 'terca-feira')
                    ->checkbox(name: 'rede_social_dia[]', label: 'Quarta-Feira', value: 'quarta-feira')
                    ->checkbox(name: 'rede_social_dia[]', label: 'Quinta-Feira', value: 'quinta-feira')
                    ->checkbox(name: 'rede_social_dia[]', label: 'Sexta-Feira', value: 'sexta-feira');
            },
            class: 'display_none',
            id: 'bloco_rede_social_dia'
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
                $Painel->checkbox(name: 'restricao_lista[]', label: $titulo, value: $id);
            }
        });
    });
});

$Painel->js('painel_comercial_empresa_add');

return $Painel;
