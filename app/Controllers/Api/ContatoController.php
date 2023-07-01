<?php

namespace App\Controllers\Api;

use App\Models\Api\Contato\ContatoEntity;
use App\Models\Api\Contato\ContatoModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class ContatoController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface
{
    /**
     * @param  string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Contato = new ContatoEntity();
        $Contato->uuid($id);

        return $this->retornoSucesso($Contato);
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Contato = new ContatoModel($request);
        return mensagemSucesso($Contato->listarDados());
    }
    /**
    * @param  ContatoEntity  $contatoEntity
    *
    * @return Response
    * @throws Excecao
    */
    public function postSalvar(Request $request): Response
    {
        $Contato = new ContatoEntity();
        $Contato->set(lista: $request->dado());
        $Contato->salvar();

        return $this->retornoSucesso($Contato, 201);
    }


    /**
     * @param  ContatoEntity  $contatoEntity
     * @param  int            $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(ContatoEntity $contatoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $contatoEntity,
                lista: [
                    'id_admin_empresa', 'nome', 'email', 'telefone', 'mensagem', 'url', 'descoberta_site'
                ]
            ),
            $status
        );
    }
}
