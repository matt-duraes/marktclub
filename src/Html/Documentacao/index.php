<?php

$uri  = explode('/', preg_replace('/^\/__documentacao\/{0,1}/', '', $_SERVER['REQUEST_URI']));
$pagina = array_key_exists(0, $uri) && !empty($uri[0]) ? trim($uri[0]) : 'start';
$view = array_key_exists(1, $uri) && !empty($uri[1]) ? trim($uri[1]) : 'index';

require_once 'view/index.php';
