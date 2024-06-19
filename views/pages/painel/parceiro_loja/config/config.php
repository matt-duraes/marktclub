<?php

$Historico = new \PainelConfig\Historico(app: 'parceiro_loja', download: true);
$Historico->app('parceiro_externo', 'Indicação');

return [
    'titulo'     => 'Lojas',
    'buscar'     => true,
    'filtrar'    => true,
    'visualizar' => true,
    'ordem'      => true,
    'download'   => true,
    'add'        => true,
    'editar'     => true,
    'deletar'    => true,
    'historico'  => $Historico,
    'api'        => [
        'scope' => 'parceiro_loja',
        'uri'   => '/parceiro-loja'
    ]
];
