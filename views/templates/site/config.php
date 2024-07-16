<?php

use App\Models\Site\Popup\PopupModel;

$USUARIO = sessao('USUARIO');

define('USUARIO_ID', $USUARIO['id']);
define('USUARIO_NOME', $USUARIO['nome']);
define('USUARIO_IMAGEM', $USUARIO['imagem']);
define('USUARIO_EMAIL', $USUARIO['email']);
define('TIPO_USUARIO', $USUARIO['tipo']);

define('MENU_HOVER', isset($menu) ? $menu : '');

include ROOT . '/resources/php/site/icone.php';
include ROOT . '/resources/php/site/tema.php';
$popupPromocao = (new PopupModel())->listar();

$Botao = new \ResourcesSite\Componente\Botao();
