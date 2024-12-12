<?php

$ext = strCaixaBaixa(arquivoExt($path));
$extPermitida = [
    'png', 'jpg', 'jpeg', 'gif', 'svg',
    'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'ai', 'eps', 'csv',
    'mp4', 'mov', 'avi', 'webm'
];

if (!in_array($ext, $extPermitida)) {
    mensagemStatus(404, localhost: 'Extensão não permitida.');
} elseif (!file_exists($path)) {
    mensagemStatus(404, localhost: 'O arquivo não existe.');
} elseif (!is_file($path)) {
    mensagemStatus(404, localhost: 'O arquivo não é um arquivo comum.');
}
