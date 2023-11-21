<?php

namespace App\Controllers\Api;

use App\Classes\UsuarioDependente\Helper;
use App\Models\Api\UsuarioCliente\DeletarModel;
use App\Models\Api\UsuarioDependente\DependenteEntity;
use App\Models\Api\UsuarioDependente\DependenteModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use SendGrid\Mail\TypeException;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

final class UsuarioDependenteController extends Controller implements
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerDeletarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $DependenteModel = new DependenteModel($request->usuario);
        return mensagemSucesso($DependenteModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $DependenteEntity = new DependenteEntity();
        $DependenteEntity->set(lista: $request->dado());
        $DependenteEntity->salvar();
        return mensagemSucesso(
            pegarPropriedadeDaEntity($DependenteEntity, lista: [
                'uuid', 'nome', 'email', 'cpf', 'status'
            ]),
            201,
            Helper::CRIPTOGRAFAR
        );
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $DeletarModel = new DeletarModel();
        $DeletarModel->uuid($id);
        $DeletarModel->deletar();
        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     * @throws TypeException
     */
    public function postEmail(Request $request): Response
    {
        $DependenteEntity = new DependenteEntity();
        $DependenteEntity->uuid($request->usuario);
        $DependenteEntity->enviarEmail();
        return mensagemSucesso(
            ['E-mail enviado com sucesso!']
        );
    }
}
