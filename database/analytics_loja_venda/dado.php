<?php

$hoje = date('Y-m') . '-01';

$data = [
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 1,
        'numero_transacao' => rand(2, 8),
        'valor_venda'      => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio'   => dataRemover($hoje, 6, 'meses')
    ],
];

for ($e = 1; $e <= 50; $e++) {
    for ($i = 1; $i <= 10; $i++) {
        $data[] = [
            'id_admin_empresa' => $e,
            'id_parceiro_loja' => rand(1, 50),
            'numero_transacao' => rand(2, 8),
            'valor_venda'      => number_format(rand(500, 2000), 2, '.', ''),
            'data_relatorio'   => dataRemover($hoje, $i, 'meses')
        ];
    }
}

return $data;
