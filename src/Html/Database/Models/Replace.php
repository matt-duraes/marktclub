<?php

$ROOT = __DIR__ . '/../../../../';

try {
    $listaDiretorio = array_diff(scandir($ROOT . 'database'), ['.', '..']);
    $listaTabela = [];
    $listaReplace = [];

    foreach ($listaDiretorio as $diretorio) {
        if (!file_exists($ROOT . '/database/' . $diretorio . '/base.php')) {
            continue;
        }
        $arquivoTabela = listarArquivoDiretorio($ROOT . 'database/' . $diretorio, inicio: 'tabela-');
        $tabelaReal = array_key_exists(0, $arquivoTabela) ? str_replace('tabela-', '', $arquivoTabela[0]) : $diretorio;
        $listaTabela[$diretorio] = $tabelaReal;
    }
    foreach ($listaTabela as $diretorio => $tabela) {
        $Database = include $ROOT . '/database/' . $diretorio . '/base.php';
        $Database->diretorio = $diretorio;
        $Database->tabela = $tabela;
        $replace = $Database->replace;
        if ($replace) {
            $listaReplace[$Database->tabela] = $replace;
        }
    }

    if ($listaReplace) {
        $replaceTabela = [];
        foreach ($listaReplace as $tabela => $parametro) {
            $replaceCampo = [];
            foreach ($parametro as $ind => $val) {
                $replaceCampo[] = "'{$ind}' => '{$val}'";
            }
            $replaceTabela[] = "'{$tabela}' => [" . PHP_EOL . '        '
                . implode(',' . PHP_EOL . '        ', $replaceCampo) . PHP_EOL . '    ]';
        }

        $replaceHtml = '<?php' . PHP_EOL . PHP_EOL . 'return [' . PHP_EOL . '    '
            . implode(',' . PHP_EOL . '    ', $replaceTabela) . PHP_EOL . '];' . PHP_EOL;
        file_put_contents(ROOT . '/database/replace.php', $replaceHtml);
    }
} catch (\Throwable $th) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao configurar tabelas - ' . $th->getMessage()]);
    exit();
}

echo json_encode(['status' => 'sucesso']);
