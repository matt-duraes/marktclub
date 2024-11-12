<?php

namespace App\Helpers;

final class LinkClubeHelper
{
    public string $link = '';

    public function __construct(string $link)
    {
        if (empty($link)) {
            return;
        }
        $this->link = str_replace(
            [
                'https://clube.markt.club', 'https://clube.marktclub.com.br', 'http://clube.markt.club',
                'http://clube.marktclub.com.br', 'https://clube.youhuul.com', 'https://clube.youhuul.com.br'
            ],
            LINK,
            $link
        );
    }
}
