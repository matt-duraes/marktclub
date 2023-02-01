<?php

namespace System\Config;

final class Constantes
{
    public function __construct()
    {
        define('DIRETORIO_VIEW', env('DIRETORIO_VIEW', 'public'));
        define('ROOT', $this->root());
        define('TITULO', env('APP_TITULO', ''));
        define('DESCRICAO', env('APP_DESCRICAO', ''));
        define('CACHE', $this->cache());
        define(
            'DIRETORIO_PRIVADO',
            str_replace(
                '{{ROOT}}',
                ROOT,
                env('DIRETORIO_PRIVADO', '/{{ROOT}}/files/arquivo_privado')
            )
        );
        define(
            'DIRETORIO_PUBLICO',
            str_replace(
                '{{ROOT}}',
                ROOT,
                env('DIRETORIO_PUBLICO', '/{{ROOT}/files/arquivo_publico')
            )
        );
        define('CONTENT_TYPE', $this->contentType());
        define('METODO', $_SERVER['REQUEST_METHOD'] ?? '');
    }

    private function root(): string
    {
        return str_replace(['/' . DIRETORIO_VIEW . '/index.php', '/index.php'], '', $_SERVER['SCRIPT_FILENAME']);
    }

    private function cache(): string
    {
        if (SISTEMA == 'PRODUCAO') {
            return env('APP_CACHE', '');
        }
        return md5(uniqid(time()));
    }

    private function contentType()
    {
        $header = getallheaders();
        return array_key_exists('Content-Type', $header) ? explode(';', $header['Content-Type'])[0] : '';
    }
}
