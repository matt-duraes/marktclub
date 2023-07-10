<?php

$array = [];

$array[] = [
    'id_usuario_cliente' => 3,
    'id_admin_empresa'   => 1,
    'id_parceiro_loja'   => 4207,
    'codigo'             => 123123,
    'data_criacao'       => dataRemover(agora(), 20, 'dias', 'Y-m-d H:i:s'),
    'data_emissao'       => dataRemover(agora(), 20, 'dias', 'Y-m-d H:i:s'),
    'data_vencimento'    => '2040-01-01',
    'status'             => 3
];
$array[] = [
    'id_usuario_cliente' => 3,
    'id_admin_empresa'   => 1,
    'id_parceiro_loja'   => 15612,
    'codigo'             => 321321,
    'data_criacao'       => dataRemover(agora(), 20, 'dias', 'Y-m-d H:i:s'),
    'data_emissao'       => dataRemover(agora(), 20, 'dias', 'Y-m-d H:i:s'),
    'data_vencimento'    => '2040-01-01',
    'status'             => 3
];

for ($i = 0; $i < 40; $i++) {
    $array[] = [
        'id_parceiro_loja' => [4207, 15612][rand(0, 1)],
        'codigo'           => strCodigo(8),
        'data_vencimento'  => '2040-01-01',
        'status'           => 1
    ];
}

return $array;
