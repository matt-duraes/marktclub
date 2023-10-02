<?php

use App\Classes\ComercialEmpresa\CadastroUsuario;
use App\Classes\ComercialEmpresa\CanalPreferencia;
use App\Classes\ComercialEmpresa\ContratoPrazo;
use App\Classes\ComercialEmpresa\ContratoRenovacao;
use App\Classes\ComercialEmpresa\FinalidadePrivada;
use App\Classes\ComercialEmpresa\FinalidadePublica;
use App\Classes\ComercialEmpresa\FormatoReuniao;
use App\Classes\ComercialEmpresa\Origem;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use App\Classes\ComercialEmpresa\Status;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;

$dado = [];
$dado[] = [
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
];
$titulos = [
    'Tech Innovate Solutions',
    'EcoGreen Ventures',
    'Quantum Dynamics Inc.',
    'Stellar Synergy Enterprises',
    'BrightHorizon Technologies',
    'OmniSync Systems',
    'Visionary Media Group',
    'Nexus Global Solutions',
    'Apex Innovations LLC',
    'ProTech Solutions',
    'BlueSky Enterprises',
    'Quantum Leap Technologies',
    'Sage Insight Group',
    'SparkWave Innovations',
    'Future Focus Inc.',
    'Solaris Solutions',
    'Infinity Enterprises Ltd.',
    'TerraNova Technologies',
    'Nexus Innovate',
    'Innovatech Solutions',
    'Pinnacle Performance Group',
    'SwiftScale Solutions',
    'Paradigm Shift Enterprises',
    'Fusion Dynamics Inc.',
    'Phoenix Innovations',
    'Virtuoso Ventures',
    'Precision Systems Ltd.',
    'EchoSphere Technologies',
    'Quantum Quest Enterprises',
    'Opulent Optics Inc.',
    'Synergy Solutions Group',
    'Accelerate Technologies',
    'BlueWave Innovations',
    'Vitality Ventures Ltd.',
    'Quantum Nexus Inc.',
    'Synergetic Systems',
    'Horizon Dynamics Ltd.',
    'Innovare Solutions',
    'Quantum Pulse Enterprises',
    'NexGen Innovations',
    'Ignite Insight Group',
    'Prime Progress Technologies',
    'FutureWave Enterprises',
    'SwiftShift Solutions',
    'Quantum Vision Inc.',
    'Ascent Innovations',
    'VisionQuest Enterprises',
    'Quantum Flux Technologies',
    'Innovate Beyond Ltd.',
    'SynergySphere Solutions'
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

for ($i = 2; $i <= 50; $i++) {
    $nome = $titulos[$i - 3] ?? nomeAleatorio();
    $slug = strtolower(preg_replace('/[ -]+/', '-', $nome));
    $status = valorAleatorio($listaStatus);
    $condicaoStatusProspecao = $status == $prospeccaoNumero || $status == $standByNUmero;

    $dado[$i] = [
        'id'                      => $i,
        'cod'                     => uuid(),
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
        $dado[$i]['contratou_concorrente'] = rand(0, 1);
        $dado[$i]['qual_concorrente'] = nomeAleatorio();
        $dado[$i]['origem'] = valorAleatorio($listaOrigem);
        $dado[$i]['base_usuarios'] = rand(1, 10000);
    }

    if ($i % 2 == 0) {
        $dado[$i]['canal_preferencia'] = valorAleatorio($listaCanalPreferencia);
        $dado[$i]['data_apresentacao'] = dataPassadaAleatorio();
        $dado[$i]['formato_reuniao'] = valorAleatorio($listaFormatoReuniao);
    }

    if ($i % 7 == 0) {
        $dado[$i]['motivo_standby'] = 'Motivo standby';
        $dado[$i]['previsao_retorno'] = dataFuturaAleatorio();
    }
}

return $dado;
