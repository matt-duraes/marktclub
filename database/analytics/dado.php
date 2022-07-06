<?php

$dado = [];
$dataNumero = 9;
$dataInicial = agora();
$contador = 0;

for ($i = 0; $i < 1000; $i++) {
    $data = dataRemover($dataInicial, $dataNumero, 'dias');
    $contador++;
    if ($contador == 100) {
        $contador = 0;
        $dataNumero--;
    }
    $dado[] = [
        'usuario' => rand(1, 3),
        'empresa' => 1,
        'vinculo' => rand(1, 3),
        'usuario_tipo' => 1,
        'hash' => md5(uniqid(time())),
        'ip' => '127.0.0.1',
        'agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36 RuxitSynthetic/1.0 v2832351274092595195 t6816603945225267545 ath259cea6f altpriv cvcv=2 smf=0',
        'dispositivo' => ['Desktop', 'Mobile Phone'][rand(0, 1)],
        'os' => ['Linux', 'Windows', 'MAC', 'Android', 'IOS'][rand(0, 4)],
        'browser' => 'Chrome',
        'versao' => '103.0',
        'mobile' => '',
        'tablet' => '',
        'pais' => '',
        'uf' => '',
        'cidade' => '',
        'latitude' => '',
        'longitude' => '',
        'url' => '/convenios',
        'data_criacao' => $data
    ];
}

return $dado;
