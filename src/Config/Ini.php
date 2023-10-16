<?php

ini_set('session.cookie_httponly', 1);

ini_set('expose_php', 0);
ini_set('allow_url_fopen', 0);
ini_set('register_globals', 0);

$__APP_TIMEZONE = env('APP_TIMEZONE', '');
if ($__APP_TIMEZONE) {
    date_default_timezone_set($__APP_TIMEZONE);
}

$__APP_DEBUG = env('APP_DEBUG', '');
if (true === $__APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('log_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

$__INI_LISTA = env('INI_LISTA', '');
if ($__INI_LISTA) {
    foreach ($__INI_LISTA as $ind => $val) {
        ini_set($ind, $val);
    }
}

unset(
    $__APP_TIMEZONE,
    $__APP_DEBUG,
    $__INI_LISTA,
);
