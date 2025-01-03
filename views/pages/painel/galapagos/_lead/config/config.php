<?php

$Historico = new \PainelConfig\Historico(app: 'galapagos__lead');
// $Historico->app('galapagos__lead_extra', 'Galapagos  Lead Extra');

return [
    'titulo'     => 'Galapagos  Lead',
    'buscar'     => true,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => false,
    'download'   => true,
    'historico'  => $Historico,
    'add'        => true,
    'editar'     => false,
    'deletar'    => false,
    'api'        => [
        'scope'        => 'galapagos_lead',
        'uri'          => '/galapagos-lead',
        'criptografar' => []
    ]
];
