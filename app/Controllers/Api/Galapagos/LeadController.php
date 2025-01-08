<?php

namespace App\Controllers\Api\Galapagos;

use Http\Request;
use Modules\Nome;
use Http\Response;
use Modules\Botao;
use Modules\Email;
use Modules\Telefone;
use Controller\Controller;
use App\Models\Api\Galapagos\Api\Redirect;
use App\Models\Api\Galapagos\Lead\LeadEntity;
use System\Interface\ControllerSalvarInterface;

final class LeadController extends Controller implements ControllerSalvarInterface
{
    public function postSalvar(Request $request): Response
    {
        $Lead = new LeadEntity(
            nome: new Nome($request->nome),
            email: new Email($request->email),
            telefone: new Telefone($request->telefone),
            termo: new Botao($request->termo)
        );
        $Lead->salvar();

        $Redirect = new Redirect(
            Lead: $Lead
        );

        return mensagemSucesso(dado: [
            'link' => $Redirect->link
        ], status: 201);
    }
}
