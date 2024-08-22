<?php

use Helpers\ApiHelper;
use Helpers\ListaHelper;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;

$Painel = new PainelConfig\Filtrar('usuario_cliente');

$trabalhoEmpresa = (new ApiHelper(token: true))->get('/site-lotacao/select')->array()['dado'] ?? [];

$Painel
    ->select(name: 'empresa', label: 'Empresa', lista: 'empresa', permissao: Helper::PERMISSAO_EMPRESA)
    ->input(name: 'nome', titulo: 'Nome', label: 'Nome', placeholder: 'Digite o nome')
    ->email(name: 'email', titulo: 'E-mail', label: 'E-mail', placeholder: 'Digite um e-mail')
    ->cpf(name: 'cpf', titulo: 'CPF', label: 'CPF', placeholder: 'Digite um CPF')
    ->bloco(function () use ($Painel, $trabalhoEmpresa) {
        $Painel
            ->select(
                name: 'tipo',
                label: 'Tipo de usuário',
                lista: ['' => 'Escolha uma opção', 'titular' => 'Titular', 'dependente' => 'Dependente', 'funcionario' => 'Funcionário']
            )
            ->select(
                name: 'endereco_estado',
                label: 'Estado',
                lista: (new ListaHelper())->add('', 'Escolha um estado')->estado()->r()
            )
            ->select(
                name: 'federacao',
                label: 'Federação',
                lista: (new ListaHelper())->add('', 'Escolha um estado')->estado()->r()
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->input(name: 'matricula', titulo: 'Matrícula', label: 'Matrícula', placeholder: 'Digite uma matrícula')
            ->input(name: 'siape', titulo: 'SIAPE', label: 'SIAPE', placeholder: 'Digite um SIAPE');
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(name: 'data_criacao_de', titulo: 'Criado em', label: 'Criado em', placeholder: 'Criado em')
            ->data(name: 'data_criacao_ate', titulo: 'Criado até', label: 'Criado até', placeholder: 'Criado até')
            ->data(
                name: 'data_upload',
                titulo: 'Data de upload',
                label: 'Data de upload',
                placeholder: 'Data de upload'
            );
    })
    ->bloco(function () use ($Painel, $trabalhoEmpresa) {
        $Painel
            ->select(
                name: 'trabalho_empresa',
                titulo: 'Local onde trabalha',
                label: 'Trabalho',
                lista: !empty($trabalhoEmpresa) ? $trabalhoEmpresa : (new TrabalhoEmpresa())->select('Escolha uma opção')
            )
            ->select(
                name: 'trabalho_cargo',
                titulo: 'Cargo',
                label: 'Cargo',
                lista: (new TrabalhoCargo())->select('Escolha uma opção')
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(
                name: 'origem',
                titulo: 'Origem',
                label: 'Origem',
                lista: (new Origem())->select('Escolha uma opção')
            )
            ->select(
                name: 'status',
                titulo: 'Status',
                label: 'Status',
                lista: (new Status())->select('Escolha uma opção')
            );
    })
    ->switch(name: 'lead', titulo: 'Usuários do lead', label: 'Apenas usuários do lead?')
    ->switch(name: 'pagamento', titulo: 'Pagamento em aberto', label: 'Apenas pagamento em aberto?');

$Painel->replace('status', (new Status())->select());

return $Painel;
