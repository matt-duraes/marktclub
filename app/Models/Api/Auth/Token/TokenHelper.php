<?php

namespace App\Models\Api\Auth\Token;

final class TokenHelper
{
    public const AUDIENCE_CLUBE = 'clube';
    public const AUDIENCE_PAINEL = 'painel';

    public function audience(bool $erro = false): string
    {
        $existe = defined('TOKEN') && array_key_exists('app', TOKEN) && object_key_exists('audience', TOKEN['app']) && !empty(TOKEN['app']->audience);
        if (!$existe && $erro) {
            mensagemStatus(400, localhost: 'Não foi encontrado um token e/ou audience');
        }
        return $existe ? TOKEN['app']->audience : '';
    }

    public function eClube(bool $erro = false): bool
    {
        $eClube = $this->audience($erro) === TokenHelper::AUDIENCE_CLUBE;
        if (!$eClube && $erro) {
            mensagemStatus(400, localhost: 'O token não é do clube');
        }
        return $eClube;
    }

    public function ePainel(bool $erro = false): bool
    {
        $ePainel = $this->audience($erro) === TokenHelper::AUDIENCE_PAINEL;
        if (!$ePainel && $erro) {
            mensagemStatus(400, localhost: 'O token não é do painel');
        }
        return $ePainel;
    }

    public function pegarEmpresa(bool $erro = false): int
    {
        $existe = defined('TOKEN') && array_key_exists('empresa', TOKEN) && object_key_exists('id', TOKEN['empresa']) && !empty(TOKEN['empresa']->id);
        if (!$existe && $erro) {
            mensagemStatus(400, localhost: 'Não foi encontrado um token e/ou empresa');
        }
        return $existe ? TOKEN['empresa']->id : 0;
    }

    public function pegarUsuario(bool $erro = false): int
    {
        $existe = defined('TOKEN') && array_key_exists('usuario', TOKEN) && object_key_exists('id', TOKEN['usuario']) && !empty(TOKEN['usuario']->id);
        if (!$existe && $erro) {
            mensagemStatus(400, localhost: 'Não foi encontrado um token e/ou usuario');
        }
        return $existe ? TOKEN['usuario']->id : 0;
    }
}
