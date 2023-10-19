<?php

$data = hoje();
$dado = [];

$parceiros = [
    'Sala Vip',
    'Kabum',
    'Kalung',
    'Fisk',
    'CNA',
    'Wizard',
    'Casa do Construtor',
];

for ($e = 1; $e <= 50; $e++) {
    for ($i = 1; $i <= 20; $i++) {
        $id = numeroAleatorio(1, 3);

        $dado[] = [
            'id_admin_empresa'         => $e,
            'parceiro_estabelecimento' => rand(1, 2),
            'id_parceiro_loja'         => $id,
            'parceiro_nome'            => $parceiros[$id],
            'quantidade'               => rand(1, 100),
            'data_acesso'              => dataRemover($data, $i, 'dia')
        ];
    }
}

return $dado;
