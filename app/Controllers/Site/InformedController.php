<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Classes\Comercial\Empresa\UUID;

final class InformedController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        if(!in_array(CLUBE_EMPRESA, [UUID::CFM, UUID::YOUHUUL])) {
            mensagemStatus(404);
        }

        return view('informed', [
            'menu' => 'informed'
        ]);
    }
}
