<?php

/*
|--------------------------------------------------------------------------
| CHAMA A VIEW
|--------------------------------------------------------------------------
|
| Retorna uma view do sistema
|
*/
use Erro\Excecao;
use Http\Response;
use Controller\Render;

if (!function_exists('view')) {
    /**
     * @param  string      $arquivo Arquivo Html
     * @param  array       $var     Lista de variáveis a ser passada para a view
     * @param  array       $header  Lista de header para ser incorporado
     * @param  string|null $css     Arquivo CSS para incorporar
     * @param  string|null $js      Arquivo JS para incorporar
     * @return Response
     * @throws Excecao
     */
    function view(
        string $arquivo,
        array $var = [],
        array $header = [],
        ?string $css = null,
        ?string $js = null,
        bool $cache = false
    ): Response {
        $Render = new Render(
            arquivo: $arquivo,
            var: $var,
            header: $header,
            css: $css,
            js: $js,
            cacheHeader: $cache
        );
        return $Render->response();
    }
}
