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

define('CONTATO_TELEFONE', strTelefone($CLUBE['contato_telefone']));
define('CONTATO_WHATSAPP', strTelefone($CLUBE['contato_whatsapp']));
define('CONTATO_EMAIL', $CLUBE['contato_email']);
define('CONTATO_HORARIO', $CLUBE['horario_atendimento']);
define('CONTATO_ENDERECO', $CLUBE['contato_endereco']);

define('API', $CLUBE['api']);

$pagina = $CLUBE['menu'];
define('MENU_ACESSO_RAPIDO', $pagina['acesso_rapido']);
define('MENU_PREMIUM', $pagina['premium']);
define('MENU_LOJA', $pagina['convenio']);
define('MENU_LOJA_PROXIMA', $pagina['convenio_mapa']);
define('MENU_AUTOMOVEL', $pagina['automovel']);
define('MENU_CUPOM', $pagina['cupom']);
define('MENU_CASHBACK', $pagina['cashback']);
define('MENU_CINEMA', $pagina['cinema']);
define('MENU_TURISMO', $pagina['turismo']);
define('MENU_SALAVIP', $pagina['salavip']);
define('MENU_CREDITO_SICOOB', $pagina['sicoob_credito']);
define('MENU_MEDICAMENTO', $pagina['medicamento']);
define('MENU_SAUDE', $pagina['saude_vitoria'] || $pagina['saude_amil'] || $pagina['saude_seguros']);
define('MENU_ODONTOLOGIA', $pagina['odontologia']);
define('MENU_INDICACAO', $pagina['indicacao']);
define('MENU_HISTORICO', $pagina['historico']);
define('MENU_DEPENDENTE', $pagina['dependente']);
define('MENU_CARTEIRA', $pagina['carteira']);
define('MENU_SAIR', $pagina['sair']);
define('MENU_PERFIL', !API || MENU_DEPENDENTE || MENU_CASHBACK || MENU_INDICACAO);

define('LINK_APP_ANDRIOD', $CLUBE['link_app_android']);
define('LINK_APP_IOS', $CLUBE['link_app_ios']);
define('MENU_BAIXAR_APP', !empty(LINK_APP_ANDRIOD) || !empty(LINK_APP_IOS));

define('MENU_HOVER', isset($menu) ? $menu : '');

include ROOT . '/resources/php/site/icone.php';
