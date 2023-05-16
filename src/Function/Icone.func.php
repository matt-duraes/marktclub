<?php

if (!function_exists('iconeTv')) {
    // doc
    // exemplo
    // echo iconeTv
    /**
     * Gera um icone de TV
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeTv(int $tamanho = 24): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24.1 24" style="enable-background:new 0 0 24.1 24;" xml:space="preserve"><path d="M16.1,4.8h6.7c0.7,0,1.2,0.5,1.2,1.2v16.8c0,0.7-0.5,1.2-1.2,1.2H1.2C0.5,24,0,23.5,0,22.8c0,0,0,0,0,0V6c0-0.7,0.5-1.2,1.2-1.2h6.7L4.9,1.7L6.6,0l4.8,4.8h1.4L17.5,0l1.7,1.7L16.1,4.8z M2.4,7.2v14.4h19.2V7.2H2.4z"/></svg>';
    }
}
if (!function_exists('iconeTablet')) {
    // doc
    // exemplo
    // echo iconeTablet
    /**
     * Gera um icone de Tablet
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeTablet(int $tamanho = 24): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19.2 24" style="enable-background:new 0 0 19.2 24;" xml:space="preserve"><path d="M2.4,2.4v19.2h14.4V2.4H2.4z M1.2,0H18c0.7,0,1.2,0.5,1.2,1.2v21.6c0,0.7-0.5,1.2-1.2,1.2H1.2C0.5,24,0,23.5,0,22.8V1.2C0,0.5,0.5,0,1.2,0z M9.6,18c0.7,0,1.2,0.5,1.2,1.2c0,0.7-0.5,1.2-1.2,1.2c-0.7,0-1.2-0.5-1.2-1.2C8.4,18.5,8.9,18,9.6,18z"/></svg>';
    }
}
if (!function_exists('iconeTelefone')) {
    // doc
    // exemplo
    // echo iconeTelefone
    /**
     * Gera um icone de telefone
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeTelefone(int $tamanho = 24): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 15.5 24" style="enable-background:new 0 0 15.5 24;" xml:space="preserve"><path d="M2.3,2.2h12c0.6,0,1.1,0.5,1.1,1.1v19.6c0,0.6-0.5,1.1-1.1,1.1H1.2c-0.6,0-1.1-0.5-1.1-1.1V0h2.2V2.2z M2.3,9.8h10.9V4.4H2.3V9.8z M2.3,12v9.8h10.9V12H2.3z"/></svg>';
    }
}
if (!function_exists('iconePc')) {
    // doc
    // exemplo
    // echo iconePc
    /**
     * Gera um icone do PC
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconePc(int $tamanho = 24): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 25.3 24" style="enable-background:new 0 0 25.3 24;" xml:space="preserve"><path d="M2.5,16.4h20.2V2.5H2.5V16.4z M13.9,18.9v2.5h5.1V24H6.3v-2.5h5.1v-2.5H1.3c-0.7,0-1.3-0.6-1.3-1.3c0,0,0,0,0,0V1.3C0,0.6,0.6,0,1.3,0H24c0.7,0,1.3,0.6,1.3,1.3v16.4c0,0.7-0.6,1.3-1.3,1.3H13.9z"/></svg>';
    }
}
if (!function_exists('iconeUsuario')) {
    // doc
    // exemplo
    // echo iconeUsuario 17
    /**
     * Gera um icone de usuário
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeUsuario(int $tamanho = 11): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 35 40" style="enable-background:new 0 0 35 40;" xml:space="preserve"><path class="st0" d="M21.7,23.1h-8.4c-5.5,0-10,4.5-10,10v0.2c0,1.8,1.5,3.3,3.3,3.3h21.7c1.8,0,3.3-1.5,3.3-3.3v-0.2C31.7,27.6,27.2,23.1,21.7,23.1z M13.3,19.8C6,19.8,0,25.7,0,33.1v0.2C0,37,3,40,6.7,40h21.7c3.7,0,6.7-3,6.7-6.7v-0.2c0-7.4-6-13.3-13.3-13.3H13.3z"/><path class="st0" d="M17.5,13.7c2.9,0,5.2-2.3,5.2-5.2c0-2.9-2.3-5.2-5.2-5.2c-2.9,0-5.2,2.3-5.2,5.2C12.3,11.4,14.6,13.7,17.5,13.7z M17.5,17.1c4.7,0,8.5-3.8,8.5-8.5C26,3.8,22.2,0,17.5,0S9,3.8,9,8.5C9,13.2,12.8,17.1,17.5,17.1z"/></svg>';
    }
}
if (!function_exists('iconeUsuarioGrupo')) {
    // doc
    // exemplo
    // echo iconeUsuarioGrupo 18
    /**
     * Gera um icone de usuário em grupo
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeUsuarioGrupo(int $tamanho = 11): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 54 40" style="enable-background:new 0 0 54 40;" xml:space="preserve"><path d="M22.8,19.8c-7.3,0-13.3,5.9-13.3,13.3v0.2c0,3.7,3,6.7,6.7,6.7h21.7c3.7,0,6.7-3,6.7-6.7v-0.2c0-7.4-6-13.3-13.3-13.3H22.8z"/><path d="M27,17.1c4.7,0,8.5-3.8,8.5-8.5C35.5,3.8,31.7,0,27,0s-8.5,3.8-8.5,8.5S22.3,17.1,27,17.1z"/><path d="M38,3.8c-0.4,0-0.8,0.1-1.2,0.1c0.7,1.4,1.2,3,1.2,4.7c0,3.7-2,6.9-5,8.7c1.3,1.2,3.1,1.9,5,1.9c4.3,0,7.7-3.4,7.7-7.7C45.8,7.2,42.3,3.8,38,3.8z"/><path d="M41.9,21.7L41.9,21.7c3.1,2.8,5.2,6.8,5.2,11.4c0,2.9-1.3,5.3-3.4,6.9h4.2c3.4,0,6.1-2.7,6.1-6.1v-0.2C54,27,48.6,21.7,41.9,21.7z"/><path d="M16,3.8c0.4,0,0.8,0.1,1.2,0.1c-0.7,1.4-1.2,3-1.2,4.7c0,3.7,2,6.9,5,8.7c-1.3,1.2-3.1,1.9-5,1.9c-4.3,0-7.7-3.4-7.7-7.7C8.2,7.2,11.7,3.8,16,3.8z"/><path d="M12.1,21.7L12.1,21.7c-3.1,2.8-5.2,6.8-5.2,11.4c0,2.9,1.3,5.3,3.4,6.9H6.1C2.7,40,0,37.3,0,33.9l0-0.2C0,27,5.4,21.7,12.1,21.7z"/></svg>';
    }
}
if (!function_exists('iconeUsuarioAdd')) {
    // doc
    // exemplo
    // echo iconeUsuarioAdd
    /**
     * Gera um icone de add usuário
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeUsuarioAdd(int $tamanho = 11): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M14,20C6.3,20,0,26.3,0,34v4c0,1.1,0.9,2,2,2h24c1.1,0,2-0.9,2-2v-4C28,26.3,21.7,20,14,20z"/><path class="st0" d="M38,14h-4v-4c0-1.1-0.9-2-2-2s-2,0.9-2,2v4h-4c-1.1,0-2,0.9-2,2s0.9,2,2,2h4v4c0,1.1,0.9,2,2,2s2-0.9,2-2v-4h4 c1.1,0,2-0.9,2-2S39.1,14,38,14z"/><path class="st0" d="M14,18c4.4,0,8-3.6,8-8V8c0-4.4-3.6-8-8-8S6,3.6,6,8v2C6,14.4,9.6,18,14,18z"/></svg>';
    }
}
if (!function_exists('iconeMensagem')) {
    // doc
    // exemplo
    // echo iconeMensagem
    /**
     * Gera um icone de mensagem
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeMensagem(int $tamanho = 11): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 38" style="enable-background:new 0 0 40 38;" xml:space="preserve"><path class="st0" d="M26.7,0H13.3C6,0,0,6.1,0,13.6v3.6c0,5.9,3.7,11.1,9.1,12.9c-0.7,1.2-1.6,3.1-2.9,6c-0.2,0.5-0.1,1.1,0.3,1.5 C6.7,37.9,7,38,7.3,38c0.2,0,0.4,0,0.5-0.1l15-7h3.8C34,30.9,40,24.7,40,17.2v-3.6C40,6.1,34,0,26.7,0z M9.9,18.6 c-1.6,0-2.9-1.3-2.9-2.9s1.3-2.9,2.9-2.9s2.9,1.3,2.9,2.9S11.5,18.6,9.9,18.6z M20,18.6c-1.6,0-2.9-1.3-2.9-2.9s1.3-2.9,2.9-2.9 s2.9,1.3,2.9,2.9S21.6,18.6,20,18.6z M30.1,18.6c-1.6,0-2.9-1.3-2.9-2.9s1.3-2.9,2.9-2.9s2.9,1.3,2.9,2.9S31.7,18.6,30.1,18.6z"/></svg>';
    }
}
if (!function_exists('iconeAdd')) {
    // doc
    // exemplo
    // echo iconeAdd
    /**
     * Gera um icone de ADD
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeAdd(int $tamanho = 11): string
    {
        return '<svg height="' . $tamanho . '" style="margin-bottom: 3px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 35" style="enable-background:new 0 0 40 35;" xml:space="preserve"><path class="st0" d="M1.1,31.1c-0.6,0-1.1,0.5-1.1,1.1v1.8C0,34.5,0.5,35,1.1,35h37.8c0.6,0,1.1-0.5,1.1-1.1v-1.8 c0-0.6-0.5-1.1-1.1-1.1H1.1z"/><path class="st0" d="M25.9,0c-0.4,0-0.7,0.3-0.7,0.7v9.2h-9.3c-0.4,0-0.7,0.3-0.7,0.7V14c0,0.4,0.3,0.7,0.7,0.7h9.3v9.2 c0,0.4,0.3,0.7,0.7,0.7h3.5c0.4,0,0.7-0.3,0.7-0.7v-9.2h9.3c0.4,0,0.7-0.3,0.7-0.7v-3.5c0-0.4-0.3-0.7-0.7-0.7H30V0.7 C30,0.3,29.7,0,29.4,0H25.9z"/><path class="st0" d="M1.1,10.1c-0.6,0-1.1,0.5-1.1,1.1v1.8C0,13.5,0.5,14,1.1,14h9.3c0.6,0,1.1-0.5,1.1-1.1v-1.8 c0-0.6-0.5-1.1-1.1-1.1H1.1z"/><path class="st0" d="M1.2,20.6c-0.6,0-1.1,0.5-1.1,1.1v1.8c0,0.6,0.5,1.1,1.1,1.1H20c0.6,0,1.1-0.5,1.1-1.1v-1.8 c0-0.6-0.5-1.1-1.1-1.1H1.2z"/><path class="st0" d="M34.4,20.6c-0.6,0-1.1,0.5-1.1,1.1v1.8c0,0.6,0.5,1.1,1.1,1.1h4.5c0.6,0,1.1-0.5,1.1-1.1v-1.8 c0-0.6-0.5-1.1-1.1-1.1H34.4z"/></svg>';
    }
}
if (!function_exists('iconeEditar')) {
    // doc
    // exemplo
    // echo iconeEditar
    /**
     * Gera um icone de Editar
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeEditar(int $tamanho = 16): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 27 27" style="enable-background:new 0 0 27 27;" xml:space="preserve"><desc>Created with Sketch.</desc><g><g transform="translate(-2.000000, -2.000000)"><g><path d="M21.3,2.3c0.4-0.4,1-0.4,1.4,0l0,0l6,6c0.4,0.4,0.4,1,0,1.4l0,0l-17,17c-0.1,0.1-0.3,0.2-0.5,0.3l0,0     l-8,2c-0.7,0.2-1.4-0.5-1.2-1.2l0,0l2-8c0-0.2,0.1-0.3,0.3-0.5l0,0L21.3,2.3z M18,8.4L5.9,20.5l-1.5,6.1l6.1-1.5L22.6,13L18,8.4z M22,4.4L19.4,7l4.6,4.6L26.6,9L22,4.4z"/></g></g></g></svg>';
    }
}
if (!function_exists('iconeRenomear')) {
    // doc
    // exemplo
    // echo iconeRenomear
    /**
     * Gera um icone de renomear
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeRenomear(int $tamanho = 35): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 25 31.25" x="0px" y="0px"><g><path d="M4,9.4v6.2a1,1,0,0,0,1,1H6.89a.5.5,0,1,1,0,1H5a2,2,0,0,1-2-2V9.4a2,2,0,0,1,2-2H6.89a.5.5,0,0,1,0,1H5A1,1,0,0,0,4,9.4Zm16-2H10.89a.5.5,0,0,0,0,1H20a1,1,0,0,1,1,1v6.2a1,1,0,0,1-1,1H10.89a.5.5,0,0,0,0,1H20a2,2,0,0,0,2-2V9.4A2,2,0,0,0,20,7.4ZM9.559,6.208a.5.5,0,0,0,0-1,1.139,1.139,0,0,0-.671.237,1.141,1.141,0,0,0-.671-.237.5.5,0,0,0,0,1,.171.171,0,0,1,.171.17V18.622a.171.171,0,0,1-.171.17.5.5,0,0,0,0,1,1.141,1.141,0,0,0,.671-.237,1.139,1.139,0,0,0,.671.237.5.5,0,0,0,0-1,.17.17,0,0,1-.171-.17V6.378A.17.17,0,0,1,9.559,6.208Z"/></g></svg>';
    }
}
if (!function_exists('iconeDeletar')) {
    // doc
    // exemplo
    // echo iconeDeletar
    /**
     * Gera um icone de deletar
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeDeletar(int $tamanho = 22): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" x="0px" y="0px"><g data-name="Application, Delete"><path d="M13,37a4,4,0,0,0,4,4H31a4,4,0,0,0,4-4V16H13Zm2-19H33V37a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2Zm7,16H20V23h2Zm6,0H26V23h2Zm3.41-23-4-4H20.59l-4,4H9v2H39V11Zm-10-2h5.18l2,2H19.41Z"/></g></svg>';
    }
}
if (!function_exists('iconeListaMista')) {
    // doc
    // exemplo
    // echo iconeListaMista
    /**
     * Gera um icone de lista mista
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeListaMista(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" x="0px" y="0px" viewBox="0 0 35 35" style="enable-background:new 0 0 35 35;" xml:space="preserve"><path d="M1.3,15.8h13.3c0.7,0,1.2-0.5,1.2-1.2V1.2c0-0.7-0.5-1.2-1.2-1.2H1.3C0.6,0,0,0.6,0,1.2v13.3C0,15.3,0.6,15.8,1.3,15.8z M2.5,2.5h10.8v10.8H2.5C2.5,13.3,2.5,2.5,2.5,2.5z" /><path d="M33.7,15.8c0.7,0,1.2-0.5,1.2-1.2V1.2C35,0.6,34.4,0,33.7,0H20.4c-0.7,0-1.2,0.5-1.2,1.2v13.3c0,0.7,0.5,1.2,1.2,1.2H33.7 z M21.7,2.5h10.8v10.8H21.7V2.5z" /><path d="M1.3,22.9c-0.7,0-1.2,0.5-1.2,1.2c0,0.7,0.5,1.2,1.2,1.2l32.5,0l0,0c0.7,0,1.2-0.5,1.2-1.2c0-0.7-0.5-1.2-1.2-1.2 L1.3,22.9L1.3,22.9z" /><path d="M1.2,35l32.5,0l0,0c0.7,0,1.2-0.5,1.2-1.2s-0.5-1.2-1.2-1.2l-32.5,0l0,0C0.5,32.5,0,33,0,33.7C0,34.4,0.6,35,1.2,35z" /></svg>';
    }
}
if (!function_exists('iconeListaLista')) {
    // doc
    // exemplo
    // echo iconeListaLista
    /**
     * Gera um icone de lista
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeListaLista(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" x="0px" y="0px" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve"><path d="M2.8,17.1v-2.3H0v2.3H2.8 M2.8,5.2V2.9H0v2.3H2.8 M2.9,8.8H0v2.3h2.9V8.8 M20,11.2V8.8H6.1v2.3H20 M20,17.1v-2.3H6.1v2.3H20 M20,5.2V2.9H6.1v2.3H20z" /></svg>';
    }
}
if (!function_exists('iconeListaQuadro')) {
    // doc
    // exemplo
    // echo iconeListaQuadro
    /**
     * Gera um icone de lista quadrada
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeListaQuadro(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" x="0px" y="0px" viewBox="0 0 35 35" style="enable-background:new 0 0 35 35;" xml:space="preserve"><path d="M1.2,15.9h13.3c0.7,0,1.2-0.5,1.2-1.2V1.2c0-0.7-0.5-1.2-1.2-1.2H1.2C0.5,0,0,0.5,0,1.2v13.4C0,15.3,0.5,15.9,1.2,15.9z M2.4,2.5h10.9v10.9H2.4C2.4,13.4,2.4,2.5,2.4,2.5z" /><path d="M33.8,15.9c0.7,0,1.2-0.5,1.2-1.2V1.2C35,0.5,34.5,0,33.8,0H20.4c-0.7,0-1.2,0.5-1.2,1.2v13.4c0,0.7,0.5,1.2,1.2,1.2H33.8z M21.7,2.5h10.9v10.9H21.7V2.5z" /><path d="M1.2,35h13.3c0.7,0,1.2-0.5,1.2-1.2V20.4c0-0.7-0.5-1.2-1.2-1.2H1.2c-0.7,0-1.2,0.5-1.2,1.2v13.4C0,34.5,0.5,35,1.2,35z M2.4,21.6h10.9v10.9H2.4C2.4,32.5,2.4,21.6,2.4,21.6z" /><path d="M33.8,35c0.7,0,1.2-0.5,1.2-1.2V20.4c0-0.7-0.5-1.2-1.2-1.2H20.4c-0.7,0-1.2,0.5-1.2,1.2v13.4c0,0.7,0.5,1.2,1.2,1.2H33.8z M21.7,21.6h10.9v10.9H21.7V21.6z" /></svg>';
    }
}
if (!function_exists('iconeOrdemNovo')) {
    // doc
    // exemplo
    // echo iconeOrdemNovo
    /**
     * Gera um icone de ordem novo
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeOrdemNovo(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" x="0px" y="0px" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve"><path class="st0" d="M5.5,15.5C2.5,15.5,0,13.1,0,10s2.5-5.5,5.5-5.5s5.5,2.5,5.5,5.5S8.6,15.5,5.5,15.5z M5.5,5.4 C3,5.4,0.9,7.5,0.9,10s2.1,4.6,4.6,4.6s4.6-2.1,4.6-4.6S8.1,5.4,5.5,5.4z M6.6,11.7l-1.3-1.4c-0.1-0.1-0.1-0.2-0.1-0.4V7.7 c0-0.3,0.2-0.5,0.5-0.5C5.8,7.2,6,7.4,6,7.7v2.1L7.2,11c0.2,0.2,0.2,0.5,0,0.7C7,11.9,6.7,11.9,6.6,11.7L6.6,11.7z" /><g transform="translate(0,-952.36218)"><path d="M15.6,956.9l-3.6,3.2c-0.2,0.2-0.2,0.5,0,0.6c0.2,0.2,0.5,0.2,0.6,0l2.9-2.5v9.1c0,0.3,0.2,0.5,0.5,0.5 c0.3,0,0.5-0.2,0.5-0.5v-9.1l2.9,2.5c0.2,0.2,0.5,0.1,0.6,0c0.2-0.2,0.1-0.5,0-0.6l-3.6-3.2C16,956.8,15.8,956.8,15.6,956.9 L15.6,956.9z" /></g></svg>';
    }
}
if (!function_exists('iconeOrdemVelho')) {
    // doc
    // exemplo
    // echo iconeOrdemVelho
    /**
     * Gera um icone de ordem velho
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeOrdemVelho(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" x="0px" y="0px" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve"><path class="st0" d="M5.5,15.5C2.5,15.5,0,13.1,0,10s2.5-5.5,5.5-5.5s5.5,2.5,5.5,5.5S8.6,15.5,5.5,15.5z M5.5,5.4 C3,5.4,0.9,7.5,0.9,10s2.1,4.6,4.6,4.6s4.6-2.1,4.6-4.6S8.1,5.4,5.5,5.4z M6.6,11.7l-1.3-1.4c-0.1-0.1-0.1-0.2-0.1-0.4V7.7 c0-0.3,0.2-0.5,0.5-0.5C5.8,7.2,6,7.4,6,7.7v2.1L7.2,11c0.2,0.2,0.2,0.5,0,0.7C7,11.9,6.7,11.9,6.6,11.7L6.6,11.7z" /><g transform="translate(0,-952.36218)"><path d="M16.2,967.8l3.6-3.2c0.2-0.2,0.2-0.5,0-0.6c-0.2-0.2-0.5-0.2-0.6,0l-2.9,2.5v-9.1c0-0.3-0.2-0.5-0.5-0.5s-0.5,0.2-0.5,0.5 v9.1l-2.9-2.5c-0.2-0.2-0.5-0.1-0.6,0c-0.2,0.2-0.1,0.5,0,0.6l3.6,3.2C15.8,967.9,16,967.9,16.2,967.8L16.2,967.8z" /></g></svg>';
    }
}
if (!function_exists('iconeOrdemAscendente')) {
    // doc
    // exemplo
    // echo iconeOrdemAscendente
    /**
     * Gera um icone de ordem ascendente
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeOrdemAscendente(int $tamanho = 16): string
    {
        return '<svg height="' . $tamanho . '" x="0px" y="0px" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M15.9,968.5l4-3.5c0.2-0.2,0.2-0.5,0.1-0.7c-0.2-0.2-0.5-0.2-0.7-0.1L16,967v-9.9c0-0.3-0.2-0.5-0.5-0.5s-0.5,0.2-0.5,0.5 v9.9l-3.1-2.7c-0.2-0.2-0.5-0.1-0.7,0.1c-0.2,0.2-0.1,0.5,0.1,0.7l4,3.5C15.5,968.7,15.7,968.6,15.9,968.5L15.9,968.5z" /></g><path d="M5.7,3.8l5.4,12H9.9l-1.8-3.9h-5l-1.8,3.9H0l5.4-12H5.7z M5.6,6.3l-2,4.3h3.9L5.6,6.3z" /></svg>';
    }
}
if (!function_exists('iconeOrdemDescendente')) {
    // doc
    // exemplo
    // echo iconeOrdemDescendente
    /**
     * Gera um icone de ordem descendente
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeOrdemDescendente(int $tamanho = 16): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 20" style="enable-background:new 0 0 20 20;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M15.2,956.7l-4,3.5c-0.2,0.2-0.2,0.5-0.1,0.7c0.2,0.2,0.5,0.2,0.7,0.1l3.1-2.7v9.9c0,0.3,0.2,0.5,0.5,0.5s0.5-0.2,0.5-0.5 v-9.9l3.1,2.7c0.2,0.2,0.5,0.1,0.7-0.1c0.2-0.2,0.1-0.5-0.1-0.7l-4-3.5C15.6,956.6,15.4,956.6,15.2,956.7L15.2,956.7z" /></g><path d="M5.7,3.8l5.4,12H9.9l-1.8-3.9h-5l-1.8,3.9H0l5.4-12H5.7z M5.6,6.3l-2,4.3h3.9L5.6,6.3z" /></svg>';
    }
}
if (!function_exists('iconeCriarPasta')) {
    // doc
    // exemplo
    // echo iconeCriarPasta
    /**
     * Gera um icone de criar pasta
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeCriarPasta(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 32" style="enable-background:new 0 0 40 32;" xml:space="preserve"><path d="M36.1,29.5c-0.3,0-0.6,0-0.9,0c-0.8,0-1.6,0-2.4,0c-1.2,0-2.3,0-3.5,0c-1.4,0-2.8,0-4.3,0c-1.6,0-3.1,0-4.7,0 c-1.6,0-3.1,0-4.7,0c-1.4,0-2.9,0-4.3,0c-1.2,0-2.4,0-3.7,0c-0.9,0-1.7,0-2.6,0c-0.4,0-0.8,0-1.2,0c-0.1,0-0.3,0-0.4,0 c0.1,0,0.2,0,0.3,0c-0.2,0-0.4-0.1-0.7-0.2c0.1,0,0.2,0.1,0.3,0.1c-0.2-0.1-0.3-0.1-0.4-0.2c0,0-0.1,0-0.1-0.1 c-0.2-0.1,0.2,0.2,0.1,0.1c-0.1-0.1-0.1-0.1-0.2-0.2c-0.1,0-0.1-0.1-0.1-0.2c-0.1-0.2,0.1,0.2,0.1,0.1c0,0,0-0.1-0.1-0.1 c-0.1-0.1-0.2-0.3-0.2-0.5c0,0.1,0.1,0.2,0.1,0.3c-0.1-0.2-0.1-0.4-0.2-0.6c0,0.1,0,0.2,0,0.3c0-0.3,0-0.6,0-0.9 c0-0.6,0-1.2,0-1.8c0-1.9,0-3.9,0-5.8c0-2.4,0-4.8,0-7.1c0-2,0-4.1,0-6.1c0-1,0-1.9,0-2.9c0-0.1,0-0.2,0-0.4c0,0.1,0,0.2,0,0.3 c0-0.2,0.1-0.4,0.2-0.6c0,0.1-0.1,0.2-0.1,0.3C2.6,3.3,2.7,3.2,2.8,3c0,0,0-0.1,0.1-0.1C3,2.8,2.7,3.2,2.8,3 C2.8,3,2.9,2.9,2.9,2.9c0.1,0,0.1-0.1,0.2-0.1C3.3,2.6,2.9,2.9,3,2.8c0,0,0.1,0,0.1-0.1c0.1-0.1,0.3-0.2,0.5-0.2 c-0.1,0-0.2,0.1-0.3,0.1c0.2-0.1,0.4-0.1,0.7-0.2c-0.1,0-0.2,0-0.3,0c0.4,0,0.9,0,1.3,0c0.9,0,1.7,0,2.6,0c2,0,4,0,5.9,0 c0.5,0,0.9,0,1.4,0c-0.3-0.1-0.6-0.2-0.9-0.4c1.2,1.2,2.5,2.3,3.7,3.5C17.8,5.8,18,6,18.2,6.2c0.3,0.3,0.6,0.4,1,0.4 c0.4,0,0.9,0,1.3,0c1.9,0,3.8,0,5.6,0c2.2,0,4.3,0,6.5,0c1.1,0,2.3,0,3.4,0c0.1,0,0.3,0,0.4,0c-0.1,0-0.2,0-0.3,0 c0.2,0,0.4,0.1,0.7,0.2c-0.1,0-0.2-0.1-0.3-0.1c0.2,0.1,0.3,0.1,0.4,0.2c0,0,0.1,0,0.1,0.1c0.2,0.1-0.2-0.2-0.1-0.1 C36.9,6.9,37,6.9,37.1,7c0.1,0,0.1,0.1,0.1,0.2c0.1,0.2-0.1-0.2-0.1-0.1c0,0,0,0.1,0.1,0.1c0.1,0.1,0.2,0.3,0.2,0.5 c0-0.1-0.1-0.2-0.1-0.3c0.1,0.2,0.1,0.4,0.2,0.6c0-0.1,0-0.2,0-0.3c0,0.3,0,0.5,0,0.8c0,0.5,0,1,0,1.5c0,1.6,0,3.2,0,4.9 c0,2,0,4,0,5.9c0,1.7,0,3.4,0,5.1c0,0.8,0,1.6,0,2.4c0,0.1,0,0.2,0,0.4c0-0.1,0-0.2,0-0.3c0,0.2-0.1,0.4-0.2,0.6 c0-0.1,0.1-0.2,0.1-0.3c-0.1,0.2-0.1,0.3-0.2,0.4c0,0,0,0.1-0.1,0.1c-0.1,0.2,0.2-0.2,0.1-0.1c-0.1,0.1-0.1,0.1-0.2,0.2 c-0.1,0-0.1,0.1-0.2,0.1c-0.2,0.1,0.2-0.1,0.1-0.1c0,0-0.1,0-0.1,0.1c-0.1,0.1-0.3,0.2-0.5,0.2c0.1,0,0.2-0.1,0.3-0.1 C36.5,29.5,36.3,29.5,36.1,29.5c0.1,0,0.2,0,0.3,0C36.3,29.5,36.2,29.5,36.1,29.5c-0.7,0-1.3,0.6-1.3,1.2c0,0.7,0.6,1.2,1.3,1.2 c1.9,0,3.6-1.4,3.9-3.3c0-0.3,0-0.6,0-1c0-1.2,0-2.4,0-3.6c0-1.9,0-3.8,0-5.6c0-1.9,0-3.9,0-5.8c0-1.3,0-2.6,0-3.9 c0-0.2,0-0.5,0-0.7c0-1.7-1.1-3.1-2.7-3.6c-0.5-0.2-1.1-0.2-1.6-0.2c-0.5,0-1.1,0-1.6,0c-1.8,0-3.6,0-5.4,0c-2,0-3.9,0-5.9,0 c-1.2,0-2.3,0-3.5,0c-0.1,0-0.1,0-0.2,0c0.3,0.1,0.6,0.2,0.9,0.4c-1-1-2-1.9-3-2.8c-0.4-0.4-0.9-0.8-1.3-1.2 c-0.3-0.3-0.6-0.4-1-0.4c-0.2,0-0.3,0-0.5,0C12.6,0,11,0,9.4,0C7.8,0,6.2,0,4.6,0c-0.5,0-1,0-1.5,0.1C1.4,0.4,0.1,2,0,3.7 c0,0.5,0,1.1,0,1.6c0,1.3,0,2.6,0,3.9c0,1.8,0,3.5,0,5.3c0,1.9,0,3.7,0,5.6c0,1.6,0,3.2,0,4.7c0,1,0,1.9,0,2.9c0,0.1,0,0.3,0,0.4 c0,1.7,1.2,3.3,2.9,3.7C3.6,32,4.3,32,5,32c1.1,0,2.1,0,3.2,0c1.6,0,3.1,0,4.7,0c1.8,0,3.7,0,5.5,0c1.9,0,3.8,0,5.7,0 c1.7,0,3.5,0,5.2,0c1.4,0,2.7,0,4.1,0c0.8,0,1.6,0,2.3,0c0.1,0,0.2,0,0.3,0c0.7,0,1.3-0.6,1.3-1.2C37.3,30.1,36.8,29.5,36.1,29.5 z"/><path d="M21.3,23.1c0-1.1,0-2.2,0-3.2c0-1.7,0-3.4,0-5.2c0-0.4,0-0.8,0-1.2c0-0.6-0.6-1.3-1.3-1.2c-0.7,0-1.3,0.5-1.3,1.2 c0,1.1,0,2.2,0,3.2c0,1.7,0,3.4,0,5.2c0,0.4,0,0.8,0,1.2c0,0.6,0.6,1.3,1.3,1.2C20.7,24.3,21.3,23.8,21.3,23.1L21.3,23.1z"/><path d="M24.9,17.1c-1.1,0-2.2,0-3.3,0c-1.8,0-3.5,0-5.3,0c-0.4,0-0.8,0-1.2,0c-0.7,0-1.3,0.6-1.3,1.2c0,0.7,0.6,1.2,1.3,1.2 c1.1,0,2.2,0,3.3,0c1.8,0,3.5,0,5.3,0c0.4,0,0.8,0,1.2,0c0.7,0,1.3-0.6,1.3-1.2C26.1,17.6,25.6,17.1,24.9,17.1L24.9,17.1z"/></svg>';
    }
}
if (!function_exists('iconeRemoverPasta')) {
    // doc
    // exemplo
    // echo iconeRemoverPasta
    /**
     * Gera um icone de remover pasta
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeRemoverPasta(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 32" style="enable-background:new 0 0 40 32;" xml:space="preserve"><path d="M36.1,29.5c-0.3,0-0.6,0-0.9,0c-0.8,0-1.6,0-2.4,0c-1.2,0-2.3,0-3.5,0c-1.4,0-2.8,0-4.3,0c-1.6,0-3.1,0-4.7,0 c-1.6,0-3.1,0-4.7,0c-1.4,0-2.9,0-4.3,0c-1.2,0-2.4,0-3.7,0c-0.9,0-1.7,0-2.6,0c-0.4,0-0.8,0-1.2,0c-0.1,0-0.3,0-0.4,0 c0.1,0,0.2,0,0.3,0c-0.2,0-0.4-0.1-0.7-0.2c0.1,0,0.2,0.1,0.3,0.1c-0.2-0.1-0.3-0.1-0.4-0.2c0,0-0.1,0-0.1-0.1 c-0.2-0.1,0.2,0.2,0.1,0.1c-0.1-0.1-0.1-0.1-0.2-0.2c-0.1,0-0.1-0.1-0.1-0.2c-0.1-0.2,0.1,0.2,0.1,0.1c0,0,0-0.1-0.1-0.1 c-0.1-0.1-0.2-0.3-0.2-0.5c0,0.1,0.1,0.2,0.1,0.3c-0.1-0.2-0.1-0.4-0.2-0.6c0,0.1,0,0.2,0,0.3c0-0.3,0-0.6,0-0.9 c0-0.6,0-1.2,0-1.8c0-1.9,0-3.9,0-5.8c0-2.4,0-4.8,0-7.1c0-2,0-4.1,0-6.1c0-1,0-1.9,0-2.9c0-0.1,0-0.2,0-0.4c0,0.1,0,0.2,0,0.3 c0-0.2,0.1-0.4,0.2-0.6c0,0.1-0.1,0.2-0.1,0.3C2.6,3.3,2.7,3.2,2.8,3c0,0,0-0.1,0.1-0.1C3,2.8,2.7,3.2,2.8,3 C2.8,3,2.9,2.9,2.9,2.9c0.1,0,0.1-0.1,0.2-0.1C3.3,2.6,2.9,2.9,3,2.8c0,0,0.1,0,0.1-0.1c0.1-0.1,0.3-0.2,0.5-0.2 c-0.1,0-0.2,0.1-0.3,0.1c0.2-0.1,0.4-0.1,0.7-0.2c-0.1,0-0.2,0-0.3,0c0.4,0,0.9,0,1.3,0c0.9,0,1.7,0,2.6,0c2,0,4,0,5.9,0 c0.5,0,0.9,0,1.4,0c-0.3-0.1-0.6-0.2-0.9-0.4c1.2,1.2,2.5,2.3,3.7,3.5C17.8,5.8,18,6,18.2,6.2c0.3,0.3,0.6,0.4,1,0.4 c0.4,0,0.9,0,1.3,0c1.9,0,3.8,0,5.6,0c2.2,0,4.3,0,6.5,0c1.1,0,2.3,0,3.4,0c0.1,0,0.3,0,0.4,0c-0.1,0-0.2,0-0.3,0 c0.2,0,0.4,0.1,0.7,0.2c-0.1,0-0.2-0.1-0.3-0.1c0.2,0.1,0.3,0.1,0.4,0.2c0,0,0.1,0,0.1,0.1c0.2,0.1-0.2-0.2-0.1-0.1 C36.9,6.9,37,6.9,37.1,7c0.1,0,0.1,0.1,0.1,0.2c0.1,0.2-0.1-0.2-0.1-0.1c0,0,0,0.1,0.1,0.1c0.1,0.1,0.2,0.3,0.2,0.5 c0-0.1-0.1-0.2-0.1-0.3c0.1,0.2,0.1,0.4,0.2,0.6c0-0.1,0-0.2,0-0.3c0,0.3,0,0.5,0,0.8c0,0.5,0,1,0,1.5c0,1.6,0,3.2,0,4.9 c0,2,0,4,0,5.9c0,1.7,0,3.4,0,5.1c0,0.8,0,1.6,0,2.4c0,0.1,0,0.2,0,0.4c0-0.1,0-0.2,0-0.3c0,0.2-0.1,0.4-0.2,0.6 c0-0.1,0.1-0.2,0.1-0.3c-0.1,0.2-0.1,0.3-0.2,0.4c0,0,0,0.1-0.1,0.1c-0.1,0.2,0.2-0.2,0.1-0.1c-0.1,0.1-0.1,0.1-0.2,0.2 c-0.1,0-0.1,0.1-0.2,0.1c-0.2,0.1,0.2-0.1,0.1-0.1c0,0-0.1,0-0.1,0.1c-0.1,0.1-0.3,0.2-0.5,0.2c0.1,0,0.2-0.1,0.3-0.1 C36.5,29.5,36.3,29.5,36.1,29.5c0.1,0,0.2,0,0.3,0C36.3,29.5,36.2,29.5,36.1,29.5c-0.7,0-1.3,0.6-1.3,1.2c0,0.7,0.6,1.2,1.3,1.2 c1.9,0,3.6-1.4,3.9-3.3c0-0.3,0-0.6,0-1c0-1.2,0-2.4,0-3.6c0-1.9,0-3.8,0-5.6c0-1.9,0-3.9,0-5.8c0-1.3,0-2.6,0-3.9 c0-0.2,0-0.5,0-0.7c0-1.7-1.1-3.1-2.7-3.6c-0.5-0.2-1.1-0.2-1.6-0.2c-0.5,0-1.1,0-1.6,0c-1.8,0-3.6,0-5.4,0c-2,0-3.9,0-5.9,0 c-1.2,0-2.3,0-3.5,0c-0.1,0-0.1,0-0.2,0c0.3,0.1,0.6,0.2,0.9,0.4c-1-1-2-1.9-3-2.8c-0.4-0.4-0.9-0.8-1.3-1.2 c-0.3-0.3-0.6-0.4-1-0.4c-0.2,0-0.3,0-0.5,0C12.6,0,11,0,9.4,0C7.8,0,6.2,0,4.6,0c-0.5,0-1,0-1.5,0.1C1.4,0.4,0.1,2,0,3.7 c0,0.5,0,1.1,0,1.6c0,1.3,0,2.6,0,3.9c0,1.8,0,3.5,0,5.3c0,1.9,0,3.7,0,5.6c0,1.6,0,3.2,0,4.7c0,1,0,1.9,0,2.9c0,0.1,0,0.3,0,0.4 c0,1.7,1.2,3.3,2.9,3.7C3.6,32,4.3,32,5,32c1.1,0,2.1,0,3.2,0c1.6,0,3.1,0,4.7,0c1.8,0,3.7,0,5.5,0c1.9,0,3.8,0,5.7,0 c1.7,0,3.5,0,5.2,0c1.4,0,2.7,0,4.1,0c0.8,0,1.6,0,2.3,0c0.1,0,0.2,0,0.3,0c0.7,0,1.3-0.6,1.3-1.2C37.3,30.1,36.8,29.5,36.1,29.5 z"/> <path d="M24.9,17.1c-1.1,0-2.2,0-3.3,0c-1.8,0-3.5,0-5.3,0c-0.4,0-0.8,0-1.2,0c-0.7,0-1.3,0.6-1.3,1.3c0,0.7,0.6,1.3,1.3,1.3 c1.1,0,2.2,0,3.3,0c1.8,0,3.5,0,5.3,0c0.4,0,0.8,0,1.2,0c0.7,0,1.3-0.6,1.3-1.3C26.1,17.7,25.6,17.1,24.9,17.1L24.9,17.1z"/></svg>';
    }
}
if (!function_exists('iconePasta')) {
    // doc
    // exemplo
    // echo iconePasta
    /**
     * Gera um icone de pasta
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconePasta(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 32" style="enable-background:new 0 0 40 32;" xml:space="preserve"><g><path d="M36.1,29.5c-0.3,0-0.6,0-0.9,0c-0.8,0-1.6,0-2.4,0c-1.2,0-2.3,0-3.5,0c-1.4,0-2.8,0-4.3,0c-1.6,0-3.1,0-4.7,0 c-1.6,0-3.1,0-4.7,0c-1.4,0-2.9,0-4.3,0c-1.2,0-2.4,0-3.7,0c-0.9,0-1.7,0-2.6,0c-0.4,0-0.8,0-1.2,0c-0.1,0-0.3,0-0.4,0 c0.1,0,0.2,0,0.3,0c-0.2,0-0.4-0.1-0.7-0.2c0.1,0,0.2,0.1,0.3,0.1c-0.2-0.1-0.3-0.1-0.4-0.2c0,0-0.1,0-0.1-0.1 c-0.2-0.1,0.2,0.2,0.1,0.1c-0.1-0.1-0.1-0.1-0.2-0.2c-0.1,0-0.1-0.1-0.1-0.2c-0.1-0.2,0.1,0.2,0.1,0.1c0,0,0-0.1-0.1-0.1 c-0.1-0.1-0.2-0.3-0.2-0.5c0,0.1,0.1,0.2,0.1,0.3c-0.1-0.2-0.1-0.4-0.2-0.6c0,0.1,0,0.2,0,0.3c0-0.3,0-0.6,0-0.9 c0-0.6,0-1.2,0-1.8c0-1.9,0-3.9,0-5.8c0-2.4,0-4.8,0-7.1c0-2,0-4.1,0-6.1c0-1,0-1.9,0-2.9c0-0.1,0-0.2,0-0.4c0,0.1,0,0.2,0,0.3 c0-0.2,0.1-0.4,0.2-0.6c0,0.1-0.1,0.2-0.1,0.3C2.6,3.3,2.7,3.2,2.8,3c0,0,0-0.1,0.1-0.1C3,2.8,2.7,3.2,2.8,3 C2.8,3,2.9,2.9,2.9,2.9c0.1,0,0.1-0.1,0.2-0.1C3.3,2.6,2.9,2.9,3,2.8c0,0,0.1,0,0.1-0.1c0.1-0.1,0.3-0.2,0.5-0.2 c-0.1,0-0.2,0.1-0.3,0.1c0.2-0.1,0.4-0.1,0.7-0.2c-0.1,0-0.2,0-0.3,0c0.4,0,0.9,0,1.3,0c0.9,0,1.7,0,2.6,0c2,0,4,0,5.9,0 c0.5,0,0.9,0,1.4,0c-0.3-0.1-0.6-0.2-0.9-0.4c1.2,1.2,2.5,2.3,3.7,3.5C17.8,5.8,18,6,18.2,6.2c0.3,0.3,0.6,0.4,1,0.4 c0.4,0,0.9,0,1.3,0c1.9,0,3.8,0,5.6,0c2.2,0,4.3,0,6.5,0c1.1,0,2.3,0,3.4,0c0.1,0,0.3,0,0.4,0c-0.1,0-0.2,0-0.3,0 c0.2,0,0.4,0.1,0.7,0.2c-0.1,0-0.2-0.1-0.3-0.1c0.2,0.1,0.3,0.1,0.4,0.2c0,0,0.1,0,0.1,0.1c0.2,0.1-0.2-0.2-0.1-0.1 C36.9,6.9,37,6.9,37.1,7c0.1,0,0.1,0.1,0.1,0.2c0.1,0.2-0.1-0.2-0.1-0.1c0,0,0,0.1,0.1,0.1c0.1,0.1,0.2,0.3,0.2,0.5 c0-0.1-0.1-0.2-0.1-0.3c0.1,0.2,0.1,0.4,0.2,0.6c0-0.1,0-0.2,0-0.3c0,0.3,0,0.5,0,0.8c0,0.5,0,1,0,1.5c0,1.6,0,3.2,0,4.9 c0,2,0,4,0,5.9c0,1.7,0,3.4,0,5.1c0,0.8,0,1.6,0,2.4c0,0.1,0,0.2,0,0.4c0-0.1,0-0.2,0-0.3c0,0.2-0.1,0.4-0.2,0.6 c0-0.1,0.1-0.2,0.1-0.3c-0.1,0.2-0.1,0.3-0.2,0.4c0,0,0,0.1-0.1,0.1c-0.1,0.2,0.2-0.2,0.1-0.1c-0.1,0.1-0.1,0.1-0.2,0.2 c-0.1,0-0.1,0.1-0.2,0.1c-0.2,0.1,0.2-0.1,0.1-0.1c0,0-0.1,0-0.1,0.1c-0.1,0.1-0.3,0.2-0.5,0.2c0.1,0,0.2-0.1,0.3-0.1 C36.5,29.5,36.3,29.5,36.1,29.5c0.1,0,0.2,0,0.3,0C36.3,29.5,36.2,29.5,36.1,29.5c-0.7,0-1.3,0.6-1.3,1.2c0,0.7,0.6,1.2,1.3,1.2 c1.9,0,3.6-1.4,3.9-3.3c0-0.3,0-0.6,0-1c0-1.2,0-2.4,0-3.6c0-1.9,0-3.8,0-5.6c0-1.9,0-3.9,0-5.8c0-1.3,0-2.6,0-3.9 c0-0.2,0-0.5,0-0.7c0-1.7-1.1-3.1-2.7-3.6c-0.5-0.2-1.1-0.2-1.6-0.2c-0.5,0-1.1,0-1.6,0c-1.8,0-3.6,0-5.4,0c-2,0-3.9,0-5.9,0 c-1.2,0-2.3,0-3.5,0c-0.1,0-0.1,0-0.2,0c0.3,0.1,0.6,0.2,0.9,0.4c-1-1-2-1.9-3-2.8c-0.4-0.4-0.9-0.8-1.3-1.2 c-0.3-0.3-0.6-0.4-1-0.4c-0.2,0-0.3,0-0.5,0C12.6,0,11,0,9.4,0C7.8,0,6.2,0,4.6,0c-0.5,0-1,0-1.5,0.1C1.4,0.4,0.1,2,0,3.7 c0,0.5,0,1.1,0,1.6c0,1.3,0,2.6,0,3.9c0,1.8,0,3.5,0,5.3c0,1.9,0,3.7,0,5.6c0,1.6,0,3.2,0,4.7c0,1,0,1.9,0,2.9c0,0.1,0,0.3,0,0.4 c0,1.7,1.2,3.3,2.9,3.7C3.6,32,4.3,32,5,32c1.1,0,2.1,0,3.2,0c1.6,0,3.1,0,4.7,0c1.8,0,3.7,0,5.5,0c1.9,0,3.8,0,5.7,0 c1.7,0,3.5,0,5.2,0c1.4,0,2.7,0,4.1,0c0.8,0,1.6,0,2.3,0c0.1,0,0.2,0,0.3,0c0.7,0,1.3-0.6,1.3-1.2C37.3,30.1,36.8,29.5,36.1,29.5z "/></g></svg>';
    }
}
if (!function_exists('iconeMenu')) {
    // doc
    // exemplo
    // echo iconeMenu
    /**
     * Gera um icone de menu
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeMenu(int $tamanho = 26): string
    {
        return '<svg height="' . $tamanho . '" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.1" x="0px" y="0px" viewBox="0 0 100 100"><g transform="translate(0,-952.36218)"><path style="text-indent:0;text-transform:none;direction:ltr;block-progression:tb;baseline-shift:baseline;color:#000000;enable-background:accumulate;" d="m 16,967.36218 c -3.3137,0 -6,2.6862 -6,6 0,3.3136 2.6863,6 6,6 l 68,0 c 3.3137,0 6,-2.6864 6,-6 0,-3.3138 -2.6863,-6 -6,-6 l -68,0 z m 0,29 c -3.3137,0 -6,2.6862 -6,6.00002 0,3.3136 2.6863,6 6,6 l 68,0 c 3.3137,0 6,-2.6864 6,-6 0,-3.31382 -2.6863,-6.00002 -6,-6.00002 l -68,0 z m 0,29.00002 c -3.3137,0 -6,2.6862 -6,6 0,3.3136 2.6863,6 6,6 l 68,0 c 3.3137,0 6,-2.6864 6,-6 0,-3.3138 -2.6863,-6 -6,-6 l -68,0 z" marker="none" visibility="visible" display="inline" overflow="visible"/></g></svg>';
    }
}
if (!function_exists('iconeFechar')) {
    // doc
    // exemplo
    // echo iconeFechar
    /**
     * Gera um icone de fechar
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeFechar(int $tamanho = 12): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg>';
    }
}
if (!function_exists('iconeVoltar')) {
    // doc
    // exemplo
    // echo iconeVoltar
    /**
     * Gera um icone de voltar
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeVoltar(int $tamanho = 12): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 38" style="enable-background:new 0 0 50 38;" xml:space="preserve"><path d="M48.1,17.1H6.4L20,3.3c0.8-0.7,0.8-1.9,0.1-2.7c-0.7-0.8-1.9-0.8-2.7-0.1c0,0-0.1,0.1-0.1,0.1l-16.8,17 c-0.7,0.7-0.7,1.9,0,2.7l16.8,17c0.7,0.8,1.9,0.8,2.7,0.1c0.8-0.7,0.8-1.9,0.1-2.7c0,0-0.1-0.1-0.1-0.1L6.4,20.9h41.7 c1,0,1.9-0.9,1.9-1.9C50,17.9,49.2,17.1,48.1,17.1z"/></svg>';
    }
}
if (!function_exists('iconeEmail')) {
    // doc
    // exemplo
    // echo iconeEmail
    /**
     * Gera um icone de email
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeEmail(int $tamanho = 30): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 30" style="enable-background:new 0 0 40 30;" xml:space="preserve"><path class="st0" d="M36,0H20.8h-1.7H4C1.8,0,0,1.7,0,3.8v22.5C0,28.3,1.8,30,4,30h10.9h4H36c2.2,0,4-1.7,4-3.8V3.8 C40,1.7,38.2,0,36,0z M4,2.6h15.2h1.7H36c0.1,0,0.2,0,0.3,0L20,14.8L3.6,2.7C3.7,2.7,3.8,2.6,4,2.6z M37.2,26.2 c0,0.6-0.5,1.1-1.2,1.1H18.9h-4H4c-0.6,0-1.2-0.5-1.2-1.1V5.4l16.4,12.1c0,0,0,0,0,0v0c0.1,0,0.1,0.1,0.2,0.1c0.1,0,0.1,0.1,0.2,0.1 c0.1,0,0.3,0.1,0.4,0.1c0,0,0,0,0,0s0,0,0,0c0.1,0,0.3,0,0.4-0.1c0.1,0,0.1-0.1,0.2-0.1c0.1,0,0.1-0.1,0.2-0.1v0c0,0,0,0,0,0 L37.2,5.4V26.2z"/></svg>';
    }
}
if (!function_exists('iconeEmailErro')) {
    // doc
    // exemplo
    // echo iconeEmailErro
    /**
     * Gera um icone de email de erro
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeEmailErro(int $tamanho = 30): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 30" style="enable-background:new 0 0 40 30;" xml:space="preserve"><path class="st0" d="M39.4,24l-4-6.5V3.3c0-1.8-1.6-3.3-3.5-3.3H3.5C1.6,0,0,1.5,0,3.3v19.8c0,1.8,1.6,3.3,3.5,3.3h13.2 c0.7,0,1.2-0.5,1.2-1.2c0-0.6-0.6-1.2-1.2-1.2H3.5c-0.6,0-1-0.4-1-1V4.8l14.5,10.7c0.2,0.2,0.5,0.2,0.8,0.2c0.3,0,0.5-0.1,0.8-0.2 L32.9,4.8v8.9c-0.8-0.7-1.8-1.2-2.9-1.2c-1.5,0-2.8,0.7-3.6,2l-5.9,9.6c-0.7,1.2-0.7,2.7,0,3.9c0.7,1.2,2.1,2,3.6,2h11.7 c0,0,0,0,0,0c2.3,0,4.1-1.8,4.1-3.9C40,25.3,39.8,24.6,39.4,24z M17.7,13L3.2,2.4c0.1,0,0.2,0,0.3,0h28.4c0.1,0,0.2,0,0.3,0L17.7,13 z M35.9,27.7C35.9,27.7,35.9,27.7,35.9,27.7H24.1c-0.6,0-1.1-0.3-1.4-0.8c-0.3-0.5-0.3-1.1,0-1.6l5.9-9.6c0.3-0.5,0.9-0.8,1.4-0.8 s1.1,0.3,1.4,0.8l5.8,9.5c0,0,0,0,0,0c0.2,0.3,0.3,0.5,0.3,0.9C37.5,26.9,36.8,27.7,35.9,27.7z M30,16.3c-0.7,0-1.2,0.5-1.2,1.2v5 c0,0.6,0.6,1.2,1.2,1.2c0.7,0,1.2-0.5,1.2-1.2v-5C31.2,16.8,30.7,16.3,30,16.3z M30,24.2c-0.7,0-1.2,0.5-1.2,1.2v0.2 c0,0.6,0.6,1.2,1.2,1.2c0.7,0,1.2-0.5,1.2-1.2v-0.2C31.2,24.7,30.7,24.2,30,24.2z"/></svg>';
    }
}
if (!function_exists('iconeApple')) {
    // doc
    // exemplo
    // echo iconeApple
    /**
     * Gera um icone da Apple
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeApple(int $tamanho = 24): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19.6 24" style="enable-background:new 0 0 19.6 24;" xml:space="preserve"><path d="M14.4,8c-0.5,0-1.1,0.1-1.9,0.4c0.1,0-0.8,0.3-1,0.4c-0.5,0.2-1,0.3-1.5,0.3C9.4,9.1,9,9,8.4,8.8C8.3,8.7,8.1,8.7,7.9,8.6c-0.1,0-0.4-0.2-0.5-0.2C6.7,8.1,6.3,8,6,8C4.7,8,3.6,8.8,2.9,9.9c-1.4,2.4-0.6,6.8,1.4,9.8c1.1,1.6,1.7,2.1,1.9,2.1c0.2,0,0.4-0.1,0.8-0.2l0.2-0.1c1.1-0.5,1.9-0.7,3-0.7c1.1,0,1.8,0.2,2.9,0.7l0.2,0.1c0.4,0.2,0.6,0.2,0.9,0.2c0.4,0,0.9-0.5,1.9-2c0.3-0.4,0.5-0.9,0.8-1.3c-0.1-0.1-0.3-0.2-0.4-0.4c-1.4-1.3-2.3-3.1-2.3-5.3c0-1.6,0.5-3.2,1.5-4.5C15.3,8.1,14.8,8,14.4,8z M14.5,5.8c0.8,0.1,3,0.3,4.4,2.4c-0.1,0.1-2.6,1.5-2.6,4.6c0,3.6,3.2,4.8,3.2,4.9c0,0.1-0.5,1.7-1.7,3.4c-1,1.5-2,2.9-3.7,2.9c-1.6,0-2.1-0.9-4-0.9c-1.8,0-2.4,0.9-3.9,1c-1.6,0.1-2.8-1.6-3.8-3C0.5,18-1.1,12.5,1,8.9c1.1-1.8,2.9-3,5-3c1.5,0,3,1,4,1C10.9,6.9,12.5,5.6,14.5,5.8z M13.3,3.8c-0.8,1-2.2,1.8-3.6,1.7C9.6,4.2,10.2,2.7,11,1.8c0.9-1,2.3-1.8,3.5-1.8C14.7,1.4,14.1,2.8,13.3,3.8z"/></svg>';
    }
}
if (!function_exists('iconeAndroid')) {
    // doc
    // exemplo
    // echo iconeAndroid
    /**
     * Gera um icone do Android
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeAndroid(int $tamanho = 24): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 21.6 24" style="enable-background:new 0 0 21.6 24;" xml:space="preserve"><path d="M19.2,13.2H2.4v8.4h16.8V13.2z M19.2,10.8c0-4.6-3.8-8.4-8.4-8.4s-8.4,3.8-8.4,8.4H19.2z M4.1,2.4C6,0.8,8.3,0,10.8,0c2.5,0,4.9,0.9,6.7,2.4l1.7-1.7L21,2.3l-1.7,1.7c1.5,1.9,2.4,4.3,2.4,6.7v12c0,0.7-0.5,1.2-1.2,1.2H1.2C0.5,24,0,23.5,0,22.8v-12c0-2.5,0.9-4.9,2.4-6.7L0.6,2.3l1.7-1.7L4.1,2.4L4.1,2.4z M7.2,8.4C6.5,8.4,6,7.9,6,7.2S6.5,6,7.2,6s1.2,0.5,1.2,1.2S7.9,8.4,7.2,8.4z M14.4,8.4c-0.7,0-1.2-0.5-1.2-1.2S13.7,6,14.4,6s1.2,0.5,1.2,1.2S15.1,8.4,14.4,8.4z"/></svg>';
    }
}
if (!function_exists('iconeGoogle')) {
    // doc
    // exemplo
    // echo iconeGoogle
    /**
     * Gera um icone do Google
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeGoogle(int $tamanho = 14): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M20.4,17.1V24H32c-0.5,2.9-3.5,8.6-11.6,8.6c-7,0-12.7-5.7-12.7-12.6c0-7,5.7-12.6,12.7-12.6 c4,0,6.6,1.7,8.1,3.1l5.5-5.2C30.5,2,25.9,0,20.4,0C9.1,0,0,8.9,0,20c0,11.1,9.1,20,20.4,20C32.2,40,40,31.9,40,20.5 c0-1.3-0.1-2.3-0.3-3.3H20.4L20.4,17.1z"/></svg>';
    }
}
if (!function_exists('iconeGithub')) {
    // doc
    // exemplo
    // echo iconeGithub
    /**
     * Gera um icone do Github
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeGithub(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 32" style="enable-background:new 0 0 40 32;" xml:space="preserve"><path d="M20.5,32.1c-2.8,0-6.6-0.2-8.7-0.6c-8-1.7-11.9-6.6-11.9-15.1c0-2.9,1-5.6,2.7-7.7c-0.5-2.5-0.3-5,0.7-7.4c0.2-0.6,0.7-1,1.3-1.2l0.1,0c2.9-0.5,6.3,1.1,8.7,2.6c1.9-0.5,4.1-0.7,6.6-0.7c2.3,0,4.5,0.3,6.7,0.8c4.7-3,7.3-2.9,8.5-2.6c0.7,0.3,1.2,0.7,1.4,1.3c0.9,2.4,1.1,4.9,0.6,7.4c1.7,2.1,2.7,4.8,2.7,7.6c0,8.3-4,13.4-11.9,15.1C26.5,31.8,23.7,32.1,20.5,32.1z M6.6,3.9C6.3,5.3,6.3,6.8,6.8,8.3c0.3,0.8,0.1,1.7-0.5,2.3c-1.6,1.6-2.4,3.7-2.4,5.8c0,9.1,5.3,11,11.1,11.6c0.4,0.1,2.1,0.3,4.8,0.4c1-0.1,1.9-0.1,2.7-0.2c1.3-0.1,2.4-0.2,2.7-0.2l0,0c5.4-0.7,11-2.6,11.1-11.6c-0.1-2.3-0.9-4.3-2.5-6c-0.5-0.5-0.7-1.3-0.4-2.1C33.8,7,33.9,5.5,33.5,4c-1.8,0.5-3.4,1.3-4.8,2.4c-0.6,0.4-1.3,0.5-2,0.3c-2.1-0.7-4.3-1-6.5-0.9c-2.3-0.1-4.4,0.2-6.6,0.9c-0.6,0.2-1.4,0.1-2-0.3C10.2,5.3,8.5,4.4,6.6,3.9z"/></svg>';
    }
}
if (!function_exists('iconeFacebook')) {
    // doc
    // exemplo
    // echo iconeFacebook
    /**
     * Gera um icone do Facebook
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeFacebook(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" id="Camada_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 40" style="enable-background:new 0 0 20 40;" xml:space="preserve"><path class="st0" d="M19.2,21h-6v19H4.3V21H0v-7.4h4.3V8.8c0-3.4,1.7-8.8,9-8.8l6.6,0v7.2h-4.8c-0.8,0-1.9,0.4-1.9,2v4.4H20L19.2,21 z"/></svg>';
    }
}
if (!function_exists('iconeWhatsapp')) {
    // doc
    // exemplo
    // echo iconeWhatsapp
    /**
     * Gera um icone do WhatsApp
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeWhatsapp(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M34.2,5.8C30.4,2.1,25.4,0,20.1,0C9.1,0,0.2,8.9,0.2,19.8c0,3.5,0.9,6.9,2.7,9.9L0,40l10.6-2.8 c2.9,1.6,6.2,2.4,9.5,2.4h0c11,0,19.9-8.9,19.9-19.8C40,14.5,37.9,9.6,34.2,5.8z M20.1,36.3L20.1,36.3c-3,0-5.9-0.8-8.4-2.3L11,33.7 l-6.3,1.6l1.7-6.1l-0.4-0.6c-1.7-2.6-2.5-5.7-2.5-8.8c0-9.1,7.4-16.5,16.6-16.5c4.4,0,8.6,1.7,11.7,4.8s4.8,7.3,4.8,11.7 C36.6,28.9,29.2,36.3,20.1,36.3z M29.2,24c-0.5-0.2-2.9-1.4-3.4-1.6c-0.5-0.2-0.8-0.2-1.1,0.2c-0.3,0.5-1.3,1.6-1.6,1.9 c-0.3,0.3-0.6,0.4-1.1,0.1c-0.5-0.2-2.1-0.8-4-2.5c-1.5-1.3-2.5-2.9-2.8-3.4c-0.3-0.5,0-0.7,0.2-1c0.5-0.7,1.1-1.4,1.2-1.7 c0.2-0.3,0.1-0.6,0-0.9c-0.1-0.2-1.1-2.7-1.5-3.7c-0.4-1-0.8-0.8-1.1-0.9c-0.3,0-0.6,0-1,0c-0.3,0-0.9,0.1-1.3,0.6 C11.2,11.8,10,13,10,15.4s1.8,4.8,2,5.1s3.5,5.3,8.5,7.5c1.2,0.5,2.1,0.8,2.8,1c1.2,0.4,2.3,0.3,3.1,0.2c1-0.1,2.9-1.2,3.4-2.4 c0.4-1.2,0.4-2.1,0.3-2.4C30,24.3,29.7,24.2,29.2,24z"/></svg>';
    }
}
if (!function_exists('iconeYoutube')) {
    // doc
    // exemplo
    // echo iconeYoutube
    /**
     * Gera um icone do YouTube
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeYoutube(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 20 16" style="enable-background:new 0 0 20 16;" xml:space="preserve"><path class="st0" d="M16.2,0.5c-4.1-0.7-8.3-0.7-12.4,0C1.5,1,0,2.5,0,4.5v7c0,2,1.5,3.5,3.8,4c2,0.3,4.1,0.5,6.2,0.5 c2.1,0,4.1-0.2,6.2-0.5c2.3-0.4,3.8-2,3.8-4v-7C20,2.5,18.5,0.9,16.2,0.5z M13.3,8.4l-5,3c-0.1,0-0.2,0.1-0.3,0.1 c-0.1,0-0.2,0-0.2-0.1c-0.2-0.1-0.3-0.3-0.2-0.5V5c0-0.2,0.1-0.3,0.2-0.4c0.2-0.1,0.4-0.1,0.5,0l5,3c0.2,0.2,0.3,0.5,0.2,0.7 C13.4,8.3,13.3,8.4,13.3,8.4L13.3,8.4z"/></svg>';
    }
}
if (!function_exists('iconeTwitter')) {
    // doc
    // exemplo
    // echo iconeTwitter
    /**
     * Gera um icone do Twitter
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeTwitter(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>';
    }
}
if (!function_exists('iconePinterest')) {
    // doc
    // exemplo
    // echo iconePinterest
    /**
     * Gera um icone do Pinterest
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconePinterest(int $tamanho = 19): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>';
    }
}
if (!function_exists('iconeTwitch')) {
    // doc
    // exemplo
    // echo iconeTwitch
    /**
     * Gera um icone da Twitch
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeTwitch(int $tamanho = 17): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M2.149 0l-1.612 4.119v16.836h5.731v3.045h3.224l3.045-3.045h4.657l6.269-6.269v-14.686h-21.314zm19.164 13.612l-3.582 3.582h-5.731l-3.045 3.045v-3.045h-4.836v-15.045h17.194v11.463zm-3.582-7.343v6.262h-2.149v-6.262h2.149zm-5.731 0v6.262h-2.149v-6.262h2.149z" fill-rule="evenodd" clip-rule="evenodd"/></svg>';
    }
}
if (!function_exists('iconeVimeo')) {
    // doc
    // exemplo
    // echo iconeVimeo
    /**
     * Gera um icone do Vimeo
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeVimeo(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22.875 10.063c-2.442 5.217-8.337 12.319-12.063 12.319-3.672 0-4.203-7.831-6.208-13.043-.987-2.565-1.624-1.976-3.474-.681l-1.128-1.455c2.698-2.372 5.398-5.127 7.057-5.28 1.868-.179 3.018 1.098 3.448 3.832.568 3.593 1.362 9.17 2.748 9.17 1.08 0 3.741-4.424 3.878-6.006.243-2.316-1.703-2.386-3.392-1.663 2.673-8.754 13.793-7.142 9.134 2.807z"/></svg>';
    }
}
if (!function_exists('iconeInstagram')) {
    // doc
    // exemplo
    // echo iconeInstagram
    /**
     * Gera um icone do Instagram
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeInstagram(int $tamanho = 17): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>';
    }
}
if (!function_exists('iconeImagem')) {
    // doc
    // exemplo
    // echo iconeImagem
    /**
     * Gera um icone de imagem
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeImagem(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 30" style="enable-background:new 0 0 40 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path class="st0" d="M2.7,952.4c-1.5,0-2.7,1.2-2.7,2.6v24.7c0,1.5,1.2,2.6,2.7,2.6h34.7c1.5,0,2.7-1.2,2.7-2.6V955 c0-1.5-1.2-2.6-2.7-2.6H2.7z M2.7,954.1h34.7c0.5,0,0.9,0.4,0.9,0.9v18.5l-7.4-5.9c-0.3-0.2-0.7-0.3-1.1,0l-6.6,4.5l-8.8-7.1 c-0.2-0.1-0.4-0.2-0.7-0.2c-0.1,0-0.3,0.1-0.4,0.2l-11.5,7.9V955C1.8,954.5,2.2,954.1,2.7,954.1L2.7,954.1z M23.1,958.5 c-2,0-3.6,1.6-3.6,3.5s1.6,3.5,3.6,3.5s3.6-1.6,3.6-3.5S25.1,958.5,23.1,958.5z M23.1,960.3c1,0,1.8,0.8,1.8,1.8 c0,1-0.8,1.8-1.8,1.8c-1,0-1.8-0.8-1.8-1.8C21.3,961.1,22.1,960.3,23.1,960.3z M13.7,966.7l8.8,7.1c0.3,0.2,0.7,0.3,1.1,0l6.6-4.5 l8.1,6.4v4c0,0.5-0.4,0.9-0.9,0.9H2.7c-0.5,0-0.9-0.4-0.9-0.9v-4.8L13.7,966.7L13.7,966.7z"/></g></svg>';
    }
}
if (!function_exists('iconeAmigo')) {
    // doc
    // exemplo
    // echo iconeAmigo
    /**
     * Gera um icone de amigo
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeAmigo(int $tamanho = 9)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 26" style="enable-background:new 0 0 40 26;" xml:space="preserve"><ellipse class="st0" cx="20" cy="5.3" rx="5.4" ry="5.3"/><ellipse class="st0" cx="32" cy="6.5" rx="4" ry="4"/><path class="st0" d="M40,19.8c-0.8-5-3.4-8-7.9-8c-1.7,0-3.1,0.4-4.3,1.3c0.1,0.1,0.3,0.3,0.5,0.4c1.9,2,3.2,4.9,3.9,8.6 c0,0.1,0,0.3,0,0.4C36.5,22.6,40.3,21.8,40,19.8z"/><ellipse class="st0" cx="8" cy="6.5" rx="4" ry="4"/><path class="st0" d="M11.9,13.6c0.1-0.1,0.3-0.3,0.4-0.4c-1.2-0.8-2.6-1.3-4.3-1.3c-4.5,0-7.1,2.9-7.9,8c-0.3,2,3.6,2.7,7.9,2.7 c0-0.1,0-0.3,0-0.4C8.6,18.5,9.9,15.6,11.9,13.6z"/><path class="st0" d="M20,26c-5.9,0-11-1-10.6-3.6C10.5,15.7,14,11.8,20,11.8s9.5,4,10.6,10.6C31,25,25.9,26,20,26z"/></svg>';
    }
}
if (!function_exists('iconeConfig')) {
    // doc
    // exemplo
    // echo iconeConfig
    /**
     * Gera um icone de configuração
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeConfig(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" x="0px" y="0px" viewBox="0 0 35 35" style="enable-background:new 0 0 35 35;" xml:space="preserve"><path d="M30.4,18.3c-0.4,1.7-1.9,3-3.7,3c-1.8,0-3.4-1.3-3.7-3H2.3c-0.4,0-0.8-0.3-0.8-0.8s0.3-0.8,0.8-0.8h20.6 c0.4-1.7,1.9-3,3.7-3c1.8,0,3.4,1.3,3.7,3h2.4c0.4,0,0.8,0.3,0.8,0.8s-0.3,0.8-0.8,0.8H30.4z M26.6,15.2c-1.3,0-2.3,1-2.3,2.3 c0,1.3,1,2.3,2.3,2.3c1.3,0,2.3-1,2.3-2.3C28.9,16.2,27.9,15.2,26.6,15.2z M12.1,4.6c-0.4,1.7-1.9,3-3.7,3c-1.8,0-3.4-1.3-3.7-3 H2.3c-0.4,0-0.8-0.3-0.8-0.8S1.9,3,2.3,3h2.4C5,1.3,6.5,0,8.4,0c1.8,0,3.4,1.3,3.7,3h20.6c0.4,0,0.8,0.3,0.8,0.8s-0.3,0.8-0.8,0.8 H12.1L12.1,4.6z M8.4,1.5c-1.3,0-2.3,1-2.3,2.3c0,1.3,1,2.3,2.3,2.3c1.3,0,2.3-1,2.3-2.3C10.7,2.5,9.6,1.5,8.4,1.5z M18.9,32 c-0.4,1.7-1.9,3-3.7,3c-1.8,0-3.4-1.3-3.7-3H2.3c-0.4,0-0.8-0.3-0.8-0.8s0.3-0.8,0.8-0.8h9.2c0.4-1.7,1.9-3,3.7-3 c1.8,0,3.4,1.3,3.7,3h13.8c0.4,0,0.8,0.3,0.8,0.8S33.1,32,32.7,32H18.9z M15.2,28.9c-1.3,0-2.3,1-2.3,2.3c0,1.3,1,2.3,2.3,2.3 c1.3,0,2.3-1,2.3-2.3C17.5,29.9,16.5,28.9,15.2,28.9z" /></svg>';
    }
}
if (!function_exists('iconeOpcao')) {
    // doc
    // exemplo
    // echo iconeOpcao
    /**
     * Gera um icone de opção
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeOpcao(int $tamanho = 12): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 10 40" style="enable-background:new 0 0 10 40;" xml:space="preserve"><circle class="st0" cx="5" cy="5" r="5"/><circle class="st0" cx="5" cy="20" r="5"/><circle class="st0" cx="5" cy="35" r="5"/></svg>';
    }
}
if (!function_exists('iconeBuscar')) {
    // doc
    // exemplo
    // echo iconeBuscar
    /**
     * Gera um icone de buscar
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeBuscar(int $tamanho = 17): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve"><path class="st0" fill="none" d="M12.97,3.17c-5.35,0-9.72,4.3-9.72,9.57s4.37,9.57,9.72,9.57s9.72-4.3,9.72-9.57S18.32,3.17,12.97,3.17z"/><path class="st1" d="M23.64,20.73c1.66-2.17,2.68-4.87,2.68-7.8C26.32,5.8,20.39,0,13.14,0S0,5.8,0,12.93s5.89,12.93,13.14,12.93 c2.71,0,5.25-0.83,7.35-2.23c0.03,0.03,0.03,0.03,0.07,0.07l5.76,5.67c0.41,0.4,0.98,0.63,1.52,0.63s1.12-0.2,1.52-0.63 c0.85-0.83,0.85-2.17,0-3L23.64,20.73z M13.14,21.63c-4.88,0-8.84-3.9-8.84-8.7s3.96-8.7,8.84-8.7s8.84,3.9,8.84,8.7 S18.02,21.63,13.14,21.63z"/></svg>';
    }
}
if (!function_exists('iconeFiltrar')) {
    // doc
    // exemplo
    // echo iconeFiltrar
    /**
     * Gera um icone de filtrar
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeFiltrar(int $tamanho = 16): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><g><g><path d="M50.8,94.6L45,90.7c-3.1-2-4.9-5.5-4.9-9.2V53c0-0.7-0.2-1.3-0.6-1.8L17.6,21.4c-2.5-3.3-2.8-7.7-0.9-11.4    c1.9-3.7,5.6-6,9.8-6h55c4.2,0,7.9,2.3,9.8,6c1.9,3.7,1.5,8.1-0.9,11.4L68.4,51.1c-0.4,0.5-0.6,1.2-0.6,1.8v32.5    c0,4-2.2,7.7-5.8,9.6c-1.6,0.9-3.4,1.3-5.2,1.3C54.7,96.4,52.6,95.8,50.8,94.6z M26.4,11.8c-1.7,0-2.5,1.2-2.7,1.7    c-0.3,0.5-0.7,1.8,0.3,3.2l21.9,29.8c1.4,1.9,2.1,4.1,2.1,6.5v28.6c0,1,0.5,2,1.4,2.6l5.9,3.9c1.4,0.9,2.6,0.4,3.1,0.1    c0.5-0.3,1.6-1,1.6-2.7V53c0-2.3,0.7-4.6,2.1-6.5l21.9-29.8c1-1.4,0.5-2.7,0.3-3.2c-0.3-0.5-1-1.7-2.7-1.7H26.4z"/></g></g></svg>';
    }
}
if (!function_exists('iconeOrdenar')) {
    // doc
    // exemplo
    // echo iconeOrdenar
    /**
     * Gera um icone de ordernar
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeOrdenar(int $tamanho = 18): string
    {
        return '
        <svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M93.5,26.375c0,2.761-2.238,5-5,5h-77c-2.762,0-5-2.239-5-5v-4.667c0-2.761,2.238-5,5-5h77c2.762,0,5,2.239,5,5V26.375z"/><path d="M78.5,52.333c0,2.762-2.238,5-5,5h-47c-2.762,0-5-2.238-5-5v-4.666c0-2.762,2.238-5,5-5h47c2.762,0,5,2.238,5,5V52.333z"/><path d="M63.5,78.292c0,2.761-2.238,5-5,5h-17c-2.762,0-5-2.239-5-5v-4.667c0-2.761,2.238-5,5-5h17c2.762,0,5,2.239,5,5V78.292z"/></svg>';
    }
}
if (!function_exists('iconeNotificacao')) {
    // doc
    // exemplo
    // echo iconeNotificacao
    /**
     * Gera um icone de notificação
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeNotificacao(int $tamanho = 30): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve"><path class="st0" d="M12.55,3.58c-4.23,1.09-7.36,4.93-7.36,9.51v6.54c0,0.6-0.49,1.09-1.09,1.09c-1.21,0-2.18,0.98-2.18,2.18 v1.64c0,0.3,0.24,0.54,0.55,0.54h25.09c0.3,0,0.55-0.25,0.55-0.54v-1.64c0-1.2-0.98-2.18-2.18-2.18c-0.6,0-1.09-0.49-1.09-1.09 v-6.54c0-4.57-3.13-8.42-7.36-9.51V2.46C17.45,1.1,16.36,0,15,0c-1.35,0-2.45,1.1-2.45,2.46V3.58z M11.18,26.18h7.64 c0,2.11-1.71,3.82-3.82,3.82S11.18,28.29,11.18,26.18z"/></svg>';
    }
}
if (!function_exists('iconeNotificacaoLimpar')) {
    // doc
    // exemplo
    // echo iconeNotificacaoLimpar
    /**
     * Gera um icone de limpar notificação
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeNotificacaoLimpar(int $tamanho = 12): string
    {
        return '<svg  height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 25" style="enable-background:new 0 0 40 25;" xml:space="preserve"><path class="st0" d="M29.6,1.5C29.6,0.7,29,0,28.1,0H1.5C0.7,0,0,0.7,0,1.5c0,0.8,0.7,1.5,1.5,1.5h26.6C29,2.9,29.6,2.3,29.6,1.5z"/><path class="st0" d="M38.5,22.1H11.9c-0.8,0-1.5,0.7-1.5,1.5c0,0.8,0.7,1.5,1.5,1.5h26.6c0.8,0,1.5-0.7,1.5-1.5 S39.3,22.1,38.5,22.1z"/><path class="st0" d="M34.8,12.5c0-0.8-0.7-1.5-1.5-1.5H6.7c-0.8,0-1.5,0.7-1.5,1.5c0,0.8,0.7,1.5,1.5,1.5h26.6 C34.1,14,34.8,13.3,34.8,12.5z"/></svg>';
    }
}
if (!function_exists('iconeImpressora')) {
    // doc
    // exemplo
    // echo iconeImpressora
    /**
     * Gera um icone de impressora
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeImpressora(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" viewBox="0 0 700 700" xmlns="http://www.w3.org/2000/svg"><g><path d="m542.5 437.5h-30.863c-9.7578 0.03125-19.328-2.668-27.629-7.7969-8.3008-5.1289-15-12.48-19.336-21.223l-15.652-31.309c-1.4492-2.9102-3.6797-5.3594-6.4453-7.0703-2.7695-1.7109-5.957-2.6094-9.2109-2.6016h-166.73c-3.2539-0.007812-6.4414 0.89062-9.2109 2.6016-2.7656 1.7109-4.9961 4.1602-6.4453 7.0703l-15.652 31.309c-4.3359 8.7422-11.035 16.094-19.336 21.223-8.3008 5.1289-17.871 7.8281-27.629 7.7969h-30.863c-13.918-0.015625-27.266-5.5508-37.105-15.395-9.8438-9.8398-15.379-23.188-15.395-37.105v-140c0.015625-13.918 5.5508-27.266 15.395-37.105 9.8398-9.8438 23.188-15.379 37.105-15.395h385c13.918 0.015625 27.266 5.5508 37.105 15.395 9.8438 9.8398 15.379 23.188 15.395 37.105v140c-0.015625 13.918-5.5508 27.266-15.395 37.105-9.8398 9.8438-23.188 15.379-37.105 15.395zm-275.86-105h166.73-0.003907c9.7578-0.03125 19.328 2.668 27.629 7.7969 8.3008 5.1289 15 12.48 19.336 21.223l15.652 31.309c1.4492 2.9102 3.6797 5.3594 6.4453 7.0703 2.7695 1.7109 5.957 2.6094 9.2109 2.6016h30.863c4.6406-0.003906 9.0898-1.8477 12.371-5.1289s5.125-7.7305 5.1289-12.371v-140c-0.003906-4.6406-1.8477-9.0898-5.1289-12.371s-7.7305-5.125-12.371-5.1289h-385c-4.6406 0.003906-9.0898 1.8477-12.371 5.1289s-5.125 7.7305-5.1289 12.371v140c0.003906 4.6406 1.8477 9.0898 5.1289 12.371s7.7305 5.125 12.371 5.1289h30.863c3.2539 0.007812 6.4414-0.89062 9.2109-2.6016 2.7656-1.7109 4.9961-4.1602 6.4453-7.0703l15.652-31.309c4.3359-8.7422 11.035-16.094 19.336-21.223 8.3008-5.1289 17.871-7.8281 27.629-7.7969z"/><path d="m490 227.5h-280c-4.6406 0-9.0938-1.8438-12.375-5.125s-5.125-7.7344-5.125-12.375v-124.5c0.015625-13.391 5.3398-26.223 14.809-35.691s22.301-14.793 35.691-14.809h214c13.391 0.015625 26.223 5.3398 35.691 14.809s14.793 22.301 14.809 35.691v124.5c0 4.6406-1.8438 9.0938-5.125 12.375s-7.7344 5.125-12.375 5.125zm-262.5-35h245v-107c-0.003906-4.1094-1.6406-8.0469-4.5469-10.953s-6.8438-4.543-10.953-4.5469h-214c-4.1094 0.003906-8.0469 1.6406-10.953 4.5469s-4.543 6.8438-4.5469 10.953z"/><path d="m455 525h-210c-13.918-0.015625-27.266-5.5508-37.105-15.395-9.8438-9.8398-15.379-23.188-15.395-37.105v-52.5c0-6.2539 3.3359-12.031 8.75-15.156s12.086-3.125 17.5 0 8.75 8.9023 8.75 15.156v52.5c0.003906 4.6406 1.8477 9.0898 5.1289 12.371s7.7305 5.125 12.371 5.1289h210c4.6406-0.003906 9.0898-1.8477 12.371-5.1289s5.125-7.7305 5.1289-12.371v-52.5c0-6.2539 3.3359-12.031 8.75-15.156s12.086-3.125 17.5 0 8.75 8.9023 8.75 15.156v52.5c-0.015625 13.918-5.5508 27.266-15.395 37.105-9.8398 9.8438-23.188 15.379-37.105 15.395z"/><path d="m507.5 297.5h-52.5c-6.2539 0-12.031-3.3359-15.156-8.75s-3.125-12.086 0-17.5 8.9023-8.75 15.156-8.75h52.5c6.2539 0 12.031 3.3359 15.156 8.75s3.125 12.086 0 17.5-8.9023 8.75-15.15 8.75z"/></g></svg>';
    }
}
if (!function_exists('iconeFlechaCima')) {
    // doc
    // exemplo
    // echo iconeFlechaCima
    /**
     * Gera um icone de flexa para cima
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeFlechaCima(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 38 50" style="enable-background:new 0 0 38 50;" xml:space="preserve"><path d="M20.8,48.1V6.4L34.6,20c0.7,0.8,1.9,0.8,2.7,0.1c0.8-0.7,0.8-1.9,0.1-2.7l-0.1-0.1l-17-16.8c-0.7-0.7-1.9-0.7-2.7,0 l-17,16.8C-0.2,18-0.2,19.2,0.5,20c0.7,0.8,1.9,0.8,2.7,0.1L3.3,20L17,6.4v41.7c0,1,0.9,1.9,1.9,1.9C20,50,20.8,49.2,20.8,48.1z"/></svg>';
    }
}
if (!function_exists('iconeFlechaBaixo')) {
    // doc
    // exemplo
    // echo iconeFlechaBaixo
    /**
     * Gera um icone de flecha para baixo
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeFlechaBaixo(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 38 50" style="enable-background:new 0 0 38 50;" xml:space="preserve"><path d="M17.1,1.8v41.7L3.3,29.9c-0.7-0.8-1.9-0.8-2.7-0.1c-0.8,0.7-0.8,1.9-0.1,2.7l0.1,0.1l17,16.8c0.7,0.7,1.9,0.7,2.7,0l17-16.8 c0.8-0.7,0.8-1.9,0.1-2.7c-0.7-0.8-1.9-0.8-2.7-0.1l-0.1,0.1L20.9,43.5V1.8c0-1-0.9-1.9-1.9-1.9C17.9-0.1,17.1,0.7,17.1,1.8z"/></svg>';
    }
}
if (!function_exists('iconeFlechaEsquerda')) {
    // doc
    // exemplo
    // echo iconeFlechaEsquerda
    /**
     * Gera um icone de flecha para esquerda
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeFlechaEsquerda(int $tamanho = 10): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 38" style="enable-background:new 0 0 50 38;" xml:space="preserve"><path d="M48.1,17.1H6.4L20,3.3c0.8-0.7,0.8-1.9,0.1-2.7c-0.7-0.8-1.9-0.8-2.7-0.1c0,0-0.1,0.1-0.1,0.1l-16.8,17 c-0.7,0.7-0.7,1.9,0,2.7l16.8,17c0.7,0.8,1.9,0.8,2.7,0.1c0.8-0.7,0.8-1.9,0.1-2.7c0,0-0.1-0.1-0.1-0.1L6.4,20.9h41.7 c1,0,1.9-0.9,1.9-1.9C50,17.9,49.2,17.1,48.1,17.1z"/></svg>';
    }
}
if (!function_exists('iconeFlechaDireita')) {
    // doc
    // exemplo
    // echo iconeFlechaDireita
    /**
     * Gera um icone de flexa para direita
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeFlechaDireita(int $tamanho = 10): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 38" style="enable-background:new 0 0 50 38;" xml:space="preserve"><path d="M1.9,20.8h41.7L30,34.6c-0.8,0.7-0.8,1.9-0.1,2.7c0.7,0.8,1.9,0.8,2.7,0.1l0.1-0.1l16.8-17c0.7-0.7,0.7-1.9,0-2.7l-16.8-17 C32-0.2,30.8-0.2,30,0.5c-0.8,0.7-0.8,1.9-0.1,2.7L30,3.3L43.6,17H1.9c-1,0-1.9,0.9-1.9,1.9C0,20,0.8,20.8,1.9,20.8z"/></svg>
        ';
    }
}
if (!function_exists('iconeSetaBaixo')) {
    // doc
    // exemplo
    // echo iconeSetaBaixo
    /**
     * Gera um icone de seta para baixo
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeSetaBaixo(int $tamanho = 5)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 16" style="enable-background:new 0 0 30 16;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M15.3,968.3c0.6-0.1,1.1-0.3,1.6-0.7l12.1-10.2c1.2-1,1.4-2.8,0.4-4c-1-1.2-2.8-1.4-4-0.4c0,0-0.1,0.1-0.1,0.1L15,961.8 l-10.2-8.7c-1.2-1.1-3-1-4.1,0.2s-1,3,0.2,4c0.1,0,0.1,0.1,0.1,0.1l12.1,10.2C13.7,968.2,14.5,968.4,15.3,968.3L15.3,968.3z"/></g></svg>';
    }
}
if (!function_exists('iconeSetaCima')) {
    // doc
    // exemplo
    // echo iconeSetaCima
    /**
     * Gera um icone de seta para cima
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeSetaCima(int $tamanho = 5)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 16" style="enable-background:new 0 0 30 16;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M14.7,952.4c-0.6,0.1-1.1,0.3-1.6,0.7L1.1,963.3c-1.2,1-1.4,2.8-0.4,4c1,1.2,2.8,1.4,4,0.4c0,0,0.1-0.1,0.1-0.1l10.2-8.7 l10.2,8.7c1.2,1.1,3,1,4.1-0.2s1-3-0.2-4c0,0-0.1-0.1-0.1-0.1L16.9,953C16.3,952.5,15.5,952.3,14.7,952.4L14.7,952.4z"/></g></svg>';
    }
}
if (!function_exists('iconeSetaEsquerda')) {
    // doc
    // exemplo
    // echo iconeSetaEsquerda 10
    /**
     * Gera um icone de seta para esquerda
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeSetaEsquerda(int $tamanho = 5)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 30" style="enable-background:new 0 0 16 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M0,967.6c0.1,0.6,0.3,1.2,0.7,1.6l10.2,12.1c1,1.2,2.8,1.4,4,0.4c1.2-1,1.4-2.8,0.4-4c0,0-0.1-0.1-0.1-0.1l-8.7-10.2 l8.7-10.2c1.1-1.2,1-3-0.2-4.1s-3-1-4,0.2c0,0-0.1,0.1-0.1,0.1L0.7,965.5C0.2,966.1-0.1,966.8,0,967.6L0,967.6z"/></g></svg>';
    }
}
if (!function_exists('iconeSetaDireita')) {
    // doc
    // exemplo
    // echo iconeSetaDireita 10
    /**
     * Gera um icone de seta para direita
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeSetaDireita(int $tamanho = 5)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 30" style="enable-background:new 0 0 16 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M16,967.1c-0.1-0.6-0.3-1.2-0.7-1.6L5.1,953.4c-1-1.2-2.8-1.4-4-0.4c-1.2,1-1.4,2.8-0.4,4c0,0,0.1,0.1,0.1,0.1l8.7,10.2 l-8.7,10.2c-1.1,1.2-1,3,0.2,4.1s3,1,4-0.2c0,0,0.1-0.1,0.1-0.1l10.2-12.1C15.8,968.6,16.1,967.9,16,967.1L16,967.1z"/></g></svg>';
    }
}

if (!function_exists('iconeBug')) {
    // doc
    // exemplo
    // echo iconeBug
    /**
     * Gera um icone de bug
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeBug(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 33.75" x="0px" y="0px"><path d="M9.13398 1.76793C8.85783 1.28964 9.02171 0.678045 9.5 0.401903C9.97829 0.12576 10.5899 0.289636 10.866 0.767928L12.5501 3.68489C13.6126 3.24359 14.7778 2.99998 16 2.99998C17.2222 2.99998 18.3874 3.24359 19.4499 3.68489L21.134 0.767938C21.4101 0.289645 22.0217 0.12577 22.5 0.401913C22.9783 0.678055 23.1422 1.28964 22.866 1.76794L21.2001 4.65341C22.4102 5.51155 23.3991 6.66118 24.0645 7.99998H27.5858L30.2929 5.29272C30.6834 4.90218 31.3165 4.90216 31.7071 5.29267C32.0976 5.68318 32.0976 6.31635 31.7071 6.70689L28.7071 9.70707C28.5196 9.89462 28.2652 9.99998 28 9.99998H24.777C24.8587 10.3602 24.9189 10.7287 24.9559 11.1039H7.04405C7.08114 10.7287 7.14128 10.3602 7.22302 9.99998H4C3.73478 9.99998 3.48043 9.89463 3.29289 9.70709L0.292711 6.70691C-0.0978143 6.31638 -0.0978143 5.68322 0.292711 5.29269C0.683234 4.90217 1.3164 4.90217 1.70692 5.29269L4.41421 7.99998H7.93552C8.60085 6.66118 9.58975 5.51154 10.7999 4.65341L9.13398 1.76793Z"/><path d="M25 16V18C25 18.6873 24.923 19.3566 24.777 19.9998H27.9998C28.265 19.9998 28.5194 20.1052 28.7069 20.2927L31.7071 23.2929C32.0976 23.6834 32.0976 24.3166 31.7071 24.7071C31.3166 25.0976 30.6834 25.0976 30.2929 24.7071L27.5856 21.9998H24.0646C22.5919 24.9633 19.5338 27 16 27C12.4662 27 9.4081 24.9633 7.93543 21.9998H4.41421L1.70692 24.7071C1.3164 25.0976 0.683234 25.0976 0.292711 24.7071C-0.0978143 24.3166 -0.0978143 23.6834 0.292711 23.2929L3.29289 20.2927C3.48043 20.1052 3.73478 19.9998 4 19.9998H7.22297C7.07705 19.3566 7 18.6873 7 18V16H4C3.44772 16 3 15.5523 3 15C3 14.4477 3.44772 14 4 14H7V13.1039H14.9998V17C14.9998 17.5523 15.4475 18 15.9998 18C16.5521 18 16.9998 17.5523 16.9998 17V13.1039H25V14H27.9998C28.5521 14 28.9998 14.4477 28.9998 15C28.9998 15.5523 28.5521 16 27.9998 16H25Z"/></svg>';
    }
}
if (!function_exists('iconeConsole')) {
    // doc
    // exemplo
    // echo iconeConsole
    /**
     * Gera um icone de console
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeConsole(int $tamanho = 22): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 27 40" style="enable-background:new 0 0 27 40;" xml:space="preserve"><path class="st0" d="M19.4,40c4.2,0,7.6-3.4,7.6-7.7V1.2C27,0.6,26.5,0,25.8,0H1.2C0.5,0,0,0.6,0,1.2v37.5C0,39.4,0.5,40,1.2,40 H19.4z M2.5,2.5h22.1v29.8c0,2.9-2.3,5.2-5.1,5.2h-17V2.5z"/><path class="st0" d="M23.3,16.1V5c0-0.7-0.5-1.2-1.2-1.2H4.9C4.2,3.8,3.7,4.3,3.7,5v17.5c0,0.7,0.5,1.2,1.2,1.2h10.9 C19.9,23.7,23.3,20.3,23.3,16.1z M20.9,16.1c0,2.9-2.3,5.2-5.1,5.2H6.1v-15h14.7V16.1z"/><path class="st0" d="M9.8,29.1H8.9v-0.9c0-0.7-0.5-1.2-1.2-1.2s-1.2,0.6-1.2,1.2v0.9H5.5c-0.7,0-1.2,0.6-1.2,1.2s0.5,1.2,1.2,1.2 h0.9v0.9c0,0.7,0.5,1.2,1.2,1.2s1.2-0.6,1.2-1.2v-0.9h0.9c0.7,0,1.2-0.6,1.2-1.2S10.5,29.1,9.8,29.1z"/><ellipse class="st0" cx="19.3" cy="28.1" rx="1.2" ry="1.2"/><ellipse class="st0" cx="19.3" cy="32.5" rx="1.2" ry="1.2"/><ellipse class="st0" cx="17.2" cy="30.3" rx="1.2" ry="1.2"/><ellipse class="st0" cx="21.5" cy="30.3" rx="1.2" ry="1.2"/></svg>';
    }
}
if (!function_exists('iconeAcessorio')) {
    // doc
    // exemplo
    // echo iconeAcessorio
    /**
     * Gera um icone de acessorio
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeAcessorio(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 20" style="enable-background:new 0 0 40 20;" xml:space="preserve"><path d="M23.1,0h7.5C35.8,0.2,40,4.6,40,10c0,5.5-4.3,10-9.7,10c-3.6,0-6.8-2.1-8.5-5.2h-4.5c-1.7,2.7-4.6,4.5-7.9,4.5 C4.2,19.4,0,15,0,9.7S4.2,0,9.4,0h7.5 M23.5,12.3c0.9,3,3.6,5.2,6.8,5.2c4,0,7.2-3.3,7.2-7.4s-3.2-7.4-7.2-7.4c-0.1,0-0.2,0-0.3,0 l-20.6,0c-3.8,0-6.9,3.2-6.9,7.1s3.1,7.1,6.9,7.1c2.9,0,5.4-1.9,6.4-4.5H23.5z M18.8,0l2.5,0 M10.6,8.4h1.9c0.3,0,0.6,0.3,0.6,0.7 v1.3c0,0.4-0.3,0.7-0.6,0.7h-1.9v1.9c0,0.4-0.3,0.6-0.6,0.6H8.8c-0.3,0-0.6-0.3-0.6-0.6V11H6.3c-0.3,0-0.6-0.3-0.6-0.7V9 c0-0.4,0.3-0.7,0.6-0.7h1.9V6.5c0-0.4,0.3-0.6,0.6-0.6H10c0.3,0,0.6,0.3,0.6,0.6V8.4z M30.6,8.4c-0.7,0-1.2-0.6-1.2-1.3 s0.6-1.3,1.2-1.3s1.2,0.6,1.2,1.3S31.3,8.4,30.6,8.4z M30.6,13.5c-0.7,0-1.2-0.6-1.2-1.3c0-0.7,0.6-1.3,1.2-1.3s1.2,0.6,1.2,1.3 C31.9,13,31.3,13.5,30.6,13.5z M33.1,11c-0.7,0-1.2-0.6-1.2-1.3c0-0.7,0.6-1.3,1.2-1.3s1.2,0.6,1.2,1.3C34.4,10.4,33.8,11,33.1,11z M28.1,11c-0.7,0-1.2-0.6-1.2-1.3c0-0.7,0.6-1.3,1.2-1.3s1.2,0.6,1.2,1.3C29.4,10.4,28.8,11,28.1,11z"/></svg>';
    }
}
if (!function_exists('iconeJogo')) {
    // doc
    // exemplo
    // echo iconeJogo
    /**
     * Gera um icone de jogo
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeJogo(int $tamanho = 21): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M20,0C9,0,0,9,0,20s9,20,20,20s20-9,20-20S31,0,20,0z M20,37.5c-9.7,0-17.5-7.9-17.5-17.5S10.3,2.5,20,2.5 S37.5,10.4,37.5,20S29.6,37.5,20,37.5z"/><path class="st0" d="M20,13.8c-3.4,0-6.2,2.8-6.2,6.2s2.8,6.2,6.2,6.2s6.2-2.8,6.2-6.2S23.4,13.8,20,13.8z M20,23.8 c-2.1,0-3.8-1.7-3.8-3.8s1.7-3.8,3.8-3.8s3.8,1.7,3.8,3.8S22.1,23.8,20,23.8z"/><path class="st0" d="M20,5c-0.7,0-1.2,0.6-1.2,1.2s0.6,1.2,1.2,1.2c3.3,0,6.5,1.3,8.8,3.7c0.5,0.5,1.3,0.5,1.8,0 c0.5-0.5,0.5-1.3,0-1.8C27.8,6.6,24,5,20,5z"/><path class="st0" d="M20,32.5c-3.3,0-6.5-1.3-8.8-3.7c-0.5-0.5-1.3-0.5-1.8,0c-0.5,0.5-0.5,1.3,0,1.8C12.2,33.4,16,35,20,35 c0.7,0,1.2-0.6,1.2-1.2S20.7,32.5,20,32.5z"/></svg>';
    }
}
if (!function_exists('iconeCarta')) {
    // doc
    // exemplo
    // echo iconeCarta
    /**
     * Gera um icone de carta
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeCarta(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"  viewBox="0 0 40 30" style="enable-background:new 0 0 40 30;" xml:space="preserve"><path class="st0" d="M3.5,6.1L10.2,5v18.2c0,1.9,0.9,3.3,2.8,3.3h0.4L7,27.6c-0.5,0.1-1.1-0.3-1.2-0.8L2.3,7.3 C2.3,6.7,2.9,6.2,3.5,6.1 M16.5,23.9h-3.4c-0.5,0-0.2-0.2-0.2-0.7V3.3c0-0.5-0.4-0.7,0.2-0.7h7.8c-0.4,0.5-0.7,1-0.8,1.7l-3.5,19.6 C16.5,23.9,16.5,23.9,16.5,23.9 M19,24.2l3.5-19.6c0.1-0.5,0.6-0.9,1.2-0.8l13.1,2.2c0.5,0.1,0.9,0.6,0.8,1.1l-3.5,19.6 c-0.1,0.5-0.6,0.9-1.2,0.8l-13.1-2.2C19.2,25.2,18.9,24.7,19,24.2 M0,7.6l3.5,19.6c0.4,1.8,2.1,3.1,4,2.8l12.4-2.1l12.5,2.1 c1.9,0.3,3.7-0.9,4-2.8l3.5-19.6C40.3,5.8,39,4,37.1,3.7L30,2.5C29.5,1,27.9,0,26.3,0H13c-1.6,0-2.5,1.1-2.8,2.6L3,3.8 C1.2,4.1-0.3,5.8,0,7.6"/></svg>';
    }
}
if (!function_exists('iconeFigure')) {
    // doc
    // exemplo
    // echo iconeFigure
    /**
     * Gera um icone de figure
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeFigure(int $tamanho = 19): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M20.3,23.4c6.5,0,11.8-5.2,11.8-11.7S26.8,0,20.3,0S8.4,5.2,8.4,11.7S13.7,23.4,20.3,23.4z M20.3,3.6 c4.5,0,8.2,3.6,8.2,8.1s-3.7,8.1-8.2,8.1c-4.5,0-8.2-3.6-8.2-8.1S15.8,3.6,20.3,3.6z"/><path class="st0" d="M26.1,24.5H13.9C6.2,24.5,0,30.6,0,38.2c0,1,0.8,1.8,1.8,1.8s1.8-0.8,1.8-1.8c0-5.6,4.6-10.1,10.2-10.1h12.3 c5.6,0,10.2,4.5,10.2,10.1c0,1,0.8,1.8,1.8,1.8c1,0,1.8-0.8,1.8-1.8C40,30.6,33.8,24.5,26.1,24.5z"/></svg>';
    }
}
if (!function_exists('iconeOutro')) {
    // doc
    // exemplo
    // echo iconeOutro
    /**
     * Gera um icone de outro
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeOutro(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M12.7,0H5.5C2.4,0,0,2.4,0,5.5v7.3c0,3,2.4,5.5,5.5,5.5h7.3c3,0,5.5-2.4,5.5-5.5V5.5C18.2,2.4,15.7,0,12.7,0z M14.5,12.7c0,1-0.8,1.8-1.8,1.8H5.5c-1,0-1.8-0.8-1.8-1.8V5.5c0-1,0.8-1.8,1.8-1.8h7.3c1,0,1.8,0.8,1.8,1.8V12.7z"/><path class="st0" d="M12.7,21.8H5.5c-3,0-5.5,2.4-5.5,5.5v7.3c0,3,2.4,5.5,5.5,5.5h7.3c3,0,5.5-2.4,5.5-5.5v-7.3 C18.2,24.3,15.7,21.8,12.7,21.8z M14.5,34.5c0,1-0.8,1.8-1.8,1.8H5.5c-1,0-1.8-0.8-1.8-1.8v-7.3c0-1,0.8-1.8,1.8-1.8h7.3 c1,0,1.8,0.8,1.8,1.8V34.5z"/><path class="st0" d="M34.5,21.8h-7.3c-3,0-5.5,2.4-5.5,5.5v7.3c0,3,2.4,5.5,5.5,5.5h7.3c3,0,5.5-2.4,5.5-5.5v-7.3 C40,24.3,37.6,21.8,34.5,21.8z M36.4,34.5c0,1-0.8,1.8-1.8,1.8h-7.3c-1,0-1.8-0.8-1.8-1.8v-7.3c0-1,0.8-1.8,1.8-1.8h7.3 c1,0,1.8,0.8,1.8,1.8V34.5z"/><path class="st0" d="M34.5,0h-7.3c-3,0-5.5,2.4-5.5,5.5v7.3c0,3,2.4,5.5,5.5,5.5h7.3c3,0,5.5-2.4,5.5-5.5V5.5C40,2.4,37.6,0,34.5,0 z M36.4,12.7c0,1-0.8,1.8-1.8,1.8h-7.3c-1,0-1.8-0.8-1.8-1.8V5.5c0-1,0.8-1.8,1.8-1.8h7.3c1,0,1.8,0.8,1.8,1.8V12.7z"/></svg>';
    }
}
if (!function_exists('iconeCor')) {
    // doc
    // exemplo
    // echo iconeCor
    /**
     * Gera um icone de cor
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeCor(int $tamanho = 18): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M15.9,0.4C8.1,2,2,8,0.4,15.8c-2.9,14.7,10.3,25.6,20.2,24c2.7-0.6,4.4-3.2,3.8-5.9c-0.1-0.5-0.3-0.9-0.5-1.3 c-1.3-2.6-0.3-5.8,2.2-7.1c0.8-0.4,1.6-0.6,2.5-0.6h6.2c2.8,0,5.1-2.2,5.2-5c0,0,0,0,0,0C39.9,8.8,30.9-0.1,19.8,0 C18.5,0,17.2,0.1,15.9,0.4z M7.5,25C6.1,25,5,23.9,5,22.5C5,21.1,6.1,20,7.5,20s2.5,1.1,2.5,2.5C10,23.9,8.9,25,7.5,25 C7.5,25,7.5,25,7.5,25z M10,15c-1.4,0-2.5-1.1-2.5-2.5c0-1.4,1.1-2.5,2.5-2.5s2.5,1.1,2.5,2.5C12.5,13.9,11.4,15,10,15 C10,15,10,15,10,15z M20,10c-1.4,0-2.5-1.1-2.5-2.5S18.6,5,20,5c1.4,0,2.5,1.1,2.5,2.5C22.5,8.9,21.4,10,20,10C20,10,20,10,20,10z M30,15c-1.4,0-2.5-1.1-2.5-2.5S28.6,10,30,10c1.4,0,2.5,1.1,2.5,2.5l0,0C32.5,13.9,31.4,15,30,15C30,15,30,15,30,15z"/></svg>';
    }
}
if (!function_exists('iconeSenha')) {
    // doc
    // exemplo
    // echo iconeSenha
    /**
     * Gera um icone de senha
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeSenha(int $tamanho = 14): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50"><path d="M20.75,36.51a15.69,15.69,0,0,0,7.4-1.84l20,20a1,1,0,0,0,.71.29.47.47,0,0,0,.17,0l4.34-.75a1,1,0,0,0,.81-.82L55,49.08a1,1,0,0,0-.28-.88L51.46,45a1,1,0,0,0-1.41,0L48.8,46.2l-2.12-2.12,1.24-1.25a1,1,0,0,0,0-1.41l-3.24-3.24a1,1,0,0,0-1.41,0L42,39.42l-2.09-2.09,1.25-1.24a1,1,0,0,0,0-1.42l-6.52-6.51a15.75,15.75,0,1,0-13.91,8.35ZM15.2,15.2a5.27,5.27,0,1,1,0,7.45A5.24,5.24,0,0,1,15.2,15.2Z" transform="translate(-5 -5)"/></svg>';
    }
}
if (!function_exists('iconeSair')) {
    // doc
    // exemplo
    // echo iconeSair
    /**
     * Gera um icone de sair
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeSair(int $tamanho = 16): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 32 32" style="enable-background:new 0 0 32 32;" xml:space="preserve"><path d="M11,29H3c-0.6,0-1-0.4-1-1V4c0-0.6,0.4-1,1-1h8c0.6,0,1,0.4,1,1s-0.4,1-1,1H4v22h7c0.6,0,1,0.4,1,1S11.6,29,11,29z M20,25  c-0.3,0-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l6.3-6.3H8c-0.6,0-1-0.4-1-1s0.4-1,1-1h17.6l-6.3-6.3c-0.4-0.4-0.4-1,0-1.4  s1-0.4,1.4,0l8,8c0.1,0.1,0.2,0.2,0.2,0.3c0,0.1,0.1,0.2,0.1,0.4l0,0c0,0,0,0,0,0l0,0c0,0.1,0,0.3-0.1,0.4c0,0.1-0.1,0.2-0.2,0.3  l-8,8C20.5,24.9,20.3,25,20,25z"/></svg>';
    }
}
if (!function_exists('iconeBloquear')) {
    // doc
    // exemplo
    // echo iconeBloquear
    /**
     * Gera um icone de bloquear
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeBloquear(int $tamanho = 14): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 1024 1024" style="enable-background:new 0 0 1024 1024;" xml:space="preserve"><g><g><g><path d="M33.9,512c0.1,49,7.4,99,22.6,145.6c14.7,45.1,35.6,88.8,63.2,127.4c15.1,21.2,31.5,41.7,49.7,60.5     c17.9,18.5,37.7,34.7,58.1,50.3c37.9,29,80.4,50.6,125.1,67c45.9,16.8,95.2,25.4,144,27.1c22.4,0.8,44.8-0.1,67-2.9     c26.6-3.3,52.8-7.5,78.6-14.8c45.6-12.9,90.2-32.9,129.8-58.9c39.2-25.8,75.5-56.9,105.4-93.3c16.5-20,32-40.9,45.3-63.1     c13.5-22.5,24.2-46.2,34.2-70.5c18.5-45.3,28.3-93.5,32-142.2c3.8-49.3-1.1-100-12.9-148c-11.2-45.7-29.9-90.6-54.3-130.8     C897.5,225.3,867,188,832,156.8c-35.3-31.3-74.7-58.5-117.8-77.9c-23.8-10.8-48.1-20.4-73.3-27.5c-26.2-7.3-53-11.6-80.1-14.7     c-49.5-5.8-99.9-2.2-148.7,7.8C365.3,54,319.6,71.7,277.9,95c-40.5,22.7-78.3,51.9-110.4,85.5c-32.5,33.9-60.8,72.9-81.8,115     c-21.5,43.1-37.6,89.1-44.9,136.8C36.8,458.7,33.9,485.2,33.9,512c0,15.4,6.7,31.6,17.6,42.4c10.4,10.4,27.5,18.2,42.4,17.6     c15.5-0.7,31.6-5.8,42.4-17.6c10.8-11.8,17.5-26.2,17.6-42.4c0-18.6,1.2-37.2,3.7-55.6c-0.7,5.3-1.4,10.6-2.1,16     c4.9-36.6,14.6-72.5,28.9-106.5c-2,4.8-4,9.6-6,14.3c14.1-33.3,32.4-64.6,54.4-93.2c-3.1,4-6.3,8.1-9.4,12.1     c22-28.4,47.4-53.8,75.8-75.8c-4,3.1-8.1,6.3-12.1,9.4c28.6-22,59.9-40.3,93.2-54.4c-4.8,2-9.6,4-14.3,6     c34.1-14.3,69.9-24,106.5-28.9c-5.3,0.7-10.6,1.4-16,2.1c36.9-4.8,74.3-4.8,111.2,0c-5.3-0.7-10.6-1.4-16-2.1     c36.6,4.9,72.5,14.6,106.5,28.9c-4.8-2-9.6-4-14.3-6c33.3,14.1,64.6,32.4,93.2,54.4c-4-3.1-8.1-6.3-12.1-9.4     c28.4,22,53.8,47.4,75.8,75.8c-3.1-4-6.3-8.1-9.4-12.1c22,28.6,40.3,59.9,54.4,93.2c-2-4.8-4-9.6-6-14.3     c14.3,34.1,24,69.9,28.9,106.5c-0.7-5.3-1.4-10.6-2.1-16c4.8,36.9,4.8,74.3,0,111.2c0.7-5.3,1.4-10.6,2.1-16     c-4.9,36.6-14.6,72.5-28.9,106.5c2-4.8,4-9.6,6-14.3c-14.1,33.3-32.4,64.6-54.4,93.2c3.1-4,6.3-8.1,9.4-12.1     c-22,28.4-47.4,53.8-75.8,75.8c4-3.1,8.1-6.3,12.1-9.4c-28.6,22-59.9,40.3-93.2,54.4c4.8-2,9.6-4,14.3-6     c-34.1,14.3-69.9,24-106.5,28.9c5.3-0.7,10.6-1.4,16-2.1c-36.9,4.8-74.3,4.8-111.2,0c5.3,0.7,10.6,1.4,16,2.1     c-36.6-4.9-72.5-14.6-106.5-28.9c4.8,2,9.6,4,14.3,6c-33.3-14.1-64.6-32.4-93.2-54.4c4,3.1,8.1,6.3,12.1,9.4     c-28.4-22-53.8-47.4-75.8-75.8c3.1,4,6.3,8.1,9.4,12.1c-22-28.6-40.3-59.9-54.4-93.2c2,4.8,4,9.6,6,14.3     c-14.3-34.1-24-69.9-28.9-106.5c0.7,5.3,1.4,10.6,2.1,16c-2.4-18.4-3.6-37-3.7-55.6c0-15.4-6.7-31.5-17.6-42.4     c-10.4-10.4-27.5-18.2-42.4-17.6c-15.5,0.7-31.6,5.8-42.4,17.6C40.7,481.3,33.8,495.8,33.9,512z"/></g></g><g><g><path d="M765.2,173.9c-5.3,5.3-10.5,10.5-15.8,15.8c-14.3,14.3-28.7,28.7-43,43c-21.2,21.2-42.4,42.4-63.6,63.6     c-25.9,25.9-51.7,51.7-77.6,77.6c-28.3,28.3-56.7,56.7-85,85c-28.6,28.6-57.2,57.2-85.8,85.8c-26.7,26.7-53.4,53.4-80,80     c-22.6,22.6-45.1,45.1-67.7,67.7c-16.2,16.2-32.4,32.4-48.7,48.7c-7.7,7.7-15.4,15.3-23.1,23.1c-0.3,0.3-0.7,0.7-1,1     c-10.9,10.9-17.6,27-17.6,42.4c0,14.7,6.5,32.3,17.6,42.4c11.4,10.5,26.4,18.3,42.4,17.6c15.9-0.7,30.9-6.1,42.4-17.6     c5.3-5.3,10.5-10.5,15.8-15.8c14.3-14.3,28.7-28.7,43-43c21.2-21.2,42.4-42.4,63.6-63.6c25.9-25.9,51.7-51.7,77.6-77.6     c28.3-28.3,56.7-56.7,85-85c28.6-28.6,57.2-57.2,85.8-85.8c26.7-26.7,53.4-53.4,80-80c22.6-22.6,45.1-45.1,67.7-67.7     c16.2-16.2,32.4-32.4,48.7-48.7c7.7-7.7,15.4-15.3,23.1-23.1c0.3-0.3,0.7-0.7,1-1c10.9-10.9,17.6-27,17.6-42.4     c0-14.7-6.5-32.3-17.6-42.4c-11.4-10.5-26.4-18.3-42.4-17.6C791.8,157,776.7,162.4,765.2,173.9L765.2,173.9z"/></g></g></g></svg>';
    }
}
if (!function_exists('iconeAsc')) {
    // doc
    // exemplo
    // echo iconeAsc
    /**
     * Gera um icone de ordem asc
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeAsc(int $tamanho = 11): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" x="0px" y="0px"><path d="M51.13,23.9,34.87,7.64a9,9,0,0,0-12.73,0L5.88,23.9a3,3,0,0,0,4.24,4.24L25.51,12.76V92a3,3,0,0,0,6,0V12.76L46.89,28.14a3,3,0,1,0,4.24-4.24Z"/><path d="M92,11H59a3,3,0,0,1,0-6H92a3,3,0,0,1,0,6Z"/><path d="M84.59,39H59a3,3,0,0,1,0-6H84.59a3,3,0,0,1,0,6Z"/><path d="M77.19,67H59a3,3,0,0,1,0-6H77.19a3,3,0,0,1,0,6Z"/><path d="M69.78,95H59a3,3,0,0,1,0-6H69.78a3,3,0,0,1,0,6Z"/></svg>';
    }
}
if (!function_exists('iconeDesc')) {
    // doc
    // exemplo
    // echo iconeDesc
    /**
     * Gera um icone de ordem desc
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeDesc(int $tamanho = 11): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" x="0px" y="0px"><path d="M51.13,76.1,34.87,92.36a9,9,0,0,1-12.73,0L5.88,76.1a3,3,0,1,1,4.24-4.24L25.51,87.24V8a3,3,0,0,1,6,0V87.24L46.89,71.86a3,3,0,1,1,4.24,4.24Z"/><path d="M92,89H59a3,3,0,0,0,0,6H92a3,3,0,0,0,0-6Z"/><path d="M84.59,61H59a3,3,0,0,0,0,6H84.59a3,3,0,0,0,0-6Z"/><path d="M77.19,33H59a3,3,0,0,0,0,6H77.19a3,3,0,0,0,0-6Z"/><path d="M69.78,5H59a3,3,0,0,0,0,6H69.78a3,3,0,0,0,0-6Z"/></svg>';
    }
}
if (!function_exists('iconeDrag')) {
    // doc
    // exemplo
    // echo iconeDrag
    /**
     * Gera um icone de drag and drop
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeDrag(int $tamanho = 10): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 21" style="enable-background:new 0 0 40 21;" xml:space="preserve"><g><path class="st0" d="M37.1,21H2.9C1.3,21,0,19.7,0,18.1s1.3-2.9,2.9-2.9h34.3c1.6,0,2.9,1.3,2.9,2.9S38.7,21,37.1,21z M37.1,5.7 H2.9C1.3,5.7,0,4.4,0,2.9S1.3,0,2.9,0h34.3C38.7,0,40,1.3,40,2.9S38.7,5.7,37.1,5.7z"/></g></svg>';
    }
}
if (!function_exists('iconeCopiar')) {
    // doc
    // exemplo
    // echo iconeCopiar
    /**
     * Gera um icone de copiar
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeCopiar(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" data-name="Layer 4" viewBox="0 0 64 64" x="0px" y="0px"><path d="M33.553,51.6a5.507,5.507,0,0,0,5.5-5.5v-2.51h5.479a5.506,5.506,0,0,0,5.5-5.5V24.132a5.506,5.506,0,0,0-5.5-5.5H30.572a5.506,5.506,0,0,0-5.5,5.5v2.509h-5.48a5.507,5.507,0,0,0-5.5,5.5V46.1a5.507,5.507,0,0,0,5.5,5.5ZM28.072,24.132a2.5,2.5,0,0,1,2.5-2.5h13.96a2.5,2.5,0,0,1,2.5,2.5V38.091a2.5,2.5,0,0,1-2.5,2.5H39.053v-8.45a5.507,5.507,0,0,0-5.5-5.5H28.072ZM17.092,46.1V32.141a2.5,2.5,0,0,1,2.5-2.5H33.553a2.5,2.5,0,0,1,2.5,2.5V46.1a2.5,2.5,0,0,1-2.5,2.5H19.592A2.5,2.5,0,0,1,17.092,46.1Z"/></svg>';
    }
}
if (!function_exists('iconeCalendario')) {
    // doc
    // exemplo
    // echo iconeCalendario
    /**
     * Gera um icone de calendario
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeCalendario(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.1" x="0px" y="0px" viewBox="0 0 100 100"><g transform="translate(0,-952.36218)"><path d="m 27.031249,955.36218 c -1.6569,0 -3,1.34315 -3,3 l 0,11.53125 c -3.47872,1.24841 -6,4.58442 -6,8.46875 0,4.93503 4.065,9 9,9 4.935,0 9,-4.06497 9,-9 0,-3.88433 -2.52128,-7.22034 -6,-8.46875 l 0,-11.53125 c 0,-1.65685 -1.3432,-3 -3,-3 z m 46,0 c -1.6569,0 -3,1.34315 -3,3 l 0,11.53125 c -3.47873,1.24841 -6,4.58442 -6,8.46875 0,4.93503 4.065,9 9,9 4.935,0 9,-4.06497 9,-9 0,-3.88433 -2.52127,-7.22034 -6,-8.46875 l 0,-11.53125 c 0,-1.65685 -1.3432,-3 -3,-3 z m -60,6 c -6.0588,0 -11.03125,4.97251 -11.03125,11.03125 l 0,65.93747 c 0,6.0588 4.97245,11.0313 11.03125,11.0313 l 73.937502,0 c 6.0587,0 11.03125,-4.9725 11.03125,-11.0313 l 0,-65.93747 c 0,-6.05874 -4.97255,-11.03125 -11.03125,-11.03125 l -5.968752,0 c -1.5849,-0.0224 -3.03125,1.4149 -3.03125,3 0,1.5851 1.44635,3.02241 3.03125,3 l 5.968752,0 c 2.8328,0 5.03125,2.19837 5.03125,5.03125 l 0,16.96875 -84.000002,0 0,-16.96875 c 0,-2.83288 2.19835,-5.03125 5.03125,-5.03125 l 5.96875,0 c 1.5849,0.0224 3.03125,-1.4149 3.03125,-3 0,-1.5851 -1.44635,-3.02241 -3.03125,-3 z m 21.96875,0 c -1.6569,0 -3,1.3431 -3,3 0,1.6568 1.3431,3 3,3 l 30,0 c 1.6569,0 3,-1.3432 3,-3 0,-1.6569 -1.3431,-3 -3,-3 z m -7.96875,14 c 1.6924,0 3,1.30761 3,3 0,1.69239 -1.3076,3 -3,3 -1.6924,0 -3,-1.30761 -3,-3 0,-1.69239 1.3076,-3 3,-3 z m 46,0 c 1.6924,0 3,1.30761 3,3 0,1.69239 -1.3076,3 -3,3 -1.6924,0 -3,-1.30761 -3,-3 0,-1.69239 1.3076,-3 3,-3 z m -65.03125,20 84.000002,0 0,42.96872 c 0,2.8329 -2.19845,5.0313 -5.03125,5.0313 l -73.937502,0 c -2.8329,0 -5.03125,-2.1984 -5.03125,-5.0313 z" style="text-indent:0;text-transform:none;direction:ltr;block-progression:tb;baseline-shift:baseline;color:#000000;enable-background:accumulate;" fill-opacity="1" stroke="none" marker="none" visibility="visible" display="inline" overflow="visible"/></g></svg>';
    }
}
if (!function_exists('iconeLink')) {
    // doc
    // exemplo
    // echo iconeLink
    /**
     * Gera um icone de link
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeLink(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns:x="http://ns.adobe.com/Extensibility/1.0/" xmlns:i="http://ns.adobe.com/AdobeIllustrator/10.0/" xmlns:graph="http://ns.adobe.com/Graphs/1.0/" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><switch><foreignObject requiredExtensions="http://ns.adobe.com/AdobeIllustrator/10.0/" x="0" y="0" width="1" height="1"/><g i:extraneous="self"><g><path d="M5273.1,2400.1v-2c0-2.8-5-4-9.7-4s-9.7,1.3-9.7,4v2c0,1.8,0.7,3.6,2,4.9l5,4.9c0.3,0.3,0.4,0.6,0.4,1v6.4     c0,0.4,0.2,0.7,0.6,0.8l2.9,0.9c0.5,0.1,1-0.2,1-0.8v-7.2c0-0.4,0.2-0.7,0.4-1l5.1-5C5272.4,2403.7,5273.1,2401.9,5273.1,2400.1z      M5263.4,2400c-4.8,0-7.4-1.3-7.5-1.8v0c0.1-0.5,2.7-1.8,7.5-1.8c4.8,0,7.3,1.3,7.5,1.8C5270.7,2398.7,5268.2,2400,5263.4,2400z"/><path d="M5268.4,2410.3c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4.3c0.6,0,1-0.4,1-1c0-0.6-0.4-1-1-1H5268.4z"/><path d="M5272.7,2413.7h-4.3c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4.3c0.6,0,1-0.4,1-1C5273.7,2414.1,5273.3,2413.7,5272.7,2413.7z"/><path d="M5272.7,2417h-4.3c-0.6,0-1,0.4-1,1c0,0.6,0.4,1,1,1h4.3c0.6,0,1-0.4,1-1C5273.7,2417.5,5273.3,2417,5272.7,2417z"/></g><g><path d="M38.4,65.5H27.8c-8.5,0-15.5-7-15.5-15.5s6.9-15.5,15.5-15.5h10.6c2.7,0,4.9-2.2,4.9-4.9s-2.2-4.9-4.9-4.9H27.8     C13.9,24.7,2.5,36,2.5,50s11.4,25.3,25.3,25.3h10.6c2.7,0,4.9-2.2,4.9-4.9C43.3,67.7,41.1,65.5,38.4,65.5z"/><path d="M72.2,24.7H61.6c-2.7,0-4.9,2.2-4.9,4.9s2.2,4.9,4.9,4.9h10.6c8.5,0,15.5,7,15.5,15.5s-6.9,15.5-15.5,15.5H61.6     c-2.7,0-4.9,2.2-4.9,4.9c0,2.7,2.2,4.9,4.9,4.9h10.6c14,0,25.3-11.4,25.3-25.3S86.1,24.7,72.2,24.7z"/><path d="M23.6,50c0,2.7,2.2,4.9,4.9,4.9h43c2.7,0,4.9-2.2,4.9-4.9s-2.2-4.9-4.9-4.9h-43C25.8,45.1,23.6,47.3,23.6,50z"/></g></g></switch></svg>';
    }
}
if (!function_exists('iconeCheck')) {
    // doc
    // exemplo
    // echo iconeCheck
    /**
     * Gera um icone de check
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeCheck(int $tamanho = 14): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z"/></svg>';
    }
}
if (!function_exists('iconeUncheck')) {
    // doc
    // exemplo
    // echo iconeUncheck
    /**
     * Gera um icone de uncheck
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeUncheck(int $tamanho = 22): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path d="M53.3,66.19L40.62,78.87c-1.08,1.08-2.83,1.08-3.9,0L10.4,52.55c-1.08-1.08-1.08-2.83,0-3.9l4.87-4.87  c1.08-1.08,2.83-1.08,3.9,0L33.9,58.51c2.63,2.63,6.92,2.63,9.56,0l1.08-1.08c0.78-0.78,0.78-2.05,0-2.83  c-0.78-0.78-2.05-0.78-2.83,0l-1.08,1.08c-1.04,1.04-2.86,1.04-3.9,0L21.99,40.95c-2.63-2.64-6.92-2.64-9.56,0l-4.87,4.87  c-2.64,2.64-2.64,6.92,0,9.56L33.9,81.7c1.32,1.32,3.05,1.98,4.78,1.98c1.73,0,3.46-0.66,4.78-1.98l12.68-12.68  c0.78-0.78,0.78-2.05,0-2.83C55.35,65.41,54.08,65.41,53.3,66.19z"/><path d="M92.43,23.17l-4.87-4.87c-2.63-2.63-6.92-2.64-9.56,0L52.13,44.17c-0.78,0.78-0.78,2.05,0,2.83  c0.39,0.39,0.9,0.59,1.41,0.59s1.02-0.2,1.41-0.59l25.87-25.87c1.08-1.07,2.83-1.08,3.9,0l4.87,4.87c1.08,1.08,1.08,2.83,0,3.9  L63.66,55.84c-0.78,0.78-0.78,2.05,0,2.83c0.39,0.39,0.9,0.59,1.41,0.59s1.02-0.2,1.41-0.59l25.94-25.94  C95.07,30.09,95.07,25.8,92.43,23.17z"/><path d="M19.73,19.65c-0.78-0.78-2.05-0.78-2.83,0c-0.78,0.78-0.78,2.05,0,2.83l60.61,60.61c0.39,0.39,0.9,0.59,1.41,0.59  s1.02-0.2,1.41-0.59c0.78-0.78,0.78-2.05,0-2.83L19.73,19.65z"/></svg>';
    }
}
if (!function_exists('iconeHome')) {
    // doc
    // exemplo
    // echo iconeHome
    /**
     * Gera um icone de home
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeHome(int $tamanho = 22): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1" viewBox="0 0 32 40" x="0px" y="0px"><title>expand-black-media</title><path d="M26.74,14.83l-10-10a1,1,0,0,0-1.4,0l-10,10A1,1,0,0,0,6,16.51H7.22v10a1,1,0,0,0,1,1h5.6V20.8a1,1,0,0,1,1-1h2.42a1,1,0,0,1,1,1v6.7h5.6a1,1,0,0,0,1-1v-10h1.27A1,1,0,0,0,26.74,14.83Z"/></svg>';
    }
}
if (!function_exists('iconeLoadingBola')) {
    // doc
    // exemplo
    // echo iconeLoadingBola
    /**
     * Gera um icone de loading de bola
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeLoadingBola(int $tamanho = 34): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><g><g><circle cx="20" cy="3.6" r="3.6"/><circle cx="20" cy="36.4" r="3.6"/></g><g><circle cx="8.4" cy="8.4" r="3.6"/><circle cx="31.6" cy="31.6" r="3.6"/></g><g><circle cx="3.6" cy="20" r="3.6"/><circle cx="36.4" cy="20" r="3.6"/></g><g><circle cx="8.4" cy="31.6" r="3.6"/><circle cx="31.6" cy="8.4" r="3.6"/></g></g></svg>';
    }
}
if (!function_exists('iconeDinheiro')) {
    // doc
    // exemplo
    // echo iconeDinheiro
    /**
     * Gera um icone de dinheiro
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeDinheiro(int $tamanho = 26): string
    {
        return '<svg height="' . $tamanho . '" xmlns:x="http://ns.adobe.com/Extensibility/1.0/" xmlns:i="http://ns.adobe.com/AdobeIllustrator/10.0/" xmlns:graph="http://ns.adobe.com/Graphs/1.0/" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><switch><foreignObject requiredExtensions="http://ns.adobe.com/AdobeIllustrator/10.0/" x="0" y="0" width="1" height="1"/><g i:extraneous="self"><g><path d="M50,29.7c-11.2,0-20.3,9.1-20.3,20.3S38.8,70.3,50,70.3S70.3,61.2,70.3,50S61.2,29.7,50,29.7z M51.7,60.1v2.7 c0,0.4-0.3,0.7-0.7,0.7h-1.9c-0.4,0-0.7-0.3-0.7-0.7v-2.6c-1.7-0.3-3-0.9-3.9-1.5c-0.4-0.2-0.7-0.5-1-0.7c-0.3-0.2-0.3-0.7-0.1-1 l1.2-1.6c0.2-0.3,0.7-0.4,1-0.1c0.3,0.2,0.6,0.5,1,0.6c0.9,0.6,2.2,1.1,3.6,1.1c1.7,0,3.1-0.9,3.1-2.5c0-3.5-9.8-2.6-9.8-9 c0-2.7,2-4.9,5.1-5.5v-2.7c0-0.4,0.3-0.7,0.7-0.7h1.9c0.4,0,0.7,0.3,0.7,0.7v2.6c1.5,0.2,2.6,0.7,3.4,1.1 c0.3,0.2,0.6,0.4,0.8,0.6c0.3,0.2,0.3,0.6,0.2,0.9l-0.9,1.6c-0.2,0.4-0.7,0.5-1,0.2c-0.2-0.2-0.5-0.3-0.7-0.4 c-0.8-0.4-1.9-0.9-3.2-0.9c-1.9,0-3.2,0.9-3.2,2.4c0,3.7,9.8,2.7,9.8,8.9C56.9,57.1,55,59.5,51.7,60.1z"/><path d="M95.4,20.5H4.6c-1.2,0-2.1,1-2.1,2.1v54.7c0,1.2,1,2.1,2.1,2.1h90.7c1.2,0,2.1-1,2.1-2.1V22.7 C97.5,21.5,96.5,20.5,95.4,20.5z M91.3,58.7c-7.4,1.6-13.3,7.6-14.6,15.1H23.3C22,66.3,16.2,60.3,8.7,58.7V41.3 c7.4-1.6,13.3-7.6,14.6-15.1h53.3c1.3,7.5,7.2,13.5,14.6,15.1V58.7z"/></g></g></switch></svg>';
    }
}
if (!function_exists('iconeAnexo')) {
    // doc
    // exemplo
    // echo iconeAnexo
    /**
     * Gera um icone de anexo
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeAnexo(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 18 20" style="enable-background:new 0 0 18 20;" xml:space="preserve"><path d="M12.2,6l-5.8,5.9C6,12.3,6,13,6.4,13.4c0.4,0.4,1,0.4,1.4,0l5.8-5.9c1.2-1.2,1.2-3.2,0-4.4c-1.2-1.2-3.1-1.2-4.3,0L3.5,9c-2,2-2,5.4,0,7.4c2,2,5.2,2,7.2,0l5.8-5.9l1.4,1.5l-5.8,5.9c-2.8,2.9-7.3,2.9-10.1,0s-2.8-7.5,0-10.4l5.8-5.9c2-2,5.2-2,7.2,0s2,5.4,0,7.4l-5.8,5.9c-1.2,1.2-3.1,1.2-4.3,0c-1.2-1.2-1.2-3.2,0-4.4l5.8-5.9L12.2,6z"/></svg>';
    }
}
if (!function_exists('iconeUpload')) {
    // doc
    // exemplo
    // echo iconeUpload
    /**
     * Gera um icone de upload
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeUpload(int $tamanho = 19): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34 40" style="enable-background:new 0 0 34 40;" xml:space="preserve"><path d="M18.3,38.7c0-3.1,0-6.2,0-9.3c0-4.9,0-9.9,0-14.8c0-1.1,0-2.2,0-3.4c0-0.7-0.6-1.4-1.3-1.3c-0.7,0-1.3,0.6-1.3,1.3 c0,3.1,0,6.2,0,9.3c0,4.9,0,9.9,0,14.8c0,1.1,0,2.2,0,3.4c0,0.7,0.6,1.4,1.3,1.3C17.7,40,18.3,39.4,18.3,38.7L18.3,38.7z"/><path d="M27.9,21.6c-1.1-1.4-2.3-2.7-3.4-4.1c-1.8-2.2-3.6-4.3-5.4-6.5c-0.4-0.5-0.8-1-1.2-1.5c-0.4-0.5-1.4-0.5-1.9,0 c-1.1,1.4-2.3,2.7-3.4,4.1c-1.8,2.2-3.6,4.3-5.4,6.5c-0.4,0.5-0.8,1-1.2,1.5c-0.5,0.6-0.5,1.4,0,1.9c0.5,0.5,1.4,0.6,1.9,0 c1.1-1.4,2.3-2.7,3.4-4.1c1.8-2.2,3.6-4.3,5.4-6.5c0.4-0.5,0.8-1,1.2-1.5c-0.6,0-1.2,0-1.9,0c1.1,1.4,2.3,2.7,3.4,4.1 c1.8,2.2,3.6,4.3,5.4,6.5c0.4,0.5,0.8,1,1.2,1.5c0.5,0.6,1.4,0.5,1.9,0C28.4,22.9,28.4,22.2,27.9,21.6L27.9,21.6z"/><path d="M32.7,0c-1,0-2.1,0-3.1,0c-2.5,0-5,0-7.5,0c-3,0-6,0-9.1,0c-2.6,0-5.2,0-7.8,0C3.9,0,2.6,0,1.4,0c0,0,0,0-0.1,0 C0.6,0,0,0.6,0,1.4s0.6,1.3,1.3,1.3c1,0,2.1,0,3.1,0c2.5,0,5,0,7.5,0c3,0,6,0,9.1,0c2.6,0,5.2,0,7.8,0c1.3,0,2.5,0,3.8,0 c0,0,0,0,0.1,0c0.7,0,1.3-0.6,1.3-1.3S33.4,0,32.7,0L32.7,0z"/></svg>';
    }
}
if (!function_exists('iconeDownload')) {
    // doc
    // exemplo
    // echo iconeDownload
    /**
     * Gera um icone de download
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeDownload(int $tamanho = 19): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34 40" style="enable-background:new 0 0 34 40;" xml:space="preserve"><path d="M15.7,1.3c0,3.1,0,6.2,0,9.3c0,4.9,0,9.9,0,14.8c0,1.1,0,2.2,0,3.4c0,0.7,0.6,1.4,1.3,1.3s1.3-0.6,1.3-1.3 c0-3.1,0-6.2,0-9.3c0-4.9,0-9.9,0-14.8c0-1.1,0-2.2,0-3.4C18.3,0.6,17.7,0,17,0C16.3,0,15.7,0.6,15.7,1.3L15.7,1.3z"/><path d="M6.1,18.4c1.1,1.4,2.3,2.7,3.4,4.1c1.8,2.2,3.6,4.3,5.4,6.5c0.4,0.5,0.8,1,1.2,1.5c0.4,0.5,1.4,0.5,1.9,0 c1.1-1.4,2.3-2.7,3.4-4.1c1.8-2.2,3.6-4.3,5.4-6.5c0.4-0.5,0.8-1,1.2-1.5c0.5-0.6,0.5-1.4,0-1.9c-0.5-0.5-1.4-0.6-1.9,0 c-1.1,1.4-2.3,2.7-3.4,4.1c-1.8,2.2-3.6,4.3-5.4,6.5c-0.4,0.5-0.8,1-1.2,1.5c0.6,0,1.2,0,1.9,0c-1.1-1.4-2.3-2.7-3.4-4.1 c-1.8-2.2-3.6-4.3-5.4-6.5c-0.4-0.5-0.8-1-1.2-1.5c-0.5-0.6-1.4-0.5-1.9,0C5.6,17.1,5.6,17.8,6.1,18.4L6.1,18.4z"/><path d="M1.3,40c1,0,2.1,0,3.1,0c2.5,0,5,0,7.5,0c3,0,6,0,9.1,0c2.6,0,5.2,0,7.8,0c1.3,0,2.5,0,3.8,0c0,0,0,0,0.1,0 c0.7,0,1.3-0.6,1.3-1.3s-0.6-1.3-1.3-1.3c-1,0-2.1,0-3.1,0c-2.5,0-5,0-7.5,0c-3,0-6,0-9.1,0c-2.6,0-5.2,0-7.8,0 c-1.3,0-2.5,0-3.8,0c0,0,0,0-0.1,0c-0.7,0-1.3,0.6-1.3,1.3C0,39.4,0.6,40,1.3,40L1.3,40z"/></svg>';
    }
}
if (!function_exists('iconeOlhoAberto')) {
    // doc
    // exemplo
    // echo iconeOlhoAberto
    /**
     * Gera um icone de olho aberto
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeOlhoAberto(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns:cc="hqttp://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.3" style="enable-background:new 0 0 40 29.3;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-13.3,0-19.3,12.3-19.6,12.9c-0.6,1.1-0.6,2.5,0,3.6c0.3,0.6,6.3,12.9,19.6,12.9s19.3-12.3,19.6-12.9 c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z M20,291.2c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2 c-0.4,0.8-5.6,11.4-17.2,11.4S3.2,304.6,2.8,303.8c-0.2-0.4-0.2-0.8,0-1.2C3.2,301.7,8.4,291.2,20,291.2z"/><path d="M20,293.9c-5.1,0-9.3,4.2-9.3,9.3s4.2,9.3,9.3,9.3s9.3-4.2,9.3-9.3S25.1,293.9,20,293.9z M20,296.5c3.7,0,6.7,3,6.7,6.7 s-3,6.7-6.7,6.7s-6.7-3-6.7-6.7S16.3,296.5,20,296.5z"/></g></svg>';
    }
}
if (!function_exists('iconeOlhoFechado')) {
    // doc
    // exemplo
    // echo iconeOlhoFechado
    /**
     * Gera um icone de olho fechado
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeOlhoFechado(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.4" style="enable-background:new 0 0 40 29.4;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-2,0-3.8,0.3-5.4,0.8c-0.7,0.2-1.2,0.9-1,1.6c0.2,0.7,0.9,1.2,1.6,1c0,0,0.1,0,0.1,0c1.5-0.4,3-0.7,4.7-0.7 c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2c-0.2,0.4-1.4,2.8-3.8,5.3c-0.5,0.5-0.5,1.4,0,1.9c0.5,0.5,1.4,0.5,1.9,0 c0,0,0.1-0.1,0.1-0.1c2.7-2.9,4.1-5.7,4.2-6c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z"/><path d="M6.6,288.5c-0.7,0-1.3,0.6-1.3,1.3c0,0.4,0.1,0.7,0.4,1l1.9,1.9c-4.7,3.6-7,8.3-7.2,8.7c-0.6,1.1-0.6,2.5,0,3.6 c0.3,0.6,6.3,12.9,19.6,12.9c4,0,7.4-1.1,10.1-2.7l2.3,2.3c0.5,0.5,1.4,0.6,1.9,0s0.6-1.4,0-1.9c0,0,0,0,0,0L7.6,288.9 C7.4,288.7,7,288.5,6.6,288.5z M9.6,294.7l3,3c-1.2,1.6-1.9,3.6-1.9,5.6c0,5.1,4.2,9.3,9.3,9.3c2,0,4-0.7,5.6-1.9l2.5,2.5 c-2.2,1.2-4.9,2-8.1,2c-11.6,0-16.8-10.5-17.2-11.4c-0.2-0.4-0.2-0.8,0-1.2C3.1,302.1,5.2,297.9,9.6,294.7z M14.4,299.5l9.2,9.2 c-1.1,0.7-2.4,1.1-3.7,1.1c-3.7,0-6.7-3-6.7-6.7C13.3,301.9,13.7,300.6,14.4,299.5z"/><path d="M19.6,296.6c0.1,0,0.3,0,0.4,0c3.7,0,6.7,3,6.7,6.7c0,0.1,0,0.2,0,0.3c-0.1,1.8,2.5,2,2.7,0.2c0-0.2,0-0.3,0-0.5c0-5.1-4.2-9.3-9.3-9.3c-0.2,0-0.4,0-0.5,0C17.7,294,17.8,296.7,19.6,296.6z"/></g></svg>';
    }
}
if (!function_exists('iconeGps')) {
    // doc
    // exemplo
    // echo iconeGps
    /**
     * Gera um icone de GPS
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeGps(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 32 40" style="enable-background:new 0 0 32 40;" xml:space="preserve"><path class="st0" d="M6.8,6.4c-2.3,2.1-3.7,5.2-3.7,9.4c0,1.9,0.8,4.2,2.1,6.7c1.3,2.4,3,4.9,4.8,7.1c1.8,2.2,3.5,4.2,4.9,5.6c0.4,0.5,0.8,0.8,1.1,1.2c0.3-0.3,0.7-0.7,1.1-1.2c1.3-1.4,3.1-3.3,4.9-5.6c1.8-2.2,3.5-4.7,4.8-7.1c1.3-2.5,2.1-4.8,2.1-6.7c0-4.2-1.5-7.3-3.7-9.4c-2.3-2.1-5.5-3.3-9.2-3.3S9.1,4.2,6.8,6.4L6.8,6.4z M16,38.5c-1.1,1.1-1.1,1.1-1.1,1.1l0,0l0,0l-0.1-0.1c-0.1-0.1-0.3-0.3-0.5-0.5c-0.4-0.4-1-1-1.7-1.7c-1.4-1.4-3.2-3.5-5.1-5.8c-1.8-2.3-3.7-4.9-5.1-7.6C1,21.2,0,18.4,0,15.7c0-4.9,1.8-8.9,4.7-11.7C7.7,1.4,11.7,0,16,0s8.3,1.4,11.3,4.1c3,2.7,4.7,6.7,4.7,11.7c0,2.6-1,5.5-2.4,8.1c-1.4,2.7-3.3,5.3-5.1,7.6c-1.8,2.3-3.7,4.3-5.1,5.8c-0.7,0.7-1.3,1.3-1.7,1.7c-0.2,0.2-0.4,0.4-0.5,0.5l-0.1,0.1l0,0l0,0C17.1,39.6,17.1,39.6,16,38.5L16,38.5z M16,38.5l1.1,1.1c-0.6,0.6-1.5,0.6-2.1,0L16,38.5z"/><path class="st0" d="M16,11.3c-2.6,0-4.6,2.1-4.6,4.6s2.1,4.6,4.6,4.6s4.6-2.1,4.6-4.6S18.6,11.3,16,11.3L16,11.3z M8.3,15.9c0-4.2,3.5-7.7,7.7-7.7c4.3,0,7.7,3.4,7.7,7.7c0,4.2-3.5,7.7-7.7,7.7C11.7,23.6,8.3,20.1,8.3,15.9z"/></svg>';
    }
}
if (!function_exists('iconeAnexo')) {
    // doc
    // exemplo
    // echo iconeAnexo
    /**
     * Gera um icone de anexo
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeAnexo(int $tamanho = 20): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 18 40" style="enable-background:new 0 0 18 40;" xml:space="preserve"><path d="M9,40L9,40c-6.7,0-9-5.3-9-10.3c0-1.3,0-6.4,0-11.4C0,13.2,0,8,0,6.6C0,3.4,1.7,0,6.5,0c2.9,0,4.4,1.3,5.2,2.4c1.1,1.6,1.3,3.4,1.3,4.2l0,23.1c0,2.9-1.5,4.7-4,4.7c-1.5,0-2.7-0.7-3.4-2c-0.5-1-0.6-2-0.6-2.7l0-23c0-0.6,0.5-1.1,1.1-1.1h0c0.6,0,1.1,0.5,1.1,1.1l0,23c0,0.4,0,1.2,0.3,1.8C7.8,32,8.3,32.2,9,32.2c1.2,0,1.8-0.9,1.8-2.5l0-23.1c0-0.5-0.1-1.8-0.9-2.9C9.2,2.7,8,2.2,6.5,2.2C2.7,2.2,2.1,5,2.1,6.6c0,1.4,0,6.5,0,11.7c0,5,0,10.1,0,11.4c0,2.4,0.7,8.1,6.8,8.1h0c2.8,0,4.7-1,5.8-3.1c0.7-1.3,1.1-3.1,1.1-5.1l0-19.4c0-0.6,0.5-1.1,1.1-1.1c0.6,0,1.1,0.5,1.1,1.1l0,19.4c0,2.3-0.5,4.5-1.3,6.1C15.7,37.7,13.5,40,9,40L9,40z"/></svg>';
    }
}
if (!function_exists('iconeMais')) {
    // doc
    // exemplo
    // echo iconeMais
    /**
     * Gera um icone de mais
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeMais(int $tamanho = 15): string
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path d="M36.8,16.8H23.2V3.2C23.2,1.4,21.8,0,20,0c-1.7,0-3.2,1.4-3.2,3.2v13.7H3.2C1.4,16.8,0,18.2,0,20c0,0.9,0.3,1.6,0.9,2.2c0.6,0.6,1.3,0.9,2.2,0.9h13.7v13.7c0,0.9,0.3,1.6,0.9,2.2c0.6,0.6,1.3,0.9,2.2,0.9c1.7,0,3.2-1.4,3.2-3.2V23.2h13.7c1.7,0,3.2-1.4,3.2-3.2C40,18.3,38.6,16.8,36.8,16.8z"/></svg>';
    }
}
if (!function_exists('iconeMenos')) {
    // doc
    // exemplo
    // echo iconeMenos
    /**
     * Gera um icone de menos
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeMenos(int $tamanho = 3)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 10" style="enable-background:new 0 0 40 10;" xml:space="preserve"><path d="M35,10H5c-2.7,0-5-2.2-5-5v0c0-2.7,2.2-5,5-5h30c2.8,0,5,2.2,5,5v0C40,7.8,37.8,10,35,10z"/></svg>';
    }
}
if (!function_exists('iconePlay')) {
    // doc
    // exemplo
    // echo iconePlay
    /**
     * Gera um icone de play
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconePlay(int $tamanho = 20)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M20,0C8.95,0,0,8.95,0,20s8.95,20,20,20s20-8.95,20-20S31.05,0,20,0L20,0z M28.19,20.67l-12.66,9.21c-0.26,0.19-0.58,0.21-0.87,0.07s-0.46-0.42-0.46-0.74l0-18.43c0-0.32,0.17-0.6,0.46-0.74c0.29-0.15,0.61-0.12,0.87,0.07l12.66,9.21c0.22,0.16,0.34,0.4,0.34,0.67C28.54,20.28,28.42,20.51,28.19,20.67L28.19,20.67z"/></svg>';
    }
}
if (!function_exists('iconePause')) {
    // doc
    // exemplo
    // echo iconePause
    /**
     * Gera um icone de pause
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconePause(int $tamanho = 20)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path style="fill-rule:evenodd;clip-rule:evenodd;" d="M40,20c0,11-9,20-20,20S0,31,0,20S9,0,20,0S40,9,40,20L40,20z M10.4,9.8c0-1.1,0.9-1.9,1.9-1.9h4.2 c1.1,0,1.9,0.9,1.9,1.9v20.3c0,1.1-0.9,1.9-1.9,1.9h-4.2c-1.1,0-1.9-0.9-1.9-1.9L10.4,9.8z M23.5,7.9c-1.1,0-1.9,0.9-1.9,1.9v20.3 c0,1.1,0.9,1.9,1.9,1.9h4.2c1.1,0,1.9-0.9,1.9-1.9V9.8c0-1.1-0.9-1.9-1.9-1.9H23.5z"/></svg>';
    }
}
if (!function_exists('iconeLike')) {
    // doc
    // exemplo
    // echo iconeLike
    /**
     * Gera um icone de like
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeLike(int $tamanho = 20)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M40,14v18c0,0.5-0.2,1-0.6,1.4l-6,6C33,39.8,32.5,40,32,40H12c-0.5,0-1-0.2-1.4-0.6L10,38.8V12.6l8.4-11.7h0 C18.7,0.3,19.4,0,20,0h2c0.5,0,1,0.2,1.4,0.6C23.8,1,24,1.5,24,2v10h14c0.5,0,1,0.2,1.4,0.6C39.8,13,40,13.5,40,14L40,14z M6,14H2 c-0.5,0-1,0.2-1.4,0.6C0.2,15,0,15.5,0,16v20c0,0.5,0.2,1,0.6,1.4C1,37.8,1.5,38,2,38h4V14z"/></svg>';
    }
}
if (!function_exists('iconeDeslike')) {
    // doc
    // exemplo
    // echo iconeDeslike
    /**
     * Gera um icone de deslike
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeDeslike(int $tamanho = 20)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M40,26c0,0.5-0.2,1-0.6,1.4C39,27.8,38.5,28,38,28H24v10c0,0.5-0.2,1-0.6,1.4C23,39.8,22.5,40,22,40h-2 c-0.6,0-1.3-0.3-1.6-0.8h0L10,27.4V1.2l0.6-0.6C11,0.2,11.5,0,12,0h20c0.5,0,1,0.2,1.4,0.6l6,6C39.8,7,40,7.5,40,8L40,26L40,26z M6,2H2C1.5,2,1,2.2,0.6,2.6C0.2,3,0,3.5,0,4v20c0,0.5,0.2,1,0.6,1.4C1,25.8,1.5,26,2,26h4V2z"/></svg>';
    }
}
if (!function_exists('iconeFixar')) {
    // doc
    // exemplo
    // echo iconeFixar
    /**
     * Gera um icone de fixar
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeFixar(int $tamanho = 20)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.828 1.686l8.486 8.486-1.415 1.414-.707-.707-4.242 4.242-.707 3.536-1.415 1.414-4.242-4.243-4.95 4.95-1.414-1.414 4.95-4.95-4.243-4.242 1.414-1.415L8.88 8.05l4.242-4.242-.707-.707 1.414-1.415zm.708 3.536l-4.671 4.67-2.822.565 6.5 6.5.564-2.822 4.671-4.67-4.242-4.243z"/></svg>';
    }
}
if (!function_exists('iconeRelogio')) {
    // doc
    // exemplo
    // echo iconeRelogio
    /**
     * Gera um icone de relógio
     *
     * @param   int     $tamanho    Altura do atributo height do svg
     * @return  string              SVG do icone
     */
    function iconeRelogio(int $tamanho = 20)
    {
        return '<svg height="' . $tamanho . '" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 30 30" style="enable-background:new 0 0 30 30;" xml:space="preserve"><path d="M15,30C6.7,30,0,23.3,0,15C0,6.7,6.7,0,15,0c8.3,0,15,6.7,15,15C30,23.3,23.3,30,15,30z M15,27c6.6,0,12-5.4,12-12c0-6.6-5.4-12-12-12C8.4,3,3,8.4,3,15C3,21.6,8.4,27,15,27z M16.5,15h6v3h-9V7.5h3V15z"/></svg>';
    }
}
