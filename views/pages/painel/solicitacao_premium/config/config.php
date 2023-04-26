<?php

use App\Classes\SolicitacaoVoucher\Helper;

return [
    'titulo' => 'Voucher premium',
    'buscar' => false,
    'filtrar' => false,
    'ordem' => false,
    'visualizar' => true,
    'download' => true,
    'add' => false,
    'editar' => false,
    'deletar' => false,
    'historico' => false,
    'api' => [
        'scope' => 'solicitacao_premium',
        'uri' => '/solicitacao-premium',
        // 'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
