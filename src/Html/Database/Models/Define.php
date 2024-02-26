<?php

$ROOT = __DIR__ . '/../../../../';

try {
    $listaDiretorio = array_diff(scandir($ROOT . 'database'), ['.', '..']);
    $listaDefine = [];

    foreach ($listaDiretorio as $diretorio) {
        if (!file_exists($ROOT . '/database/' . $diretorio . '/base.php')) {
            continue;
        }
        $arquivoTabela = listarArquivoDiretorio($ROOT . 'database/' . $diretorio, inicio: 'tabela-');
        $tabelaReal = array_key_exists(0, $arquivoTabela) ? str_replace('tabela-', '', $arquivoTabela[0]) : $diretorio;
        $nomeDefine = 'TABELA_' . mb_strtoupper($diretorio, 'UTF-8');
        $listaDefine[] = "define('" . $nomeDefine . "', '" . $tabelaReal . "');";
    }

    if ($listaDefine) {
        file_put_contents(ROOT . '/database/tabela.php', '<?php ' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $listaDefine) . PHP_EOL);
    }
} catch (\Throwable $th) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao configurar tabelas - ' . $th->getMessage()]);
    exit();
}

echo json_encode(['status' => 'sucesso']);
