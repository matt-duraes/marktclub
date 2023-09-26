<?php

namespace App\Controllers\Site;

use Http\Request;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Helpers\ClubeApiHelper;
use App\Classes\ParceiroEasylive\Tipo;

final class EasyliveController extends Controller
{
    public function view($tipo)
    {
        return view('easylive', [
            'tipo' => $tipo,
            'menu' => 'easylive-' . $tipo
        ]);
    }

    public function postListar(Request $request)
    {
        try {
            $lista = (new ClubeApiHelper('parceiro_easylive:listar'))
                ->json([
                    'pagina'     => 1,
                    'tipo'       => $request->tipo,
                    'status'     => Status::ATIVO
                ])
                ->get('/parceiro-easylive')
                ->object()->dado->lista ?? [];
        } catch (\Throwable) {
            $lista = [];
        }

        return mensagemSucesso($lista);
    }

    public function corrida()
    {
        return $this->view(Tipo::CORRIDA);
    }

    public function nacional()
    {
        return $this->view(Tipo::SHOW_NACIONAL);
    }

    public function internacional()
    {
        return $this->view(Tipo::SHOW_INTERNACIONAL);
    }
}
