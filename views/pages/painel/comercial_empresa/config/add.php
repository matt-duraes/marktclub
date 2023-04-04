<?php

use Helpers\ApiHelper;
use App\Classes\ComercialEmpresa\Status;
use App\Classes\ComercialEmpresa\TipoPagamento;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;
use App\Classes\ComercialEmpresa\FinalidadeSecundaria;

$Painel = new PainelConfig\Add(app: 'comercial-empresa', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do contrato', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título para o cliente')
            ->select(
                name: 'finalidade_principal',
                label: 'Finalidade da empresa',
                lista: (new FinalidadePrincipal())->select('Escolha uma opção')
            )
            ->select(
                name: 'finalidade_secundaria',
                label: 'Finalidade secundária',
                lista: (new FinalidadeSecundaria())->select('Escolha uma opção')
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
                name: 'usuario',
                label: 'Responsável pelo contrato',
                lista: $equipe['dado'] ?? []
            )
            ->select(
                name: 'Tipo pagamento',
                label: 'Tipo pagamento',
                lista: (new TipoPagamento())->select('Escolha uma opção')
            )
            ->input(name: 'valor_pago', label: 'Valor pago', placeholder: 'Valor pago', mascara: 'dinheiro')
            ->input(name: 'renda_media', label: 'Renda média', placeholder: 'Renda média', mascara: 'dinheiro')
            ->input(name: 'valor_pib', label: 'Valor do PIB', placeholder: 'Valor do PIB', mascara: 'dinheiro');
    });

    $Painel->fieldset('Dados do contrato', function () use ($Painel) {
        $Painel
            ->switch(name: 'produto_clube', label: 'Clube de vantagens')
            ->switch(name: 'produto_ios', label: 'APP para IOS')
            ->switch(name: 'produto_android', label: 'APP para Android')
            ->switch(name: 'produto_site', label: 'Site pré-moldado')
            ->select(name: 'estado_principal', label: 'Estado principal', lista: 'estado')
            ->select(name: 'status', label: 'Status', lista: (new Status())->select('Escolha uma opção'))
            ;
    });
});

return $Painel;
