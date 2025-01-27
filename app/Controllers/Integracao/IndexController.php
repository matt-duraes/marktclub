<?php

namespace App\Controllers\Integracao;

use Controller\Controller;

final class IndexController extends Controller
{
    public function direto(string $parceiro, string $hash)
    {
        $dado = base64Decode($hash);
        if (!validarIndiceExiste($dado, ['parceiro', 'data'])) {
            mensagemStatus(404);
        }

        return view('direto', var: [
            'parceiro' => $dado['parceiro']
        ]);
    }
}
