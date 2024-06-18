<?php

$Historico = new PainelConfig\Historico('parceiro_externo', leitura: false);
$Historico->app('parceiro_loja', 'Loja');
$Historico->app('parceiro_externo', 'Indicação');

return [
    'titulo'     => 'Indicações',
    'buscar'     => true,
    'filtrar'    => true,
    'visualizar' => true,
    'ordem'      => true,
    'download'   => true,
    'add'        => true,
    'editar'     => false,
    'deletar'    => false,
    'historico'  => $Historico,
    'api'        => [
        'scope' => 'parceiro_externo',
        'uri'   => '/parceiro-externo'
    ]
];
