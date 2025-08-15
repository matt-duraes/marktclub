<?php

use Modules\Botao;
use Helpers\ApiHelper;
use App\Models\Site\Popup\PopupModel;
use App\Classes\Comercial\Empresa\UUID;

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

$webview = isset($webview) && $webview;

// VOUCHER GEAP
$voucher = false;
if(in_array(CLUBE_EMPRESA, [UUID::GEAP, UUID::YOUHUUL])) {
    if(sessaoExiste('VOUCHER_GEAP') && eProducao()) {
        $voucher = sessao('VOUCHER_GEAP');
    }
    try {
        $buscarVoucher = (new ApiHelper(token: true))->get('/campanha-voucher-disponivel')->object();
        $voucher = validarIndiceExiste($buscarVoucher, 'dado.temVoucher') && $buscarVoucher->dado->temVoucher === Botao::SIM;
    } catch (\Throwable $th) {
        $voucher = false;
    }
    sessao('VOUCHER_GEAP', $voucher);
}
