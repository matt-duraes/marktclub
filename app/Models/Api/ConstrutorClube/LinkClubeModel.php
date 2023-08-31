<?php

namespace App\Models\Api\ConstrutorClube;

final class LinkClubeModel
{
    public string $url;

    public function __construct(string $url)
    {
        $url = preg_replace('/^http(s)?\:\/\/(www.)?/', '', $url);
        $lista = jsonDecode(env('API_REDIRECT_URI_HOMOLOGACAO', ''), true, true);
        $this->url = $lista[$url] ?? $url;
    }
}
