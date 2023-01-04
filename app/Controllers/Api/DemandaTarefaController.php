<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Classes\DemandaTarefa\Tipo;
use App\Models\Api\Demanda\TarefaEntity;
use App\Controllers\Api\Interface\SalvarInterface;

final class DemandaTarefaController extends Controller implements SalvarInterface
{
    public function postSalvar(Request $request)
    {
        $Demanda = new TarefaEntity(
            demanda: $request->demanda,
            titulo: $request->titulo,
            texto: $request->_POST('texto', html: false),
            tipo: new Tipo($request->tipo),
            hora_producao_estimada: $request->hora_producao_estimada,
            equipe: $request->equipe
        );
        $Demanda->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Demanda,
                lista: [
                    'id', 'titulo', 'texto', 'tipo', 'data_criacao', 'hora_producao_estimada', 'status'
                ]
            ),
            201
        );
    }
}
