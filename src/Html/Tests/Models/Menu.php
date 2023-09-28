<?php

$pathBackend = __DIR__ . '/../../../../tests/Backend';
$diretorioLista = listarArquivoDiretorio($pathBackend);
$menu = [];
foreach ($diretorioLista as $diretorio) {
    $arquivoLista = listarArquivoDiretorio($pathBackend . '/' . $diretorio, final: 'Test', ext: ['php']);
    $arquivoRetorno = [];
    foreach ($arquivoLista as $arquivo) {
        $arquivoNome = menuFiltrarNome($arquivo);
        $class = str_replace('.php', '', $arquivo);
        $arquivoRetorno[] = (object)[
            'nome'  => $arquivoNome,
            'class' => $class
        ];
    }
    $menu[] = (object)[
        'diretorio' => $diretorio,
        'lista'     => $arquivoRetorno
    ];
}
function menuFiltrarNome($arquivo)
{
    return trim(preg_replace(['/[A-Z]/', '/\.php$/', '/Test$/'], [' $0', ''], $arquivo));
}
