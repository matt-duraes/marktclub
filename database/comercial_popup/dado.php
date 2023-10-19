<?php

use App\Classes\ComercialPopup\Status;
use App\Classes\ComercialPopup\BotaoTarget;

$listaTitulos = [
    'A sorte está lançada! Quem será o grande vencedor?',
    'Que a sorte esteja a seu favor!',
    'É hora de conferir os números sorteados.',
    'Cruzem os dedos, alguém está prestes a ganhar!',
    'Quem será o sortudo da vez?',
    'Está ansioso(a) para saber o resultado? Nós também!',
    'Sorteio chegando ao fim. Prepare-se para a surpresa!',
    'A adrenalina está no ar. Quem será o contemplado?',
    'Momento de suspense! O resultado está próximo.',
    'Às vezes, a sorte sorri para os mais improváveis.',
    'Números da sorte prestes a serem revelados.',
    'Hoje é seu dia de sorte!',
    'Cada bilhete é uma chance. Você está nela?',
    'A sorte é imprevisível. Será que ela está a seu favor?',
    'Um sorteio é sempre uma oportunidade única.',
    'Sorteios são como presentes da vida.',
    'Às vezes, tudo o que você precisa é de um pouco de sorte.',
    'Sorte é quando a preparação encontra a oportunidade.',
    'Vencedores nunca desistem, e desistentes nunca vencem.',
    'Um prêmio inesperado pode mudar sua vida.',
    'Sorteio é a emoção de um sonho se tornando realidade.',
    'Nada é impossível quando a sorte está do seu lado.',
    'A sorte é um mistério que ninguém pode decifrar.',
    'Sorteio é a prova de que todos têm uma chance.',
    'Sorte é o que acontece quando a preparação encontra a oportunidade.',
    'Desejando boa sorte a todos os participantes!',
    'O universo conspira a favor daqueles que acreditam na sorte.',
    'A sorte é o encontro da oportunidade com a preparação.',
    'Sorteio é a magia que acontece quando você acredita.',
    'A sorte favorece a mente preparada.',
    'Sorte é a combinação perfeita entre esforço e oportunidade.',
    'A sorte está no ar. Você a sente?',
    'Nunca subestime o poder da sorte!',
    'A sorte é como um raio: imprevisível e surpreendente.',
    'Um sorriso da sorte pode mudar tudo.',
    'A sorte é como um diamante: rara e valiosa.',
    'Quando a sorte bate à sua porta, abra-a com um sorriso.',
    'Sorteio é o momento de acreditar no impossível.',
    'Sorte é quando a oportunidade encontra você preparado.',
    'A sorte é o que acontece quando você não desiste.',
    'Que a sorte esteja ao seu lado neste sorteio!',
    'Sorte é a surpresa que a vida nos reserva.',
    'A sorte sempre encontra um caminho.',
    'Um bilhete, uma esperança, uma chance.',
    'A sorte é o tempero da vida.',
    'Sorteio é a mágica que acontece quando você acredita.',
    'Que cada número seja um desejo realizado.',
    'Nunca se sabe onde a sorte pode te levar.',
    'A sorte é um presente que todos podem receber.',
    'A sorte é a amiga daqueles que acreditam.',
    'Um sorteio é um sonho em forma de números.',
    'A sorte está nos detalhes, e você está prestando atenção?',
    'Sorteio é a emoção de um novo começo.'
];
$texto = '
    Você já imaginou ganhar um prêmio que sempre desejou?
    Agora é a sua oportunidade!
    Estamos animados em anunciar nosso sorteio incrível que pode tornar seus sonhos realidade.
';
$seeds = [];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $titulo = valorAleatorio($listaTitulos);
    $regulamento = "
        O regulamento do sorteio $titulo, organizado pela Marktclub,
        estabelece as regras e condições para a participação.
        O sorteio é aberto a residentes maiores de 18 anos do Brasil,
        exceto funcionários e afiliados da organização.
    ";
    $seeds[] = [
        'uuid'             => uuid(),
        'id_admin_empresa' => numeroAleatorio(1, 50),
        'slug'             => strSlug($titulo) . 'n° ' . $i + 1,
        'imagem'           => null,
        'titulo'           => $titulo,
        'texto'            => $texto,
        'regulamento'      => $regulamento,
        'data_inicio'      => hoje(),
        'data_final'       => dataFuturaAleatorio(),
        'atualizar_dado'   => null,
        'botao_texto'      => null,
        'botao_link'       => null,
        'botao_target'     => valorAleatorio((new BotaoTarget())->listarNumero()),
        'status'           => valorAleatorio((new Status())->listarNumero())
    ];
}
return $seeds;
