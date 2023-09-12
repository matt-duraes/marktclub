<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Samsung\LogModel;
use App\Models\Api\Samsung\ValidarModel;

final class SamsungController extends Controller
{
    public function PostValidar(Request $request, $user = null, $validate = null): Response
    {
        new LogModel();
        $Validar = new ValidarModel($request->getGet('code'));
        $token = $Validar->validar();
        return new Response(
            json: $token,
            header: [
                'auth_samsung' => $token
            ]
        );
    }
}
