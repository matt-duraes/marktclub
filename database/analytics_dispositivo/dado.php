<?php

$data = hoje();
$dado = [
    ['id' => '101', 'id_admin_empresa' => '1', 'quantidade' => '63', 'dispositivo' => 'Mobile Phone', 'data_acesso' => dataRemover($data, 1, 'dia')],
    ['id' => '102', 'id_admin_empresa' => '1', 'quantidade' => '78', 'dispositivo' => 'Desktop', 'data_acesso' => dataRemover($data, 1, 'dia')]
];

for ($e = 1; $e <= 50; $e++) {
    for ($i = 1; $i <= 10; $i++) {
        $dado[] = [
            'id_admin_empresa' => $e,
            'quantidade'       => rand(1, 100),
            'dispositivo'      => ['Desktop', 'Mobile Phone'][rand(0, 1)],
            'data_acesso'      => dataRemover($data, $i, 'dia')
        ];
    }
}

return $dado;
