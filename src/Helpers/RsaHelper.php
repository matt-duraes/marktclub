<?php

namespace Helpers;

use phpseclib3\Crypt\PublicKeyLoader;

final class RsaHelper
{
    public function __construct(
        private ?string $publicKey = null,
        private ?string $privateKey = null
    ) {
    }

    public function encode($dado)
    {
        if (is_array($dado) || is_object($dado)) {
            $dado = json_encode($dado);
        }

        $chave = PublicKeyLoader::loadPublicKey($this->publicKey)->withHash('sha1')->withMGFHash('sha1');
    }

    public function criarChave(int $bits = 2048)
    {
        //
    }
}
