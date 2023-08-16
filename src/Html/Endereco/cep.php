<?php

use Helpers\LocalizacaoHelper;

$cep = $_POST['cep'] ?? '';
if (empty($cep)) {
    exit();
}

include ROOT . '/src/Helpers/LocalizacaoHelper.php';

$Localizacao = new LocalizacaoHelper();

echo jsonEncode(
    [
        'status' => 'sucesso',
        'dado'   => $Localizacao->pegarEnderecoPeloCep($cep)
    ]
);
