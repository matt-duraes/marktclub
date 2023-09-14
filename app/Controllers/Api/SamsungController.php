<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Samsung\ValidarModel;

final class SamsungController extends Controller
{
    public function PostValidar(Request $request, $user = null, $validate = null): Response
    {
        $Validar = new ValidarModel($request->getGet('code'));
        return new Response(
            json: $Validar->validar(),
            header: [
                'auth_samsung' => $Validar->pegarHeader(),
            ]
        );
    }
}
