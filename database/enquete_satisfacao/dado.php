<?php

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Suporte;

$comentarios = [
    'Estou bastante frustrado(a) com a situação atual.',
    'Sinto-me desapontado(a) com o que aconteceu.',
    'Esta situação é realmente decepcionante.',
    'Não estava esperando por isso, e estou me sentindo contrariado(a).',
    'Gostaria que as coisas tivessem sido diferentes.',
    'Estou enfrentando dificuldades, e isso está me deixando preocupado(a).',
    'Houve um mal-entendido que está me incomodando.',
    'Não estou feliz com o desfecho desta situação.',
    'Esperava algo melhor, mas não foi o que aconteceu.',
    'Precisamos encontrar uma solução para este problema.',
    'Esta situação não está de acordo com as minhas expectativas.',
    'Estou determinado(a) a superar essas dificuldades.'
];
$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $seeds[] = [
        'uuid'               => uuid(),
        'id_admin_empresa'   => numeroAleatorio(1, 50),
        'id_usuario_cliente' => numeroAleatorio(1, 50),
        'navegar'            => valorAleatorio((new Navegar())->listarNumero()),
        'procura'            => valorAleatorio((new Procura())->listarNumero()),
        'suporte'            => valorAleatorio((new Suporte())->listarNumero()),
        'atendimento'        => valorAleatorio((new Atendimento())->listarNumero()),
        'sistemas_clube'     => '["teste","testes","sasd"]',
        'comentario'         => valorAleatorio($comentarios),
        'status'             => valorAleatorio((new Status())->listarNumero())
    ];
}
return $seeds;
