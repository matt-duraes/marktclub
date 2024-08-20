<?php

use Helpers\ApiHelper;
use Modules\Genero;
use Helpers\ListaHelper;
use App\Classes\UsuarioLead\Status;
use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;

$Painel = new PainelConfig\Visualizar('usuario_leed');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco(titulo: 'Dados pessoais', callback: function () use ($Painel) {
        $Painel
            ->linha('nome', 'Nome')
            ->cpf('cpf', 'CPF')
            ->linha('rg', 'RG')
            ->linha('genero', 'Gênero')
            ->data('data_nascimento', 'Data de nascimento');
    });

    $Painel->bloco(titulo: 'Dados de trabalho', callback: function () use ($Painel) {
        $Painel
            ->linha('siape', 'SIAPE')
            ->linha('trabalho_empresa', 'Local de trabalho')
            ->linha('trabalho_cargo', 'Cargo')
            ->data('trabalho_data_inicio', 'Data exercício')
            ->linha('contrato_siape', 'Contrato')
            ->cnpj('cnpj_trabalho', 'CNPJ');
    });

    $Painel->bloco(titulo: 'Dependente', callback: function () use ($Painel) {
        $Painel
            ->array('lista_dependente', 'Dependentes');
    });

    $Painel->bloco(titulo: 'Contato', callback: function () use ($Painel) {
        $Painel
            ->email('email_trabalho', 'E-mail de trabalho')
            ->email('email_pessoal', 'E-mail pessoal')
            ->email('email_funcional', 'E-mail funcional')
            ->telefone('telefone_trabalho', 'Telefone de trabalho')
            ->telefone('telefone_pessoal', 'Telefone pessoal');
    });

    $Painel->bloco(titulo: 'Endereço', callback: function () use ($Painel) {
        $Painel
            ->linha('endereco_logradouro', 'Logradouro')
            ->linha('endereco_numero', 'Número')
            ->linha('endereco_complemento', 'Complemento')
            ->linha('endereco_bairro', 'Bairro')
            ->linha('endereco_cidade', 'Cidade')
            ->linha('endereco_estado', 'Estado')
            ->cep('endereco_cep', 'CEP');
    });
    $Painel->bloco(titulo: 'Outros dados', callback: function () use ($Painel) {
        $Painel
            ->linha('origem', 'Origem')
            ->linha('status', 'Status');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Colocar em andamento',
            inArray: ['Novo'],
            status: 'andamento',
            mensagem: 'Tem certeza que deseja mudar o status para Em andamento? Essa ação não poderá ser desfeita.'
        )
        ->status(
            campo: 'status',
            texto: 'Sem interesse',
            inArray: ['Em andamento'],
            status: 'sem-interesse',
            mensagem: 'Tem certeza que deseja dar baixa a esse Lead? Essa ação não poderá ser desfeita.',
            cor: 'vermelho'
        )
        ->status(
            campo: 'status',
            texto: 'Cadastrar usuário',
            inArray: ['Em andamento'],
            status: 'cadastro-realizado',
            mensagem: 'Tem certeza que deseja cadastrar esse Lead na base? Essa ação não poderá ser desfeita.',
            cor: 'verde'
        );
});

$Lista = new ListaHelper();
$trabalhoCargo = (new ApiHelper(token: true))->get('/site-cargo/select')->array()['dado'] ?? [];

$Painel->replace(campo: 'trabalho_empresa', lista: (new TrabalhoEmpresa())->select());
$Painel->replace(campo: 'trabalho_cargo', lista: !empty($trabalhoCargo) ? $trabalhoCargo : (new TrabalhoCargo())->select());
$Painel->replace(campo: 'status', lista: (new Status())->select());
$Painel->replace(campo: 'endereco_estado', lista: $Lista->estado()->r());
$Painel->replace(campo: 'genero', lista: (new Genero())->select());
$Painel->replace(campo: 'origem', lista: (new Origem())->select());

return $Painel;
