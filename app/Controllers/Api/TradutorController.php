<?php

namespace App\Controllers\Api;

use App\Helpers\GoogleTradutorHelper;
use Controller\Controller;
use Erro\Excecao;
use Google\Cloud\Core\Exception\ServiceException;
use Http\Request;
use Http\Response;

class TradutorController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     * @throws ServiceException
     */
    public function getTraduzir(Request $request): Response
    {
        $TradutorHelper = new GoogleTradutorHelper();
        return mensagemSucesso([
            'traducao' => [
                'en' => $TradutorHelper->traduzir($request->texto),
                'es' => $TradutorHelper->traduzir($request->texto, 'es')
            ]
        ]);
    }
}
