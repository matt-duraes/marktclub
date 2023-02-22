<?php

use Http\Response;
use ApiModel\Upload\GrupoEntity;
use ApiModel\Upload\ArquivoEntity;

$id = arquivoPrivadoId($requestUri);

try {
    $Arquivo = new ArquivoEntity();
    $Arquivo->id($id);
} catch (\Throwable) {
    mensagemStatus(404, localhost: 'Falhou ao tentar buscar arquivo.');
}

$download = array_key_exists('download', $_GET) && $_GET['download'] == 1;

$Grupo = new GrupoEntity();
$Grupo->_id($Arquivo->id_upload_grupo);


$equipe = array_key_exists('USUARIO_PAINEL', $_SESSION) && array_key_exists('id', $_SESSION['USUARIO_PAINEL']) ?
    $_SESSION['USUARIO_PAINEL']['id'] : '';

$privado = '';
if (!empty($Arquivo->privado)) {
    $privado = $Arquivo->privado;
} else if (!empty($Grupo->privado)) {
    $privado = $Grupo->privado;
}

if (!empty($privado) && !array_key_exists($privado, $_SESSION)) {
    mensagemStatus(401, localhost: 'Esse arquivo é privado.');
}

$arquivo = DIRETORIO_PRIVADO . '/' . $Grupo->diretorio . '/' . $Arquivo->get('arquivo');

if (!file_exists($arquivo)) {
    mensagemStatus(404, localhost: 'Esse arquivo não existe.');
} else if (!$download) {
    $Response = new Response(arquivo: $arquivo);
    $Response->render();
    exit();
} else if ($download) {
    $Response = new Response(download: $arquivo);
    $Response->render();
    exit();
}
mensagemStatus(404, localhost: 'Passou por tudo e falhou.');
