<?php

use Http\Response;
use Helpers\OrmHelper;

$id = arquivoPrivadoId($requestUri);

$Arquivo = (new OrmHelper(TABELA_UPLOAD_ARQUIVO, true))->pegarUltimoRegistro(
    where: ['uuid', $id],
    campo: ['id_upload_grupo', 'privado', 'arquivo'],
    retorno: 'object'
);
if (vazio($Arquivo)) {
    mensagemStatus(404, localhost: 'Falhou ao tentar buscar arquivo.');
}

$download = array_key_exists('download', $_GET) && $_GET['download'] == 'sim';

$Grupo = (new OrmHelper(TABELA_UPLOAD_GRUPO, true))->pegarUltimoRegistro(
    where: ['id', $Arquivo->id_upload_grupo],
    campo: ['privado', 'diretorio'],
    retorno: 'object'
);
if (vazio($Grupo)) {
    mensagemStatus(404, localhost: 'Falhou ao tentar buscar arquivo.');
}

$equipe = array_key_exists('USUARIO_PAINEL', $_SESSION) && array_key_exists('id', $_SESSION['USUARIO_PAINEL']) ?
    $_SESSION['USUARIO_PAINEL']['id'] : '';

$privado = '';
if (!empty($Arquivo->privado)) {
    $privado = $Arquivo->privado;
} elseif (!empty($Grupo->privado)) {
    $privado = $Grupo->privado;
}

if (!empty($privado) && !array_key_exists($privado, $_SESSION)) {
    mensagemStatus(401, localhost: 'Esse arquivo é privado.');
}

$path = DIRETORIO_PRIVADO . '/' . $Grupo->diretorio . '/' . $Arquivo->arquivo;

include 'imagem.php';

if (!file_exists($path)) {
    mensagemStatus(404, localhost: 'Esse arquivo não existe.');
} elseif (!$download) {
    $Response = new Response(arquivo: $path);
    $Response->render();
    exit();
} elseif ($download) {
    $Response = new Response(download: $path);
    $Response->render();
    exit();
}
mensagemStatus(404, localhost: 'Passou por tudo e falhou.');
