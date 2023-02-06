<?php

$hoje = date('Y-m') . '-01';

return [
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 1,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 6, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 2,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 6, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 1,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 5, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 2,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 5, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 3,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 5, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 3,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 4, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 1,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 3, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 3,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 3, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 2,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 2, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 2,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 2, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 1,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 1, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 2,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 1, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 3,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => dataRemover($hoje, 1, 'meses')
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 1,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => $hoje
    ],
    [
        'id_admin_empresa' => 1,
        'id_parceiro_loja' => 3,
        'numero_transacao' => rand(2, 8),
        'valor_venda' => number_format(rand(500, 2000), 2, '.', ''),
        'data_relatorio' => $hoje
    ],
];
