<?php

use Helpers\LocalizacaoHelper;

$estado = $_POST['estado'] ?? '';
if (empty($estado)) {
    exit();
}

include ROOT . '/src/Helpers/LocalizacaoHelper.php';

$Localizacao = new LocalizacaoHelper();

echo jsonEncode(
    [
        'status' => 'sucesso',
        'dado'   => ['' => 'Escolha uma cidade'] + $Localizacao->pegarListaCidadePeloEstado($estado)
    ]
);
