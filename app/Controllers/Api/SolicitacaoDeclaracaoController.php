<?php

namespace App\Controllers\Api;

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
                    'empresa', 'usuario', 'tipo', 'vinculo', 'cpf', 'valor', 'estado_civil',
                    'documento_rg', 'data_nascimento', 'cep', 'estado', 'cidade', 'bairro',
                    'numero', 'logradouro', 'complemento', 'dependente_cpf', 'data_validacao', 'status'
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
