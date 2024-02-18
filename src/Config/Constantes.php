<?php

namespace System\Config;

final class Constantes
{
    /**
     *
     */
    public function __construct()
    {
        define('TIPO_USUARIO', 'associado');
        define('DIRETORIO_VIEW', env('DIRETORIO_VIEW', 'public'));
        define('ROOT', $this->root());

        $titulo = env('APP_TITULO', '');
        if (!empty($titulo)) {
            define('TITULO', $titulo);
        }

        $descricao = env('APP_DESCRICAO', '');
        if (!empty($descricao)) {
            define('DESCRICAO', $descricao);
        }

        $imagem = env('IMAGEM_SOCIAL', '');
        if (!empty($imagem)) {
            define('IMAGEM_SOCIAL', $imagem);
        }

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

    /**
     * @return string
     */
    private function root(): string
    {
        return str_replace(['/' . DIRETORIO_VIEW . '/index.php', '/index.php'], '', $_SERVER['SCRIPT_FILENAME']);
    }

    /**
     * @return string
     */
    private function cache(): string
    {
        if (SISTEMA == 'PRODUCAO') {
            return env('APP_CACHE', '');
        }
        return md5(uniqid(time()));
    }

    /**
     * @return string
     */
    private function contentType(): string
    {
        $header = getallheaders();
        return array_key_exists('Content-Type', $header) ? explode(';', $header['Content-Type'])[0] : '';
    }
}
