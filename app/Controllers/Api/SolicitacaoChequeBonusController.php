<?php

namespace App\Controllers\Api;

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoChequeBonus\Ordem;
use App\Models\Api\SolicitacaoChequeBonus\ChequeBonusEntity;
use App\Models\Api\SolicitacaoChequeBonus\ChequeBonusModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;

class SolicitacaoChequeBonusController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $ChequeBonus = new ChequeBonusEntity();
        $ChequeBonus->uuid($id);
        return $this->retornoSucesso($ChequeBonus);
    }

    /**
     * @param ChequeBonusEntity $ChequeBonus
     * @param int               $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoSucesso(ChequeBonusEntity $ChequeBonus, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($ChequeBonus, lista: [
                'id', 'automovel', 'usuario', 'dependente', 'tipo_usuario', 'nome', 'email_pessoal',
                'data_nascimento', 'endereco_cep', 'endereco_logradouro', 'endereco_numero',
                'endereco_complemento', 'endereco_bairro', 'endereco_cidade', 'endereco_estado', 'rg',
                'data_criacao', 'data_atualizacao', 'data_termo', 'status'
            ]),
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
        $ChequeBonus = new ChequeBonusModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->empresa,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($ChequeBonus->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $ChequeBonus = new ChequeBonusEntity();
        $ChequeBonus->set(lista: $request->dado());
        $ChequeBonus->salvar();
        return $this->retornoSucesso($ChequeBonus, 201);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $ChequeBonus = new ChequeBonusEntity();
        $ChequeBonus->uuid($id);
        $ChequeBonus->set(lista: $request->dado());
        $ChequeBonus->salvar();
        return new Response(status: 204);
    }
}
