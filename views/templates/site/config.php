<?php

$CLUBE = sessao('CLUBE');
$USUARIO = sessao('USUARIO');

define('CLUBE_LOGO', $CLUBE['link_logo']);
define('CLUBE_TITULO', $CLUBE['titulo']);
define('CLUBE_ID', $CLUBE['id']);
define('CLUBE_COR', $CLUBE['cor']);

define('CLUBE_FINALIDADE', 1);

define('USUARIO_NOME', $USUARIO['nome']);
define('USUARIO_IMAGEM', $USUARIO['imagem']);
define('USUARIO_EMAIL', $USUARIO['email']);

define('CONTATO_TELEFONE', '(61) 99354-6881');
define('CONTATO_WHATSAPP', '(61) 99354-6881');
define('CONTATO_EMAIL', 'atendimento@markt.club');
define('CONTATO_HORARIO', 'Seg. à Sex. das 9h às 19h');
define('CONTATO_ENDERECO', 'SIG Quadra 4 Lote 125, Bloco A Sala 10 - Asa Sul, Brasília/DF - CEP: 70610-440');

define('MENU_LOJA', true);
define('MENU_LOJA_PROXIMA', true);
define('MENU_CUPOM', true);
define('MENU_CASHBACK', true);
define('MENU_EXTENSAO', true);
define('MENU_PROMOCAO', true);

define('MENU_CINEMA', true);
define('MENU_TURISMO', true);
define('MENU_SALAVIP', true);

define('MENU_CREDITO_SICOOB', true);
define('MENU_CREDITO_CONSIGNADO', true);
define('MENU_CREDITO_AUTOMOVEL', true);
define('MENU_CREDITO', true);

define('MENU_ODONTOLOGICO', true);
define('MENU_SEGURO_DE_VIDA', true);
define('MENU_MEDICAMENTO', true);
define('MENU_FEDERAL_SAUDE', true);

define('MENU_CONSULTORIA', true);

define('MENU_HOVER', isset($menu) ? $menu : '');

include ROOT . '/resources/php/site/icone.php';
