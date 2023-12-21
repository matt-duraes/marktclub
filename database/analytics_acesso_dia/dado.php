<?php

$data = hoje();
$dado = [
    ['id_admin_empresa' => '1', 'quantidade_total' => '122', 'quantidade_unico' => '3', 'data_acesso' => dataRemover($data, 1, 'dia')]
];

for ($e = 1; $e <= 10; $e++) {
    for ($i = 1; $i <= 2; $i++) {
        $dado[] = [
            'id_admin_empresa' => $e,
            'quantidade_total' => rand(1, 100),
            'quantidade_unico' => rand(1, 100),
            'data_acesso'      => dataRemover($data, $i, 'dia')
        ];
    }
}

return $dado;
