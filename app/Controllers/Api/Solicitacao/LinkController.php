<?php

namespace App\Controllers\Api\Solicitacao;

use Http\Response;
use Controller\Controller;
use App\Models\Api\Solicitacao\Link\ResgatarEntity;

final class LinkController extends Controller
{
    public function confirmar(string $hash)
    {
        $dado = base64Decode($hash, true);
        if (!$dado) {
            mensagemStatus(404);
        }
        return view('redirecionar', [
            'parceiro' => (object)$dado['parceiro'],
            'clube'    => (object)$dado['clube'],
            'hash'     => $hash
        ]);
    }

    public function redirecionar(string $hash)
    {
        $Link = new ResgatarEntity(
            dado: base64Decode($hash, true)
        );
        return new Response(url: $Link->link);
    }
}
