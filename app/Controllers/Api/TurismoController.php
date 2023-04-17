<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\Turismo\TokenModel;
use App\Controllers\Api\Trait\ClienteTrait;
use App\Models\Api\Turismo\ValidarUsuarioModel;

final class TurismoController extends Controller
{
    use ClienteTrait;

    public function postToken(Request $request)
    {
        $Turismo = new TokenModel(
            Usuario: $this->pegarCliente($request->usuario, obrigatorio: true),
            ip: $request->ip,
            userAgent: $request->user_agent,
            memoria: $request->memoria
        );

        return mensagemSucesso([
            'link' => $Turismo->pegarLink()
        ]);
    }

    public function getValidarUsuario(string $usuario)
    {
        new ValidarUsuarioModel(
            Usuario: $this->pegarCliente($usuario, obrigatorio: true)
        );
        return new Response(status: 200);
    }
}
