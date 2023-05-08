<?php

namespace App\Controllers\Api;

use App\Classes\SolicitacaoAlfa\Helper;
use App\Models\Api\SolicitacaoAlfa\SolicitacaoEntity;
use Erro\Excecao;
use Http\Request;
use Http\Response;

class SolicitacaoAlfaController
{
    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSolicitacao(Request $request): Response
    {
        $solicitacaoCreditoEntity = new SolicitacaoEntity();
        $solicitacaoCreditoEntity->set(lista: $request->dado());
        $solicitacaoCreditoEntity->salvar();

        return mensagemSucesso(
            pegarPropriedadeDaEntity($solicitacaoCreditoEntity, lista: [
                'valor_emprestimo', 'prazo', 'valor_parcela_atual', 'quantidade_parcelas_restantes',
                'taxa', 'nome', 'documento_cpf', 'email', 'telefone_celular', 'telefone_fixo', 'orgao',
                'observacao', 'data_simulacao', 'data_autorizacao', 'status', 'tipo'
            ]),
            201,
            Helper::CRIPTOGRAFAR
        );
    }
}
