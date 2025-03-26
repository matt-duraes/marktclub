<?php

use App\Classes\UsuarioEquipe\Tipo;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use Helpers\ListaHelper;
use App\Classes\ComercialEmpresa\Origem;
use App\Classes\ComercialEmpresa\CanalPreferencia;
use App\Classes\ComercialEmpresa\FormatoReuniao;
use App\Classes\ComercialEmpresa\EtapaNegociacao;

$Painel = new PainelConfig\Add(app: 'comercial-empresa', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do contrato', function () use ($Painel) {
        $Painel
            ->select(
                name: 'equipe',
                lista: 'usuario',
                label: 'Responsável pelo contrato',
                tipoEquipe: Tipo::COMERCIAL
            )
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
            );
    });
    $Painel->fieldset('Dados da empresa', function () use ($Painel) {
        $Painel
            ->input(name: 'nome_fantasia', label: 'Nome Fantasia')
            ->input(name: 'razao_social', label: 'Razão social')
            ->cnpj(name: 'cnpj', label: 'CNPJ', placeholder: 'CNPJ')
            ->url(name: 'site', label: 'Site', placeholder: 'Site')
            ->select(name: 'estado_principal', lista: (new ListaHelper())->estado()->r(), label: 'Estado principal');
    });
    $Painel->fieldset('Dados do responsável', function () use ($Painel) {
        $Painel
            ->input(name: 'responsavel_nome', label: 'Nome do responsavel')
            ->input(name: 'responsavel_cargo', label: 'Cargo do responsavel')
            ->cpf(name: 'responsavel_cpf', label: 'CPF do responsavel')
            ->email(name: 'responsavel_email', label: 'E-mail do responsavel')
            ->telefone(name: 'responsavel_telefone', label: 'Telefone do responsavel');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados de pesquisa', function () use ($Painel) {
        $Painel
            ->switch(name: 'parceiro_proprio', label: 'Parceiro próprio')
            ->switch(name: 'concorrente_status', label: 'Contratou concorrente')
            ->input(name: 'concorrente_nome', label: 'Qual concorrente')
            ->select(name: 'origem', label: 'Origem', lista: (new Origem())->select('Escolha uma opção'))
            ->numero(name: 'usuario_possivel', label: 'Base de usuários');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados de apresentação', function () use ($Painel) {
        $Painel
            ->select(
                name: 'contato_preferencial',
                lista: (new CanalPreferencia())->select('Escolha uma opção'),
                label: 'Canal de preferência'
            )
            ->data(name: 'data_apresentacao', label: 'Data de apresentação', placeholder: 'Data de apresentação')
            ->select(
                name: 'formato_reuniao',
                lista: (new FormatoReuniao())->select('Escolha uma opção'),
                label: 'Formato da reunião'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Negociação', function () use ($Painel) {
        $Painel
            ->data(name: 'devolutiva', label: 'Devolutiva', placeholder: 'Devolutiva')
            ->select(
                name: 'etapa_negociacao',
                lista: (new EtapaNegociacao())->select('Escolha uma opção'),
                label: 'Etapa'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Standby', function () use ($Painel) {
        $Painel
            ->input(name: 'motivo_standby', label: 'Motivo do standby')
            ->data(name: 'previsao_retorno', label: 'Previsão de retorno', placeholder: 'Previsão de retorno')
            ->hidden(name: 'status');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Contrato perdido', callback: function () use ($Painel) {
        $Painel->input(name: 'motivo_perdido', label: 'Motivo de perder', id: 'inpust_motivo_perdido');
    });
});

$Painel->js('painel_comercial_prospeccao_add');

return $Painel;
