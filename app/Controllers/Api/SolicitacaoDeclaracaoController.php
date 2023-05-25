<?php

namespace App\Controllers\Api;

use App\Controllers\Api\Trait\ClienteTrait;
use App\Models\Api\SolicitacaoDeclaracao\DeclaracaoEntity;
use App\Models\Api\SolicitacaoDeclaracao\DeclaracaoModel;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class SolicitacaoDeclaracaoController implements
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerListarInterface
{
    use ClienteTrait;

    /**
     * @param  string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Declaracao = new DeclaracaoEntity();
        $Declaracao->uuid($id);

        return $this->retornoSucesso($Declaracao);
    }

    /**
     * @param  DeclaracaoEntity  $Declaracao
     * @param  int               $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(DeclaracaoEntity $Declaracao, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Declaracao,
                lista: [
                    'vinculo', 'tipo', 'status', 'data_criacao'
                ]
            ),
            $status
        );
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Declaracao = new DeclaracaoModel($request);
        return mensagemSucesso($Declaracao->listarDados());
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Declaracao = new DeclaracaoEntity();
        $Declaracao->set(lista: $request->dado());
        $Declaracao->salvar();

        return $this->retornoSucesso($Declaracao, 201);
    }
}
