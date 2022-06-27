<?php

use Http\Response;

$id = arquivoPrivadoId($requestUri);

require_once 'Model/Arquivo.php';
require_once 'Model/Grupo.php';

try {
    $Arquivo = new Arquivo();
    $Arquivo->id($id);
} catch (\Throwable) {
    mensagemStatus(404, 'Falhou ao tentar buscar arquivo.');
}

$download = array_key_exists('download', $_GET) && $_GET['download'] == 1;

$Grupo = new Grupo();
$Grupo->_id($Arquivo->id_upload_grupo);


$equipe = array_key_exists('USUARIO_PAINEL', $_SESSION) && array_key_exists('id', $_SESSION['USUARIO_PAINEL']) ?
    $_SESSION['USUARIO_PAINEL']['id'] : '';

$privado = '';
if (!empty($Arquivo->privado)) {
    $privado = $Arquivo->privado;
} else if (!empty($Grupo->privado)) {
    $privado = $Grupo->privado;
}

if (
    (!empty($privado) && !array_key_exists($privado, $_SESSION)) ||
    (!empty($Grupo->get('equipe')) && (empty($equipe) || !in_array($equipe, $Grupo->get('equipe'))))
) {
    mensagemStatus(401, 'Esse arquivo é privado.');
}

$arquivo = DIRETORIO_PRIVADO . '/' . $Grupo->diretorio . '/' . $Arquivo->arquivo;

if (!file_exists($arquivo)) {
    mensagemStatus(404, 'Esse arquivo não existe.');
} else if (!$download) {
    $Response = new Response(arquivo: $arquivo);
    $Response->render();
    exit();
} else if ($download) {
    $Response = new Response(download: $arquivo);
    $Response->render();
    exit();
}
mensagemStatus(404, 'Passou por tudo e falhou.');
