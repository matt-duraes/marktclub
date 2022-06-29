<?php

namespace App\Controllers\Api;

use Http\Response;
use Helpers\CryptHelper;
use Controller\Controller;

final class ApiLocalhostController extends Controller
{
    public function getCriptografar(string $valor)
    {
        return new Response(json: [
            'retorno' => (new CryptHelper(chavePublica: TOKEN['app']->chave_publica))->encode($valor)
        ]);
    }
}
