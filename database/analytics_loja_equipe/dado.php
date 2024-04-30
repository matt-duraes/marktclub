<?php

$dado = [];

$data = dataRemover(hoje(), 7, 'dias');

for ($i = 1; $i < 8; ++$i) {
    $hoje = dataAdicionar($data, $i, 'dias');
    for ($i2 = 1; $i2 < 4; ++$i2) {
        $dado[] = [
            'id_usuario_equipe'    => $i2,
            'prospeccao_dia'       => rand(1, 100),
            'prospeccao_mes'       => rand(100, 150),
            'prospeccao_total'     => rand(200, 400),
            'problema_dia'         => rand(1, 100),
            'problema_mes'         => rand(100, 150),
            'problema_total'       => rand(200, 400),
            'cancelado_dia'        => rand(1, 100),
            'cancelado_mes'        => rand(100, 150),
            'cancelado_total'      => rand(200, 400),
            'sem_interesse_dia'    => rand(1, 100),
            'sem_interesse_mes'    => rand(100, 150),
            'sem_interesse_total'  => rand(200, 400),
            'concluido_dia'        => rand(1, 100),
            'concluido_mes'        => rand(100, 150),
            'concluido_total'      => rand(200, 400),
            'data_acesso'          => $hoje,
        ];
    }
}

return $dado;
