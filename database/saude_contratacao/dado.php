<?php

use App\Classes\Saude\Status;

return [
    [
        'uuid'                 => uuid(),
        'id_admin_empresa'     => 1,
        'id_usuario'           => 1,
        'id_simulacao'         => '32dd2783-daf2-4cf4-be78-6f43e3f801c5',
        'documento_cpf'        => cpfAleatorio(),
        'documento_rg'         => '1',
        'orgao_expedidor'      => 'SSP',
        'nome'                 => nomeAleatorio(),
        'data_nascimento'      => dataPassadaAleatorio(),
        'estado_civil'         => estadoCivilAleatorio(),
        'naturalidade'         => 'Brasileiro',
        'sexo'                 => 1,
        'peso'                 => '75',
        'altura'               => '1.75',
        'filiacao'             => '',
        'cpf_responsavel'      => cpfAleatorio(),
        'rg_responsavel'       => '1',
        'nome_responsavel'     => nomeAleatorio(),
        'email'                => emailAleatorio(),
        'telefone_celular'     => telefoneCelularAleatorio(),
        'telefone_residencial' => telefoneFixoAleatorio(),
        'telefone_comercial'   => telefoneFixoAleatorio(),
        'ramal'                => '1',
        'endereco'             => '1231',
        'cep'                  => cepAleatorio(),
        'estado'               => estadoAleatorio(),
        'cidade'               => cidadeAleatorio(estadoAleatorio()),
        'bairro'               => bairroAleatorio(),
        'numero'               => numeroAleatorio(),
        'complemento'          => complementoAleatorio(),
        'status'               => valorAleatorio((new Status())->listarNumero())
    ]
];
