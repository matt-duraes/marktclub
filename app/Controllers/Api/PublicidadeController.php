<?php

namespace App\Controllers\Api;

use App\Models\Api\Publicidade\PublicidadeEntity;
use App\Models\Api\Publicidade\PublicidadeModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use ORM\Entity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class PublicidadeController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Publicidade = new PublicidadeEntity();
        $Publicidade->idSlug($id);
        return $this->retornoPadrao($Publicidade);
    }

    /**
     * @param Entity $Entity Entidade da Publicidade
     * @param int    $status Status code que deverá ser retornado
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(Entity $Entity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Entity,
                lista: [
                    'titulo', 'imagem', 'target', 'link', 'tipo'
                ]
            ),
            $status
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $PublicidadeModel = new PublicidadeModel($request);
        return mensagemSucesso($PublicidadeModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Publicidade = new PublicidadeEntity();
        $Publicidade->set(lista: $request->dado());
        $Publicidade->salvar();
        return $this->retornoPadrao($Publicidade, 201);
    }
}
