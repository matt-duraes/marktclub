<?php

use Helpers\ListaHelper;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;

$Painel = new PainelConfig\Add(app: 'indique-concorra', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados da indicação', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Nome da empresa', obrigatorio: true)
            ->url(name: 'site', label: 'Site', placeholder: 'Site')
            ->cnpj(name: 'cnpj', label: 'CNPJ', placeholder: 'CNPJ')
            ->select(name: 'estado_principal', label: 'Estado principal', lista: (new ListaHelper())->estado()->r(), obrigatorio: true)
            ->select(
                name: 'finalidade_principal',
                label: 'Finalidade da empresa',
                lista: (new FinalidadePrincipal())->select('Escolha uma opção'),
                obrigatorio: true
            )
            ->select(
                name: 'finalidade_secundaria',
                label: 'Finalidade secundária',
                lista: ['' => 'Escolha uma finalidade principal'],
                obrigatorio: true
            );
    });
    $Painel->fieldset('Dados do responsável', function () use ($Painel) {
        $Painel
            ->input(name: 'responsavel_nome', label: 'Nome do responsavel', obrigatorio: true)
            ->input(name: 'responsavel_cargo', label: 'Cargo do responsavel')
            ->email(name: 'responsavel_email', label: 'E-mail do responsavel', obrigatorio: true)
            ->telefone(name: 'responsavel_telefone', label: 'Telefone do responsavel', obrigatorio: true);
    });
});

$Painel->js('painel_indique_concorra_add');

return $Painel;
