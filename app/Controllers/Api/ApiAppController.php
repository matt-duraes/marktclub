<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\ApiApp\AppModel;
use App\Models\Api\ApiApp\AppEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;

final class ApiAppController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface
{
    public function getListar(Request $request): Response
    {
        $App = new AppModel($request);
        $dado = $App->listarDados();

        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id): Response
    {
        validarUuid($id);
        $App = new AppEntity();
        $App->uuid($id);

        return $this->retornoPadrao($App);
    }

    private function retornoPadrao(AppEntity $App)
    {
        $dado = pegarPropriedadeDaEntity($App, lista: [
            'nome', 'descricao', 'id_admin_empresa', 'tempo_vida', 'redirect_uri', 'scope_permitido',
            'campo_permitido', 'chave_privada_publica', 'chave_publica_publica',
            'authorization_code', 'client_credentials', 'refresh_token', 'imagem'
        ]);
        return mensagemSucesso($dado);
    }
}
