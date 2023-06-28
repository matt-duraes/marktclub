<?php

$ROOT = __DIR__ . '/../../../../';

/*
|--------------------------------------------------------------------------
| CRIA O ARQUIVO DE TABELA
|--------------------------------------------------------------------------
*/
$listaDiretorio = array_diff(scandir($ROOT . 'database'), ['.', '..']);
$listaDefine = [];

foreach ($listaDiretorio as $diretorio) {
    if (file_exists($ROOT . '/database/' . $diretorio . '/base.php')) {
        $listaTabela = listarArquivoDiretorio($ROOT . 'database/' . $diretorio, inicio: 'tabela-');
        $tabelaReal = array_key_exists(0, $listaTabela) ? str_replace('tabela-', '', $listaTabela[0]) : $diretorio;
        $nomeDefine = "TABELA_" . mb_strtoupper($diretorio, 'UTF-8');
        $listaDefine[] = 'define("' . $nomeDefine . '", "' . $tabelaReal . '");';
        if (!defined($nomeDefine)) {
            define($nomeDefine, $tabelaReal);
        }
    }
}

if ($listaDefine) {
    file_put_contents(ROOT . '/files/banco/tabela.php', '<?php ' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $listaDefine));
}

/*
|--------------------------------------------------------------------------
| CRIA AS TABELA
|--------------------------------------------------------------------------
*/
$listaTabela = $_POST['tabela'] ?? [];
$listaModel = [];

foreach ($listaTabela as $diretorio) {
    if (file_exists($ROOT . '/database/' . $diretorio . '/base.php')) {
        $listaTabela = listarArquivoDiretorio($ROOT . 'database/' . $diretorio, inicio: 'tabela-');
        $Database = include $ROOT . '/database/' . $diretorio . '/base.php';
        $Database->diretorio = $diretorio;
        $Database->tabela = array_key_exists(0, $listaTabela) ? str_replace('tabela-', '', $listaTabela[0]) : $diretorio;
        $Database->sistemaDeletar();
        $Database->sistemaCriar();
        $listaModel[] = $Database;
    }
}

foreach ($listaModel as $Model) {
    $Model->sistemaRelacionar();
}

/*
|--------------------------------------------------------------------------
| RETORNA O HTML
|--------------------------------------------------------------------------
*/
include 'criar_ok.php';
