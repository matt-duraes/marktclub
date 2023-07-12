<?php

use Helpers\ApiHelper;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;

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
                lista: $equipe['dado'] ?? [],
                acao: 'editar'
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

$Painel->js('painel_comercial_empresa_add');

return $Painel;
