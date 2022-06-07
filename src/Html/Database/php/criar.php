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
        $listaDefine[] = 'define("TABELA_' . mb_strtoupper($diretorio, 'UTF-8') . '", "' . $diretorio . '");';
    }
}
if ($listaDefine) {
    file_put_contents(__DIR__ . '/../../../Database/tabela.php', '<?php ' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $listaDefine));
}

/*
|--------------------------------------------------------------------------
| CRIA AS TABELA
|--------------------------------------------------------------------------
*/
$listaTabela = $_POST['tabela'] ?? [];
$listaModel = [];

foreach ($listaTabela as $tabela) {
    if (file_exists($ROOT . '/database/' . $tabela . '/base.php')) {
        $Database = include $ROOT . '/database/' . $tabela . '/base.php';
        $Database->tabela = $tabela;
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
