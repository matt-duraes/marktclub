<?php

use App\Classes\Solicitacao\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use Modules\EstadoCivil;

$listaTipoUsuario = (new TipoUsuario())->listarNumero();
$listaStatus = (new Status())->listarNumero();
$seeds = [];
$seeds[] = [
    'cod'                        => uuid(),
    'id_admin_empresa'           => 1,
    'id_usuario_cliente'         => 1,
    'id_automovel_versao'        => 1,
    'tipo_usuario'               => 1,
    'nome'                       => nomeCompletoAleatorio(),
    'email_pessoal'              => emailAleatorio(),
    'rg'                         => rgAleatorio(),
    'estado_civil'               => (new EstadoCivil(estadoCivilAleatorio()))->numero(),
    'telefone_celular'           => telefoneCelularAleatorio(),
    'data_nascimento'            => dataPassadaAleatorio(),
    'endereco_cep'               => cepAleatorio(),
    'endereco_logradouro'        => logradouroAleatorio(),
    'endereco_numero'            => numeroAleatorio(1, 50),
    'endereco_complemento'       => null,
    'endereco_bairro'            => bairroAleatorio(),
    'endereco_cidade'            => cidadeAleatorio(),
    'endereco_estado'            => estadoAleatorio(),
    'dependente_nome'            => nomeCompletoAleatorio(),
    'dependente_email_pessoal'   => emailAleatorio(),
    'dependente_rg'              => rgAleatorio(),
    'dependente_documento'       => cpfAleatorio(),
    'dependente_grau_parentesco' => null,
    'dependente_data_nascimento' => dataPassadaAleatorio(),
    'data_termo'                 => dataPassadaAleatorio(),
    'status'                     => valorAleatorio($listaStatus)
];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $seeds[] = [
        'cod'                        => uuid(),
        'id_admin_empresa'           => numeroAleatorio(1, 50),
        'id_usuario_cliente'         => numeroAleatorio(1, 50),
        'id_automovel_versao'        => numeroAleatorio(1, 50),
        'tipo_usuario'               => valorAleatorio($listaTipoUsuario),
        'nome'                       => nomeCompletoAleatorio(),
        'email_pessoal'              => emailAleatorio(),
        'rg'                         => rgAleatorio(),
        'estado_civil'               => (new EstadoCivil(estadoCivilAleatorio()))->numero(),
        'telefone_celular'           => telefoneCelularAleatorio(),
        'data_nascimento'            => dataPassadaAleatorio(),
        'endereco_cep'               => cepAleatorio(),
        'endereco_logradouro'        => logradouroAleatorio(),
        'endereco_numero'            => numeroAleatorio(1, 50),
        'endereco_complemento'       => null,
        'endereco_bairro'            => bairroAleatorio(),
        'endereco_cidade'            => cidadeAleatorio(),
        'endereco_estado'            => estadoAleatorio(),
        'dependente_nome'            => nomeCompletoAleatorio(),
        'dependente_email_pessoal'   => emailAleatorio(),
        'dependente_rg'              => rgAleatorio(),
        'dependente_documento'       => cpfAleatorio(),
        'dependente_grau_parentesco' => null,
        'dependente_data_nascimento' => dataPassadaAleatorio(),
        'data_termo'                 => dataPassadaAleatorio(),
        'status'                     => valorAleatorio($listaStatus)
    ];
}
return $seeds;
