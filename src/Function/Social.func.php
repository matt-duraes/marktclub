<?php

if (!function_exists('socialMetaTag')) {
    // doc
    // exemplo
    // echo socialMetaTag Título_aqui,Descrição_aqui,link_imagem
    /**
     * Pega a lista de metatag da página
     *
     * @param  string|array      $titulo    Título da metatag, array para pegar o primeiro preenchido
     * @param  string|array      $descricao Descrição da metatag, array para pegar o primeiro peenchido
     * @param  null|string|array $imagem    Imagem da metatag, array para pegar o primeiro peenchido
     * @return string            Lista com as metatags
     */
    function socialMetaTag(string|array $titulo, string|array $descricao, null|string|array $imagem)
    {
        return (new \Helpers\SocialHelper())->metaTag($titulo, $descricao, $imagem);
    }
}

if (!function_exists('socialLinkFacebook')) {
    function socialLinkFacebook(?string $url = null)
    {
        $url = empty($url) ? LINK . '/' . URI : $url;
        return (new \Helpers\SocialHelper(rede: 'facebook'))->compartilhar($url);
    }
}
if (!function_exists('socialLinkTwitter')) {
    function socialLinkTwitter(string $texto, ?string $url = null, ?string $by = null)
    {
        $url = empty($url) ? LINK . '/' . URI : $url;
        return (new \Helpers\SocialHelper('twitter'))->compartilhar($url, $texto, $by);
    }
}
if (!function_exists('socialLinkWhatsapp')) {
    function socialLinkWhatsapp(?string $url = null, ?string $by = null)
    {
        $url = empty($url) ? LINK . '/' . URI : $url;
        $by = is_null($by) ? TITULO : $by;
        return (new \Helpers\SocialHelper('whatsapp'))->compartilhar(texto: $url, by: $by);
    }
}
