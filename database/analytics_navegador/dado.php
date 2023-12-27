<?php

$data = hoje();
$dado = [
    ['id' => '101', 'id_admin_empresa' => '1', 'quantidade' => '53', 'navegador' => 'Chrome', 'data_acesso' => dataRemover($data, 1, 'dia')],
    ['id' => '102', 'id_admin_empresa' => '1', 'quantidade' => '41', 'navegador' => 'Safari', 'data_acesso' => dataRemover($data, 1, 'dia')],
    ['id' => '103', 'id_admin_empresa' => '1', 'quantidade' => '47', 'navegador' => 'Firefox', 'data_acesso' => dataRemover($data, 1, 'dia')]
];

for ($e = 1; $e <= 10; $e++) {
    for ($i = 1; $i <= 5; $i++) {
        $dado[] = [
            'id_admin_empresa' => $e,
            'quantidade'       => rand(1, 100),
            'navegador'        => ['Chrome', 'Firefox', 'Safari'][rand(0, 2)],
            'data_acesso'      => dataRemover($data, $i, 'dia')
        ];
    }
}

return $dado;
