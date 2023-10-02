<?php

use App\Classes\ComunicacaoPopup\BotaoTarget;
use App\Classes\ComunicacaoPopup\Status;

$seeds = [];

for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $titulo = 'Titulo do meu popup ' . $i;
    $seeds[] = [
        'uuid'             => uuid(),
        'id_admin_empresa' => valorAleatorio([
            1, 2, 4, 82, 153, 198, 223, 229,
            1967, 1968, 1969, 1970, 1971
        ]),
        'slug'             => strSlug($titulo),
        'imagem'           => null,
        'titulo'           => $titulo,
        'texto'            => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            It has survived not only five centuries, but also the leap into electronic typesetting,
            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
        'regulamento'      => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.
            Lorem Ipsum has been the industrys standard dummy text ever since the 1500s,
            when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            It has survived not only five centuries, but also the leap into electronic typesetting,
            remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages,
            and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.',
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
