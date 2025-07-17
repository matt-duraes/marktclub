<?php

namespace App\Models\Site\Link;

final class ApiModel
{
    public string $link     = '';

    public function __construct()
    {
        $link = env('API_LINK', LINK_API);
        $local = env('API_LINK_LOCAL', []);
        if(!is_array($local) || empty($local)) {
            $this->link = $link;
            return;
        }

        if (!sessaoExiste('LINK_API_PROD')) {
            $indice = array_rand($local);
            sessao('LINK_API_PROD', $local[$indice]);
        }
        $this->link = sessao('LINK_API_PROD');
    }
}


