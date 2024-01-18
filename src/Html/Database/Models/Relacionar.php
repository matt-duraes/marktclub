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
$listaTabela = listarArquivoDiretorio($ROOT . 'database/' . $tabela, inicio: 'tabela-');
try {
    $Database = include $ROOT . '/database/' . $tabela . '/base.php';
    $Database->diretorio = $tabela;
    $Database->tabela = array_key_exists(0, $listaTabela) ? str_replace('tabela-', '', $listaTabela[0]) : $tabela;
} catch (\Throwable $th) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro setar classe da tabela - ' . $th->getMessage()]);
    exit();
}

try {
    $Database->sistemaRelacionar();
} catch (\Throwable $th) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao relacionar tabela - ' . $th->getMessage()]);
    exit();
}

echo json_encode(['status' => 'sucesso']);
