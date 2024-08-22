<?php

namespace App\Models\Site\Webview;

final class MapaModel implements LocalInterface
{
    public function __construct(
        private ?string $latitude,
        private ?string $longitude,
    ) {
        if (empty($latitude) || empty($longitude)) {
            mensagemStatus(404);
        }
    }

    public function link(): string
    {
        return LINK . '/convenios';
    }
}
