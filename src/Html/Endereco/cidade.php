<?php

use Helpers\LocalizacaoHelper;

$estado = $_POST['estado'] ?? '';
if (empty($estado)) {
    exit();
}
mensagemStatus(404);
exit();
include ROOT . '/src/Helpers/LocalizacaoHelper.php';

$Localizacao = new LocalizacaoHelper();
$titulo = $_POST['titulo'] ?? 'Escolha uma cidade';
echo jsonEncode(
    [
        'status' => 'sucesso',
        'dado'   => $Localizacao->pegarListaCidadePeloEstado($estado, titulo: $titulo)
    ]
);
