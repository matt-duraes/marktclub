<?php

$dado = [];
$dataNumero = 10;
$dataInicial = agora();

$usuarioLista = [
    [
        'id'   => 1,
        'cpf'  => cpfAleatorio(),
        'nome' => nomeCompletoAleatorio()
    ],
    [
        'id'   => 2,
        'cpf'  => cpfAleatorio(),
        'nome' => nomeCompletoAleatorio()
    ],
    [
        'id'   => 3,
        'cpf'  => cpfAleatorio(),
        'nome' => nomeCompletoAleatorio()
    ],
];
$vinculoLista = [
    [
        'id'   => 1,
        'nome' => 'Sala Vip'
    ],
    [
        'id'   => 2,
        'nome' => 'Nome 02'
    ],
    [
        'id'   => 3,
        'nome' => 'Nome 03'
    ],
];

for ($i = 0; $i <= 10; $i++) {
    $total = rand(100, 200);
    $data = dataRemover($dataInicial, $dataNumero, 'dias');
    for ($i2 = 0; $i2 < $total; $i2++) {
        $usuario = $usuarioLista[rand(0, 2)];
        $vinculo = $vinculoLista[rand(0, 2)];
        $dado[] = [
            'usuario'      => $usuario['id'],
            'usuario_cpf'  => $usuario['cpf'],
            'usuario_nome' => $usuario['nome'],
            'empresa'      => rand(1, 50),
            'vinculo'      => $vinculo['id'],
            'vinculo_nome' => $vinculo['nome'],
            'usuario_tipo' => 1,
            'hash'         => md5(uniqid(time())),
            'ip'           => '127.0.0.1',
            'agent'        => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36 RuxitSynthetic/1.0 v2832351274092595195 t6816603945225267545 ath259cea6f altpriv cvcv=2 smf=0',
            'dispositivo'  => ['Desktop', 'Mobile Phone'][rand(0, 1)],
            'os'           => ['Linux', 'Windows', 'MAC', 'Android', 'IOS'][rand(0, 4)],
            'browser'      => ['Chrome', 'Firefox', 'Safari'][rand(0, 2)],
            'versao'       => ['100.0', '101.2', '102.3'][rand(0, 2)],
            'mobile'       => '',
            'tablet'       => '',
            'pais'         => '',
            'uf'           => '',
            'cidade'       => '',
            'latitude'     => '',
            'longitude'    => '',
            'url'          => ['/convenios', '/convenios/fisk', '/convenios/salavip', '/convenios/kalunga'][rand(0, 3)],
            'data_criacao' => $data,
            'status'       => 1
        ];
    }
    $dataNumero--;
}

return $dado;
