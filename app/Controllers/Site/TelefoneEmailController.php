<?php

namespace App\Controllers\Site;

use Http\Request;
use Controller\Controller;
use App\Models\Site\TelefoneEmail\ContatoModel;

final class TelefoneEmailController extends Controller
{
    public function postListar(Request $request)
    {
        $Contato = new ContatoModel(
            vinculo: $request->id,
            local: $request->local,
            tipo: $request->tipo,
            pagina: $request->pagina
        );
        return mensagemSucesso($Contato->contato);
    }
}
