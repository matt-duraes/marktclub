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
        return new Response(
            json: $Validar->validar(),
            header: [
                'user_name_partner'     => $Validar->userName,
                'user_password_partner' => $Validar->password,
                'user_token_partner'    => $Validar->token,
            ]
        );
    }
}
