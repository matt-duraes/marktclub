<?php

namespace App\Models\Api\Samsung;

final class LogModel
{
    public function __construct()
    {
        $dado = [
            'url'    => env('LINK_REAL') . '/' . URI . QUERY_STRING,
            'server' => $_SERVER,
            'header' => getallheaders(),
            'get'    => $_GET,
            'post'   => $_POST,
        ];
        criarArquivo(
            DIRETORIO_PRIVADO . '/samsung/' . date('Y-m-d-h-i-s') . '_' . md5(uniqid(time())) . '.json',
            $dado
        );
    }
}
