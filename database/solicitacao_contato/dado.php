<?php

use App\Classes\SolicitacaoContato\Status;

$mensagem = [
    'Isso é inaceitável!',
    'Estou chocado com o que acabei de ouvir!',
    'Não posso acreditar que isso esteja acontecendo.',
    'Isso é um absurdo completo!',
    'Algo precisa ser feito imediatamente!',
    'Estou revoltado(a) com essa situação!',
    'Não há justificativa para isso!',
    'Isso vai contra todos os princípios éticos!',
    'Estou indignado(a) com tanta falta de respeito!',
    'Precisamos agir e fazer a diferença!',
    'Como podem permitir que isso aconteça?',
    'Isso é uma afronta aos nossos direitos!',
    'Não podemos simplesmente ignorar isso!',
    'Estou enfurecido(a) com essa injustiça!',
    'Vamos lutar contra isso juntos!',
    'Isso é vergonhoso e inadmissível!',
    'Não vou ficar calado(a) diante disso!',
    'Não podemos aceitar esse tipo de comportamento!',
    'Chega! Isso precisa mudar agora mesmo!',
    'Estou farto(a) de ver isso acontecer!',
    'É hora de tomar medidas sérias!',
];
$listaStatus = (new Status())->listarNumero();
$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $seeds[] = [
        'uuid'             => uuid(),
        'id_admin_empresa' => numeroAleatorio(1, 50),
        'nome'             => nomeCompletoAleatorio(),
        'email'            => emailAleatorio(),
        'mensagem'         => valorAleatorio($mensagem),
        'telefone'         => telefoneAleatorio(),
        'status'           => valorAleatorio($listaStatus)
    ];
}
return $seeds;
