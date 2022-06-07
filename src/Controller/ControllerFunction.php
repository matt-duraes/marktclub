<?php

/*
|--------------------------------------------------------------------------
| CHAMA A VIEW
|--------------------------------------------------------------------------
|
| Retorna uma view do sistema
|
*/
if (!function_exists('view')) {
    /**
     * @param string    $arquivo        Arquivo Html
     * @param array     $var            Lista de variáveis a ser passada para a view
     * @param string    $header         Lista de header para ser incorporado
     * @param string    $css            Arquivo CSS para incorporar
     * @param string    $js             Arquivo JS para incorporar
     */
    function view(
        string $arquivo,
        array $var = [],
        array $header = [],
        ?string $css = null,
        ?string $js = null
    ): \Http\Response {
        $DIR_VIEW = ROOT . '/files/build/views/';

        $listaController = [];
        $listaControllerTemp = array_diff(scandir(ROOT . '/app/Controllers'), ['.', '..']);
        if ($listaControllerTemp) {
            foreach ($listaControllerTemp as $indiceController) {
                $listaController[] = mb_strtolower($indiceController, 'UTF-8');
            }
        }

        $diretorioPadrao = mb_strtolower(ROUTE_DIRETORIO, 'UTF-8');
        $primeiroDiretorioArquivo = explode('.', $arquivo)[0] ?? '';

        if (!in_array($primeiroDiretorioArquivo, $listaController)) {
            $arquivo = $diretorioPadrao . '.' . $arquivo;
        }

        $view = str_replace(['.', '/'], '_', $arquivo);
        if (substr($arquivo, -4) == '.php') {
            $view = str_replace(['.', '/', '.php'], ['_', '_', ''], $arquivo);
        }
        $target = $DIR_VIEW . $view . '.php';
        if (!file_exists($target)) {
            throw new \Erro\Excecao(status: 404);
        }

        $jsCssNome = str_replace('/', '_', $view);
        $public = env('PUBLIC', 'public');

        $cache = defined('CACHE') && !empty(CACHE) ? '?cache=' . CACHE : '';

        $listaCss = '';
        if (!empty($css)) {
            $listaCss = LINK_PADRAO . '/css/' . preg_replace('/\.css$/', '', $css) . '.css' . $cache;
        } else if (file_exists(ROOT . '/' . $public . '/css/' . $jsCssNome . '.css')) {
            $listaCss = LINK_PADRAO . '/css/' . $jsCssNome . '.css' . $cache;
        } else {
            $listaCss = verificarSeExisteScriptDoTemplate($arquivo, 'css');
        }
        $listaJs = '';
        if (!empty($js)) {
            $listaJs = LINK_PADRAO . '/js/' . preg_replace('/\.js$/', '', $js) . '.js' . $cache;
        } else if (file_exists(ROOT . '/' . $public . '/js/' . $jsCssNome . '.js')) {
            $listaJs = LINK_PADRAO . '/js/' . $jsCssNome . '.js' . $cache;
        } else {
            $listaJs = verificarSeExisteScriptDoTemplate($arquivo, 'js');
        }
        return converterHtml($target, $listaCss, $listaJs, $var, $header);
    }
}

if (!function_exists('verificarSeExisteScriptDoTemplate')) {
    function verificarSeExisteScriptDoTemplate($target, $tipo)
    {
        $cache = defined('CACHE') && !empty(CACHE) ? '?cache=' . CACHE : '';

        $target = ROOT . '/views/pages/' . str_replace('.', '/', $target) . '/index.view';
        if (!file_exists($target)) {
            return '';
        }
        $conteudo = file_get_contents($target);
        preg_match("/\@template\ ?\(?(\'|\")([a-zA-Z0-9\_\-\.\/]+)/", $conteudo, $template);
        if (!array_key_exists(2, $template)) {
            return '';
        }
        $template = $template[2];
        if ($tipo == 'js') {
            $target = ROOT . '/views/templates/' . $template . '/' . $tipo . '/all.js';
        } else {
            $target = ROOT . '/views/templates/' . $template . '/' . $tipo . '/layout.styl';
        }

        if (!file_exists($target)) {
            return '';
        }
        if ($tipo == 'js') {
            return LINK_PADRAO . '/js/templates_' . $template . '.js' . $cache;
        }
        return LINK_PADRAO . '/css/templates_' . $template . '.css' . $cache;
    }
}

if (!function_exists('converterHtml')) {
    function converterHtml($target, $css, $js, $var, $header): \Http\Response
    {
        $listaCss = '';
        if ($css) {
            $listaCss .= '<link rel="stylesheet" href="' . $css . '">';
        }

        $listaJs = '';
        if ($js) {
            $listaJs .= '<script src="' . $js . '"></script>';
        }

        $conteudoHtml = file_get_contents($target);

        $comentario = !preg_match('/ppe\(/', $conteudoHtml) && !preg_match('/vde\(/', $conteudoHtml) && !preg_match('/exit\(/', $conteudoHtml);
        try {
            ob_start();
            if ($comentario) {
                echo '<!--LIMPAR_AO_RENDERIZAR';
            }
            if (is_array($var) && count($var) > 0) {
                extract($var, EXTR_OVERWRITE);
            }
            require_once $target;
            $html = ob_get_clean();
            if (preg_match('/^\<\!\-\-LIMPAR\_AO\_RENDERIZAR/', $html)) {
                $html = preg_replace('/^\<\!\-\-LIMPAR\_AO\_RENDERIZAR/', '', $html);
            }
        } catch (\Throwable $th) {
            if ($comentario) {
                echo '-->';
            }
            exceptionHandler($th);
        }

        return new \Http\Response(body: trim($html), header: $header);
    }
}
/*
|--------------------------------------------------------------------------
| PEGAR UM HTML
|--------------------------------------------------------------------------
|
| Pegar um arquivo html e retorna como variável,
| indicado para fazer lista de conteúdo html vindo do bando
| como por exemplo, pegar a lista de produtos via ajax
|
*/
if (!function_exists('html')) {
    /**
     * @param String    $arquivo        Arquivo Html
     * @param Array     $var            Lista de variáveis a ser passada para a view
     */
    function html($arquivo, $var = [])
    {
        $DIR_VIEW = ROOT . '/files/build/views/';

        $listaController = [];
        $listaControllerTemp = array_diff(scandir(ROOT . '/app/Controllers'), ['.', '..']);
        if ($listaControllerTemp) {
            foreach ($listaControllerTemp as $indiceController) {
                $listaController[] = mb_strtolower($indiceController, 'UTF-8');
            }
        }

        $diretorioPadrao = mb_strtolower(ROUTE_DIRETORIO, 'UTF-8');
        $primeiroDiretorioArquivo = explode('.', $arquivo)[0] ?? '';

        if (!in_array($primeiroDiretorioArquivo, $listaController)) {
            $arquivo = $diretorioPadrao . '.' . $arquivo;
        }

        $view = str_replace(['.', '/'], '_', $arquivo);
        if (substr($arquivo, -4) == '.php') {
            $view = str_replace(['.', '/', '.php'], ['_', '_', ''], $arquivo);
        }
        $target = $DIR_VIEW . $view . '.php';
        if (!file_exists($target)) {
            throw new \Erro\Excecao(status: 404);
        }

        $listaCss = '';
        $listaJs = '';

        $conteudoHtml = file_get_contents($target);

        ob_start();
        if (!preg_match('/ppe\(/', $conteudoHtml) && !preg_match('/vde\(/', $conteudoHtml) && !preg_match('/exit\(/', $conteudoHtml)) {
            echo '<!--LIMPAR_AO_RENDERIZAR';
        }
        if (is_array($var) && count($var) > 0) {
            extract($var, EXTR_OVERWRITE);
        }
        require_once $target;
        $html = ob_get_clean();
        if (preg_match('/^\<\!\-\-LIMPAR\_AO\_RENDERIZAR/', $html)) {
            $html = preg_replace('/^\<\!\-\-LIMPAR\_AO\_RENDERIZAR/', '', $html);
        }
        return $html;
    }
}
