<?php

namespace App\Classes\SolicitacaoVoucher;

use App\Classes\ParceiroLoja\Helper as ParceiroLojaHelper;
use App\Classes\UsuarioCliente\Helper as UsuarioClienteHelper;

final class Helper
{
    public const CRIPTOGRAFAR = [
        'usuario'  => UsuarioClienteHelper::CRIPTOGRAFAR,
        'parceiro' => ParceiroLojaHelper::CRIPTOGRAFAR
    ];
    public const PERMISSAO_EMPRESA = 'solicitacao_voucher_empresa';
}
