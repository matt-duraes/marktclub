<?php

use App\Classes\ComercialEmpresa\Origem;
use App\Classes\ComercialEmpresa\Status;
use App\Classes\ComercialEmpresa\ContratoPrazo;
use App\Classes\ComercialEmpresa\FormatoReuniao;
use App\Classes\ComercialEmpresa\CadastroUsuario;
use App\Classes\ComercialEmpresa\CanalPreferencia;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use App\Classes\ComercialEmpresa\ContratoRenovacao;
use App\Classes\ComercialEmpresa\FinalidadePrivada;
use App\Classes\ComercialEmpresa\FinalidadePublica;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;

$dado = [
    [
        'id'                      => 1,
        'cod'                     => '14afa776394ada4be23be6acf7e3259e',
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Markt Club',
        'razao_social'            => 'Markt Club',
        'nome_fantasia'           => 'Markt Club',
        'cnpj'                    => 14150830000100,
        'responsavel_nome'        => 'André Rodrigues',
        'responsavel_cpf'         => 1495180131,
        'responsavel_email'       => 'andre@marktclub.com.br',
        'responsavel_telefone'    => 61981777773,
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'slug'                    => 'marktclub',
        'status'                  => 1
    ],
    [
        'id'                      => 2,
        'cod'                     => '0ffc5c56b99f81ca0edea8bdf524b688',
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Anafe Card',
        'razao_social'            => 'Anafe Card',
        'nome_fantasia'           => 'Anafe Card',
        'cnpj'                    => 24323554000198,
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'slug'                    => 'anafe',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'status'                  => 1
    ],
    [
        'id'                      => 153,
        'cod'                     => '369fc307129e405b3f2f00620c7b012d',
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Fenae',
        'razao_social'            => 'Fenae',
        'nome_fantasia'           => 'Fenae',
        'cnpj'                    => 3636693000100,
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'slug'                    => 'fenae',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'status'                  => 1
    ],
    [
        'id'                      => 198,
        'cod'                     => '42727943964c0800356dde841ff62800',
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'CVS Mais',
        'razao_social'            => 'CVS Mais',
        'nome_fantasia'           => 'CVS Mais',
        'cnpj'                    => 61651675000195,
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'slug'                    => 'cvsmais',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'status'                  => 1
    ],
    [
        'id'                      => 223,
        'cod'                     => '62c6e14371c10bf6ffb20325af002e7e',
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Banco Digio',
        'razao_social'            => 'Banco Digio',
        'nome_fantasia'           => 'Banco Digio',
        'cnpj'                    => 27098060000145,
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'slug'                    => 'bancodigio',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'status'                  => 1
    ],
    [
        'id'                      => 1967,
        'cod'                     => '9954c5edcc9a7b72fed65715f326df81',
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Grupo diário',
        'razao_social'            => 'Grupo diário',
        'nome_fantasia'           => 'Grupo diário',
        'cnpj'                    => 26748774000199,
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'slug'                    => 'grupodiario',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'status'                  => 1
    ],
    [
        'id'                      => 3,
        'cod'                     => '14afa776394ada4be23be6acf7e3259f',
        'id_usuario_equipe'       => 1,
        'id_admin_empresa'        => 1,
        'titulo'                  => 'Sub Empresa Markt Club',
        'razao_social'            => 'Sub Empresa Markt Club',
        'nome_fantasia'           => 'Sub Empresa Markt Club',
        'cnpj'                    => 42353214000191,
        'responsavel_nome'        => 'André Rodrigues',
        'responsavel_cpf'         => 1495180131,
        'responsavel_email'       => 'andre2@marktclub.com.br',
        'responsavel_telefone'    => 61981777772,
        'slug'                    => 'sub-marktclub',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'status'                  => 1
    ],
    [
        'id'                      => 7,
        'cod'                     => uuid(),
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Prospecção Nome 01',
        'razao_social'            => 'Prospecção Nome 01',
        'nome_fantasia'           => 'Prospecção Nome 01',
        'cnpj'                    => cnpjAleatorio(),
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'prospeccao_status'       => 1,
        'slug'                    => 'nome-01',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'finalidade_empresa'      => 1,
        'finalidade_secundaria'   => 1,
        'status'                  => 3
    ],
    [
        'id'                      => 5,
        'cod'                     => uuid(),
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Prospecção Nome 02',
        'razao_social'            => 'Prospecção Nome 02',
        'nome_fantasia'           => 'Prospecção Nome 02',
        'cnpj'                    => cnpjAleatorio(),
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'prospeccao_status'       => 1,
        'slug'                    => 'nome-02',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'finalidade_empresa'      => 1,
        'finalidade_secundaria'   => 1,
        'status'                  => 3
    ],
    [
        'id'                      => 6,
        'cod'                     => uuid(),
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Prospecção Nome 03',
        'razao_social'            => 'Prospecção Nome 03',
        'nome_fantasia'           => 'Prospecção Nome 03',
        'cnpj'                    => cnpjAleatorio(),
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'prospeccao_status'       => 1,
        'slug'                    => 'nome-03',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'finalidade_empresa'      => 1,
        'finalidade_secundaria'   => 1,
        'status'                  => 3
    ],
    [
        'id'                      => 229,
        'cod'                     => 'ea964013e0feec5b5f52bc8a94fe7574',
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Cemecard',
        'razao_social'            => 'Cemecard',
        'nome_fantasia'           => 'Cemecard',
        'cnpj'                    => 48722524000112,
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'slug'                    => 'cemecard',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'status'                  => 1
    ],
    [
        'id'                      => 82,
        'cod'                     => '61df17919405cf68f723510ec3af4acd',
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Sinpol-DF',
        'razao_social'            => 'Sinpol-DF',
        'nome_fantasia'           => 'Sinpol-DF',
        'cnpj'                    => 53545733000178,
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'slug'                    => 'sinpol-df',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'status'                  => 1
    ],
    [
        'id'                      => 4,
        'cod'                     => '34be95dcb312e99aa17c3d0deab5556b',
        'id_usuario_equipe'       => 1,
        'titulo'                  => 'Asagu Card',
        'razao_social'            => 'Asagu Card',
        'nome_fantasia'           => 'Asagu Card',
        'cnpj'                    => 30133813000110,
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneCelularAleatorio(),
        'slug'                    => 'asagu',
        'tipo_pagamento'          => 1,
        'contrato_valor'          => 1.2,
        'contrato_dia_fechamento' => 1,
        'contrato_dia_pagamento'  => 10,
        'cobrar_aposentado'       => 1,
        'status'                  => 1
    ],
];

$listaStatus = (new Status())->listarNumero();
$prospeccaoNumero = (new Status(Status::PROSPECCAO))->numero();
$standByNUmero = (new Status(Status::STANDBY))->numero();
$perdidoNumero = (new Status(Status::INATIVO))->numero();
$finalidadaePublica = (new FinalidadePrincipal(FinalidadePrincipal::PUBLICA))->numero();
$finalidadePrivada = (new FinalidadePrincipal(FinalidadePrincipal::PRIVADA))->numero();
$listaFinalidadesPublicas = (new FinalidadePublica())->listarNumero();
$listaFinalidadePrivada = (new FinalidadePrivada())->listarNumero();
$listaStatusProspeccao = (new ProspeccaoStatus())->listarNumero();
$listaCadastroUsuario = (new CadastroUsuario())->listarNumero();
$listaContratoPrazo = (new ContratoPrazo())->listarNumero();
$lisatContratoRenovacao = (new ContratoRenovacao())->listarNumero();
$listaOrigem = (new Origem())->listarNumero();
$listaCanalPreferencia = (new CanalPreferencia())->listarNumero();
$listaFormatoReuniao = (new FormatoReuniao())->listarNumero();

for ($i = 8; $i <= 50; $i++) {
    $nome = $titulos[$i - 3] ?? nomeAleatorio();
    $slug = strtolower(preg_replace('/[ -]+/', '-', $nome));
    $status = valorAleatorio($listaStatus);
    $condicaoStatusProspecao = $status == $prospeccaoNumero || $status == $standByNUmero;

    $dado[] = [
        'id'                      => $i,
        'cod'                     => $i == 2 ? '0ffc5c56b99f81ca0edea8bdf524b688' : uuid(), // Valor fixado para testes
        'id_usuario_equipe'       => 1,
        'titulo'                  => $nome,
        'razao_social'            => $nome,
        'nome_fantasia'           => $nome,
        'cnpj'                    => cnpjAleatorio(),
        'responsavel_nome'        => nomeCompletoAleatorio(),
        'responsavel_cpf'         => cpfAleatorio(),
        'responsavel_email'       => emailAleatorio(),
        'responsavel_telefone'    => telefoneAleatorio(),
        'tipo_pagamento'          => rand(1, 2),
        'contrato_valor'          => rand(1, 10000),
        'contrato_dia_fechamento' => rand(1, 28),
        'contrato_dia_pagamento'  => rand(1, 28),
        'cobrar_aposentado'       => rand(0, 1),
        'slug'                    => $slug . $i,
        'prospeccao_status'       => $condicaoStatusProspecao || $status == $perdidoNumero ? valorAleatorio($listaStatusProspeccao) : null,
        'data_eleicao'            => $condicaoStatusProspecao ? null : dataPassadaAleatorio(),
        'cadastro_usuario'        => $condicaoStatusProspecao ? null : valorAleatorio($listaCadastroUsuario),
        'renda_media'             => $condicaoStatusProspecao ? null : rand(1, 10000),
        'contrato_data'           => $condicaoStatusProspecao ? null : dataPassadaAleatorio(),
        'contrato_prazo'          => $condicaoStatusProspecao ? null : valorAleatorio($listaContratoPrazo),
        'contrato_renovacao'      => $condicaoStatusProspecao ? null : valorAleatorio($lisatContratoRenovacao),
        'finalidade_empresa'      => $i % 2 == 0 ? $finalidadaePublica : $finalidadePrivada,
        'finalidade_secundaria'   => $i % 2 == 0 ? valorAleatorio($listaFinalidadesPublicas) : valorAleatorio($listaFinalidadePrivada),
        'status'                  => $status
    ];

    if ($i % 3 == 0) {
        $dado[$i]['concorrente_status'] = 1;
        $dado[$i]['concorrente_nome'] = nomeAleatorio();
        $dado[$i]['origem'] = valorAleatorio($listaOrigem);
        $dado[$i]['usuario_possivel'] = rand(1, 10000);
    }

    if ($i % 2 == 0) {
        $dado[$i]['contato_preferencial'] = valorAleatorio($listaCanalPreferencia);
        $dado[$i]['data_apresentacao'] = dataPassadaAleatorio();
        $dado[$i]['formato_reuniao'] = valorAleatorio($listaFormatoReuniao);
    }

    if ($i % 7 == 0) {
        $dado[$i]['motivo_standby'] = 'Motivo standby';
        $dado[$i]['previsao_retorno'] = dataFuturaAleatorio();
    }
}

return $dado;
