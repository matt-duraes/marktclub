<?php

namespace App\Controllers\Login;

use Http\Request;
use Helpers\AuthHelper;
use Controller\Controller;
use App\Models\Login\LogarModel;

final class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function postLogar(Request $request)
    {
        (new LogarModel($request->login, $request->senha));
        return mensagemSucesso([
            'link' => $this->pegarLocation()
        ], status: 201);
    }

    private function pegarLocation(): string
    {
        $link = (new AuthHelper())->location();
        if (preg_match('/\/login\/?$/', $link)) {
            return LINK_PADRAO;
        }
        return $link;
    }
}
