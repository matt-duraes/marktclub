<?php

use Helpers\ApiHelper;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use Helpers\ListaHelper;
use App\Classes\ComercialEmpresa\Origem;
use App\Classes\ComercialEmpresa\CanalPreferencia;
use App\Classes\ComercialEmpresa\FormatoReuniao;

$Painel = new PainelConfig\Add(app: 'comercial-empresa', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do contrato', function () use ($Painel) {
        $equipe = (new ApiHelper(token: true))
            ->json(['titulo' => 'Escolha um usuário'])
            ->get('/usuario-equipe/select')
            ->array();
        $Painel
            ->select(
                name: 'equipe',
                label: 'Responsável pelo contrato',
                lista: $equipe['dado'] ?? []
            )
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
            );
    });
    $Painel->fieldset('Dados da empresa', function () use ($Painel) {
        $Painel
            ->input(name: 'nome_fantasia', label: 'Nome Fantasia')
            ->input(name: 'razao_social', label: 'Razão social')
            ->cnpj(name: 'cnpj', label: 'CNPJ', placeholder: 'CNPJ')
            ->url(name: 'site', label: 'Site', placeholder: 'Site')
            ->select(name: 'estado_principal', label: 'Estado principal', lista: (new ListaHelper())->estado()->r());
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
            ->checkbox(name: 'parceiro_proprio', label: 'Parceiro próprio')
            ->checkbox(name: 'contratou_concorrente', label: 'Contratou concorrente')
            ->input(name: 'qual_concorrente', label: 'Qual concorrente')
            ->select(name: 'origem', label: 'Origem', lista: (new Origem())->select('Escolha uma opção'))
            ->numero(name: 'base_usuarios', label: 'Base de usuários');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados de apresentação', function () use ($Painel) {
        $Painel
            ->select(name: 'canal_preferencia', label: 'Canal de preferência', lista: (new CanalPreferencia())->select('Escolha uma opção'))
            ->data(name: 'data_apresentacao', label: 'Data de apresentação', placeholder: 'Data de apresentação')
            ->select(name: 'formato_reuniao', label: 'Formato da reunião', lista: (new FormatoReuniao())->select('Escolha uma opção'));
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

$Painel->js('painel_comercial_prospeccao_add');

return $Painel;
