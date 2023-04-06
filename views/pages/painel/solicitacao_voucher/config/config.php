<?php

use App\Classes\SolicitacaoVoucher\Helper;

return [
    'titulo' => 'Vouchers',
    'buscar' => true,
    'filtrar' => true,
    'ordem' => true,
    'visualizar' => true,
    'download' => true,
    'add' => false,
    'editar' => false,
    'deletar' => false,
    'historico' => false,
    'api' => [
        'scope' => 'solicitacao_voucher',
        'uri' => '/solicitacao-voucher',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
