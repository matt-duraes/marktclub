<?php

use App\Classes\SolicitacaoLoja\Helper;

return [
    'titulo'     => 'Loja',
    'buscar'     => false,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => true,
    'add'        => true,
    'editar'     => false,
    'deletar'    => true,
    'download'   => true,
    'historico'  => true,
    'api'        => [
        'scope'        => 'solicitacao_loja',
        'uri'          => '/solicitacao-loja',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
