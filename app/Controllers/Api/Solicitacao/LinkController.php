<?php

namespace App\Controllers\Api\Solicitacao;

use Controller\Controller;

final class LinkController extends Controller
{
    public function redirecionar(string $hash)
    {
        ppe($hash);
    }
}
