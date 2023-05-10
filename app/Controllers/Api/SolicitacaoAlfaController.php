<?php

namespace App\Controllers\Api;

use App\Classes\SolicitacaoAlfa\Helper;
use App\Helpers\Alfa\AlfaCredito;
use App\Models\Api\SolicitacaoAlfa\SolicitacaoEntity;
use App\Models\Api\SolicitacaoAlfa\SolicitacaoModel;
use Erro\Erro;
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
     * @throws Erro
     */
    public function postSolicitacao(Request $request): Response
    {
        $solicitacaoCreditoEntity = new SolicitacaoEntity();

        $codigo = $this->gerarCodigoDaSolicitacao();
        if ((new SolicitacaoModel())->verificarExisteCodigo($codigo)) {
            $codigo = $this->gerarCodigoDaSolicitacao();
        }

        $dados = $request->dado();
        $dados['codigo_solicitacao'] = $codigo;

        $solicitacaoCreditoEntity->set(lista: $dados);
        $solicitacaoCreditoEntity->salvar();

        if (!(new AlfaCredito($solicitacaoCreditoEntity))->enviarSolicitacao()) {
            mensagemStatus(404, localhost: 'Erro no envio dos dados para o Alfa');
        }

        return mensagemSucesso(
            pegarPropriedadeDaEntity($solicitacaoCreditoEntity, lista: [
                'codigo_solicitacao', 'valor_emprestimo', 'prazo', 'valor_parcela_atual',
                'quantidade_parcelas_restantes', 'taxa', 'nome', 'documento_cpf', 'email',
                'telefone_celular', 'telefone_fixo', 'orgao', 'observacao', 'status', 'tipo'
            ]),
            201,
            Helper::CRIPTOGRAFAR
        );
    }

    /**
     * @return string Código aleátorio com base na data e hora atual
     */
    private function gerarCodigoDaSolicitacao(bool $retry = false): string
    {
        return $retry ? $this->gerarCodigoDaSolicitacao() : uniqid(date('YmdHi') . '-');
    }
}
