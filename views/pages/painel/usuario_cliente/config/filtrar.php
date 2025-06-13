<?php

use Helpers\ApiHelper;
use Helpers\ListaHelper;
use App\Helpers\Cfm\ConselhoHelper;
use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;

$Painel = new PainelConfig\Filtrar('usuario_cliente');

$trabalhoEmpresa = (new ApiHelper(token: true))->get('/site-lotacao/select')->array()['dado'] ?? [];
$Painel
    ->select(
        name: 'empresa',
        lista: 'empresa',
        label: 'Empresa',
        permissao: Helper::PERMISSAO_EMPRESA
    )
    ->select(
        name: 'subempresa',
        lista: 'subempresa',
        label: 'Subempresa',
        permissao: Helper::PERMISSAO_SUBEMPRESA
    )
    ->input(
        name: 'nome',
        titulo: 'Nome',
        label: 'Nome',
        placeholder: 'Digite o nome'
    )
    ->email(
        name: 'email',
        titulo: 'E-mail',
        label: 'E-mail',
        placeholder: 'Digite um e-mail'
    )
    ->cpf(
        name: 'cpf',
        titulo: 'CPF',
        label: 'CPF',
        placeholder: 'Digite um CPF'
    )
    ->bloco(function () use ($Painel, $trabalhoEmpresa) {
        $Painel
            ->select(
                name: 'tipo',
                lista: [
                    ''            => 'Escolha uma opção',
                    'titular'     => 'Titular',
                    'dependente'  => 'Dependente',
                    'funcionario' => 'Funcionário'
                ],
                label: 'Tipo de usuário'
            )
            ->select(
                name: 'endereco_estado',
                lista: (new ListaHelper())->add('', 'Escolha um estado')->estado()->r(),
                label: 'Estado'
            )
            ->select(
                name: 'federacao',
                lista: (new ListaHelper())->add('', 'Escolha um estado')->estado()->r(),
                label: 'Federação'
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->input(
                name: 'matricula',
                titulo: 'Matrícula',
                label: 'Matrícula',
                placeholder: 'Digite uma matrícula'
            )
            ->select(
              name: 'estado_crm',
              lista: (new ConselhoHelper())->lista(),
              label: 'CRM',
            )
            ->input(
                name: 'siape',
                titulo: 'SIAPE',
                label: 'SIAPE',
                placeholder: 'Digite um SIAPE'
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->data(
                name: 'data_criacao_de',
                titulo: 'Criado em',
                label: 'Criado em',
                placeholder: 'Criado em'
            )
            ->data(
                name: 'data_criacao_ate',
                titulo: 'Criado até',
                label: 'Criado até',
                placeholder: 'Criado até'
            )
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
                lista: !empty($trabalhoEmpresa)
                    ? $trabalhoEmpresa
                    : (new TrabalhoEmpresa())->select('Escolha uma opção'),
                titulo: 'Local onde trabalha',
                label: 'Trabalho'
            )
            ->select(
                name: 'trabalho_cargo',
                lista: (new TrabalhoCargo())->select('Escolha uma opção'),
                titulo: 'Cargo',
                label: 'Cargo'
            );
    })
    ->bloco(function () use ($Painel) {
        $Painel
            ->select(
                name: 'origem',
                lista: (new Origem())->select('Escolha uma opção'),
                titulo: 'Origem',
                label: 'Origem'
            )
            ->select(
                name: 'status',
                lista: (new Status())->select('Escolha uma opção'),
                titulo: 'Status',
                label: 'Status'
            );
    })
    ->switch(
        name: 'lead',
        label: 'Apenas usuários do lead?',
        titulo: 'Usuários do lead'
    )
    ->switch(
        name: 'pagamento',
        label: 'Apenas pagamento em aberto?',
        titulo: 'Pagamento em aberto'
    );

$Painel->replace('status', (new Status())->select());

return $Painel;
