<?php

use Helpers\SocialHelper;

if (!function_exists('pegarSeoPagina')) {
    function pegarSeoPagina()
    {
        $Social = new SocialHelper();

        $titulo = defined('FW_METATAG_TITULO') ? FW_METATAG_TITULO : '';
        $descricao = defined('FW_METATAG_DESCRICAO') ? FW_METATAG_DESCRICAO : '';
        $imagem = defined('FW_METATAG_IMAGEM') ? FW_METATAG_IMAGEM : '';
        $tag = defined('FW_METATAG_TAG') ? FW_METATAG_TAG : '';

        return $Social->metaTag($titulo, $descricao, $imagem, $tag);
    }
}

if (!function_exists('setarSeoPagina')) {
    function setarSeoPagina(string $titulo = '', string $descricao = '', string $imagem = '', array $tag = [])
    {
        if (!empty($titulo)) {
            define('FW_METATAG_TITULO', $titulo);
        }
        if (!empty($descricao)) {
            define('FW_METATAG_DESCRICAO', $descricao);
        }
        if (!empty($imagem)) {
            define('FW_METATAG_IMAGEM', $imagem);
        }
        if (!empty($tag)) {
            define('FW_METATAG_TAG', implode(', ', $tag));
        }
    }
}
