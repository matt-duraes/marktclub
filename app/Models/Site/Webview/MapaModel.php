<?php

namespace App\Models\Site\Webview;

final class MapaModel implements LocalInterface
{

    public function __construct(
        private string $latitude,
        private string $longitude
    )
    {

    }

    public function link(): string
    {
        return '';
    }
}
