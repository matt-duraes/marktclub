<?php

$ROOT = __DIR__ . '/../../../../';

/*
|--------------------------------------------------------------------------
| CRIA AS TABELA
|--------------------------------------------------------------------------
*/
$tabela = $_POST['tabela'] ?? '';

if (!file_exists($ROOT . '/database/' . $tabela . '/base.php')) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Arquivo não existe']);
    exit();
}
try {
    $listaTabela = listarArquivoDiretorio($ROOT . 'database/' . $tabela, inicio: 'tabela-');
    $Database = include $ROOT . '/database/' . $tabela . '/base.php';
    $Database->diretorio = $tabela;
    $Database->tabela = array_key_exists(0, $listaTabela) ? str_replace('tabela-', '', $listaTabela[0]) : $tabela;
    $Database->sistemaRelacionar();
} catch (\Throwable $th) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao relacionar tabela - ' . $th->getMessage()]);
    exit();
}

echo json_encode(['status' => 'sucesso']);
