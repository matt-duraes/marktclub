<?php

$listaTeste = explode(',', preg_replace('/^\/{0,1}__tests\/{0,1}/', '', $_SERVER['REQUEST_URI']));
if (is_array($listaTeste) && count($listaTeste) > 0 && !empty($listaTeste[0])) {
    include __DIR__ . '/Models/Teste.php';
    include __DIR__ . '/Views/teste.php';
} else {
    include __DIR__ . '/Views/index.php';
}
include __DIR__ . '/Views/loading.php';
