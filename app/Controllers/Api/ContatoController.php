<?php

namespace App\Controllers\Api;

use App\Models\Api\Contato\ContatoEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerSalvarInterface;

class ContatoController extends Controller implements
    ControllerSalvarInterface
{
    /**
    * @param  ContatoEntity  $enqueteEntity
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
