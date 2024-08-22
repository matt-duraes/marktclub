<?php

namespace App\Controllers\Site\Webview;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Webview\MapaModel;
use App\Models\Site\Webview\LoginModel;
use App\Models\Site\Webview\LocalInterface;

final class LoginController extends Controller
{
    public function mapa(Request $request)
    {
        return $this->login(new MapaModel(
            latitude: $request->latitude,
            longitude: $request->longitude
        ));
    }

    private function login(LocalInterface $Local)
    {
        $Login = new LoginModel($Local);
        return new Response(url: $Login->link);
    }
}
