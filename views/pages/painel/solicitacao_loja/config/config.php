<?php

use App\Classes\SolicitacaoLoja\Helper;

return [
    'titulo'     => 'Indicações de Lojas',
    'buscar'     => false,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => true,
    'add'        => false,
    'editar'     => true,
    'deletar'    => false,
    'download'   => true,
    'historico'  => true,
    'api'        => [
        'scope'        => 'solicitacao_loja',
        'uri'          => '/solicitacao-loja',
        'criptografar' => Helper::CRIPTOGRAFAR
    ]
];
