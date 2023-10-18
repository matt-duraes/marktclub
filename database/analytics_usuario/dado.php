<?php

$data = hoje();
$dado = [];

for ($e = 1; $e <= 50; $e++) {
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

    for ($i = 0; $i <= 10; $i++) {
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
