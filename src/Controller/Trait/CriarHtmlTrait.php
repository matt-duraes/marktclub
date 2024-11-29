<?php

namespace Controller\Trait;

use Throwable;

trait CriarHtmlTrait
{
    private function criarHTML($css, $js, $var, $header)
    {
        $listaCss = !empty($css) ? '<link rel="stylesheet" href="' . $css . '">' : '';
        $listaJs = !empty($js) ? '<script src="' . $js . '"></script>' : '';

        $conteudoHtml = file_get_contents($this->viewPath);
        $comentario = !preg_match('/ppe\(/', $conteudoHtml) &&
            !preg_match('/vde\(/', $conteudoHtml) && !preg_match(
                '/exit\(/',
                $conteudoHtml
            );

        try {
            ob_start();
            if ($comentario) {
                echo '<!--LIMPAR_AO_RENDERIZAR';
            }
            if (is_array($var) && count($var) > 0) {
                extract($var, EXTR_OVERWRITE);
            }
            require_once $this->viewPath;
            $html = ob_get_clean();
            if (preg_match('/^\<\!\-\-LIMPAR\_AO\_RENDERIZAR/', $html)) {
                $html = preg_replace('/^\<\!\-\-LIMPAR\_AO\_RENDERIZAR/', '', $html);
            }
            $this->html = $html;
        } catch (Throwable $th) {
            if ($comentario) {
                echo '-->';
            }
            exceptionHandler($th);
        }
    }
}
