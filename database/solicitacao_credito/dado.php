<?php

use App\Classes\SolicitacaoCredito\Operadora;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\SolicitacaoCredito\Tipo;

return [
    [
        'uuid'             => 'c9c6d7cd-27d3-471c-b7e0-73fc7026368d',
        'codigo'           => uuid(),
        'id_admin_empresa' => 1,
        'usuario'          => 1,
        'operadora'        => (new Operadora(Operadora::SICOOB))->numero(),
        'tipo'             => (new Tipo(Tipo::CONSIGNADO))->numero(),
        'valor'            => number_format(numeroAleatorio(1, 100000), 2, '.', ''),
        'parcelas'         => numeroAleatorio(1, 96),
        'valor_parcelas'   => number_format(numeroAleatorio(1, 100000), 2, '.', ''),
        'observacao'       => '',
        'status'           => (new Status(Status::CRIADA))->numero()
    ]
];
