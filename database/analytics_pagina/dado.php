<?php

$data = hoje();
$urls = [
    '/convenios/fisk',
    '/convenios/kalunga',
    '/convenios/salavip',
];
$dado = [];

for ($e = 0; $e <= 10; $e++) {
    for ($i = 0; $i <= 2; $i++) {
        $dado[] = [
            'id_admin_empresa' => $e,
            'quantidade'       => rand(1, 1000),
            'url'              => $urls[0],
            'data_acesso'      => dataRemover($data, $i, 'dia')
        ];
        $dado[] = [
            'id_admin_empresa' => $e,
            'quantidade'       => rand(1, 100),
            'url'              => $urls[1],
            'data_acesso'      => dataRemover($data, $i, 'dia')
        ];
        $dado[] = [
            'id_admin_empresa' => $e,
            'quantidade'       => rand(1, 100),
            'url'              => $urls[2],
            'data_acesso'      => dataRemover($data, $i, 'dia')
        ];
    }
}

return $dado;
