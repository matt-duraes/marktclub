<?php

namespace App\Controllers\Integracao;

use Controller\Controller;

final class IndexController extends Controller
{
    public function direto(string $parceiro, string $hash)
    {
        $Hash = new Hash(hash: $hash);
        return view('direto', [
            'dado' => $Hash->dado
        ]);
    }
}
