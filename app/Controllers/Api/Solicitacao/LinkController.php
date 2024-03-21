<?php

namespace App\Controllers\Api\Solicitacao;

use Http\Response;
use Controller\Controller;
use App\Models\Api\Solicitacao\Link\HashModel;
use App\Models\Api\Solicitacao\Link\LimiteModel;
use App\Models\Api\Solicitacao\Link\ResgatarEntity;

final class LinkController extends Controller
{
    public function confirmar(string $hash)
    {
        $Hash = new HashModel($hash);
        $Limite = new LimiteModel($Hash);
        return view('redirecionar', [
            'parceiro' => $Hash->parceiro,
            'clube'    => $Hash->clube,
            'saldo'    => $Limite->saldo,
            'usado'    => $Limite->usado,
            'lista'    => $Limite->lista,
            'hash'     => $hash
        ]);
    }

    public function redirecionar(string $hash)
    {
        $Link = new ResgatarEntity(new HashModel($hash));
        return new Response(url: $Link->link);
    }
}
