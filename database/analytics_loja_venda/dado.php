<?php

$hoje = date('Y-m') . '-01';

$data = [
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 11,
        'numero_transacao' => rand(2, 8),
        'valor_venda'      => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio'   => dataRemover($hoje, 1, 'meses')
    ],
    [
        'id_admin_empresa' => 2,
        'id_parceiro_loja' => 1,
        'numero_transacao' => rand(2, 8),
        'valor_venda'      => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio'   => dataRemover($hoje, 1, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 2,
        'numero_transacao' => rand(2, 8),
        'valor_venda'      => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio'   => dataRemover($hoje, 1, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 11,
        'numero_transacao' => rand(2, 8),
        'valor_venda'      => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio'   => dataRemover($hoje, 1, 'meses')
    ],
    [
        'id_admin_empresa' => 2,
        'id_parceiro_loja' => 11,
        'numero_transacao' => rand(2, 8),
        'valor_venda'      => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio'   => dataRemover($hoje, 1, 'meses')
    ],
    [
        'id_admin_empresa' => 2,
        'id_parceiro_loja' => 18,
        'numero_transacao' => rand(2, 8),
        'valor_venda'      => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio'   => dataRemover($hoje, 1, 'meses')
    ],
    [
        'id_admin_empresa' => 2,
        'id_parceiro_loja' => 19,
        'numero_transacao' => rand(2, 8),
        'valor_venda'      => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio'   => dataRemover($hoje, 1, 'meses')
    ],
];

return $data;
