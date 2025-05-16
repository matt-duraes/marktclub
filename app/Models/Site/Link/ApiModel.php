<?php

namespace App\Models\Site\Link;

final class ApiModel
{
    public string $link     = '';
    public array  $linkProd = [
        'http://api1.youhuullocal.com',
        'http://api2.youhuullocal.com',
        'http://api3.youhuullocal.com',
    ];

    public function __construct()
    {
        $link = env('API_LINK', LINK_API);
        $this->link = $link;
        if ($link == 'https://apiv4hmlprod.youhuul.com') {
            $this->link = 'https://apiv4hmlprod.youhuul.com';
            return;
        } elseif ($link != 'https://apiv4.youhuul.com') {
            return;
        }

        if (!sessaoExiste('LINK_API_PROD')) {
            sessao('LINK_API_PROD', $this->linkProd[rand(0, 2)]);
        }
        $this->link = sessao('LINK_API_PROD');
    }
}


