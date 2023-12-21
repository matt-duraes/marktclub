<?php

$data = hoje();
$dado = [
    [
        'id_admin_empresa'   => 1,
        'id_usuario_cliente' => 1,
        'usuario_cpf'        => '01234567890',
        'usuario_nome'       => 'Usuario de teste',
        'quantidade'         => rand(1, 100),
        'data_acesso'        => $data
    ],
    [
        'id_admin_empresa'   => 2,
        'id_usuario_cliente' => 1,
        'usuario_cpf'        => '01234567890',
        'usuario_nome'       => 'Usuario de teste',
        'quantidade'         => rand(1, 100),
        'data_acesso'        => $data
    ]
];

for ($e = 1; $e <= 10; $e++) {
    $usuario1 = [
        'nome'               => nomeCompletoAleatorio(),
        'cpf'                => cpfAleatorio(),
        'id_usuario_cliente' => $e * numeroAleatorio(1, 1000),
    ];
    $usuario2 = [
        'nome'               => nomeCompletoAleatorio(),
        'cpf'                => cpfAleatorio(),
        'id_usuario_cliente' => $e * numeroAleatorio(1, 1000),
    ];
    $usuario3 = [
        'nome'               => nomeCompletoAleatorio(),
        'cpf'                => cpfAleatorio(),
        'id_usuario_cliente' => $e * numeroAleatorio(1, 1000),
    ];

    for ($i = 0; $i <= 2; $i++) {
        $dado[] = [
            'id_admin_empresa'   => $e,
            'id_usuario_cliente' => $usuario1['id_usuario_cliente'],
            'usuario_cpf'        => $usuario1['cpf'],
            'usuario_nome'       => $usuario1['nome'],
            'quantidade'         => rand(1, 100),
            'data_acesso'        => dataRemover($data, $i, 'dia')
        ];

        $dado[] = [
            'id_admin_empresa'   => $e,
            'id_usuario_cliente' => $usuario2['id_usuario_cliente'],
            'usuario_cpf'        => $usuario2['cpf'],
            'usuario_nome'       => $usuario2['nome'],
            'quantidade'         => rand(1, 100),
            'data_acesso'        => dataRemover($data, $i, 'dia')
        ];

        $dado[] = [
            'id_admin_empresa'   => $e,
            'id_usuario_cliente' => $usuario3['id_usuario_cliente'],
            'usuario_cpf'        => $usuario3['cpf'],
            'usuario_nome'       => $usuario3['nome'],
            'quantidade'         => rand(1, 100),
            'data_acesso'        => dataRemover($data, $i, 'dia')
        ];
    }
}

return $dado;
