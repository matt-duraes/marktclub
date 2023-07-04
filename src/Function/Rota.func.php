<?php

/*
|--------------------------------------------------------------------------
| PEGA LINK DA ROTA
|--------------------------------------------------------------------------
|
| Pega o link da rota usando o método e os names do grupo/action
|
*/
if (!function_exists('route')) {
    /**
     * @param string $local Local da rota que deseja pegar: 'post.grupo.rota' ou apensa 'grupo.rota' para views
     */
    function route(string $rota)
    {
        return LINK . \Route\Route::link($rota);
    }
}

/*
|--------------------------------------------------------------------------
| PEGA URI DA ROTA
|--------------------------------------------------------------------------
|
| Pega o uri da rota usando o método e os names do grupo/action
|
*/
if (!function_exists('uri')) {
    /**
     * @param string $rota Local da rota que deseja pegar: 'post.grupo.rota' ou apensa 'grupo.rota' para views
     */
    function routeUri($rota)
    {
        return \Route\Route::link($rota);
    }
}
