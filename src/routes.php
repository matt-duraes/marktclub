<?php 
use core\Router;

$router = new Router();


$router->get('/', 'HomeController@index'); 
$router->get('/login', 'LoginController@signin');
$router->post('/login', 'LoginController@signinAction');
$router->get('/cadastro', 'LoginController@signup');
$router->post('/cadastro', 'LoginController@signupAction');

$router->get('/usuario/{id}/editar', 'LoginController@edit');
$router->post('/usuario/{id}/editar', 'LoginController@editAction');

$router->get('/usuario/{id}/excluir', 'LoginController@del');

$router->get('/pesquisa', 'SearchController@index');
$router->get('/sair','LoginController@sair');