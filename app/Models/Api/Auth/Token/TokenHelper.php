<?php

namespace App\Models\Api\Auth\Token;

final class TokenHelper
{
    public const AUDIENCE_CLUBE = 'clube';

    public function audience(bool $erro = false): string
    {
        $existe = defined('TOKEN') && array_key_exists('app', TOKEN) && object_key_exists('audience', TOKEN['app']) && !empty(TOKEN['app']->audience);
        if(!$existe && $erro) {
            mensagemStatus(400, localhost: 'Não foi encontrado um token e/ou audience');
        }
        return $existe ? TOKEN['app']->audience : '';
    }

    public function eClube(bool $erro = false): bool
    {
        $eClube = $this->audience($erro) === TokenHelper::AUDIENCE_CLUBE;
        if(!$eClube && $erro) {
            mensagemStatus(400, localhost: 'O token não é do clube');
        }
        return $eClube;
    }

    public function pegarEmpresa(bool $erro = false): int
    {
        $existe = defined('TOKEN') && array_key_exists('empresa', TOKEN) && object_key_exists('id', TOKEN['empresa']) && !empty(TOKEN['empresa']->id);
        if(!$existe && $erro) {
            mensagemStatus(400, localhost: 'Não foi encontrado um token e/ou empresa');
        }
        return $existe ? TOKEN['empresa']->id : 0;
    }
}
