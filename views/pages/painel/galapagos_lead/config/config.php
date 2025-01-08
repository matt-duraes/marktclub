<?php

$Historico = new \PainelConfig\Historico(app: 'galapagos_lead');

return [
    'titulo'     => 'Galapagos Lead',
    'buscar'     => true,
    'filtrar'    => true,
    'ordem'      => true,
    'visualizar' => true,
    'download'   => true,
    'historico'  => $Historico,
    'add'        => false,
    'editar'     => false,
    'deletar'    => false,
    'api'        => [
        'scope'        => 'galapagos_lead',
        'uri'          => '/galapagos-lead',
        'criptografar' => ['nome', 'email', 'celular', 'empresa.titulo']
    ]
];
