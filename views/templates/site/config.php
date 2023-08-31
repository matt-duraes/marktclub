<?php

$USUARIO = sessao('USUARIO');

define('USUARIO_NOME', $USUARIO['nome']);
define('USUARIO_IMAGEM', $USUARIO['imagem']);
define('USUARIO_EMAIL', $USUARIO['email']);

define('MENU_HOVER', isset($menu) ? $menu : '');

include ROOT . '/resources/php/site/icone.php';
