<?php

namespace App\Models\Api\PontoCvs;

use App\Classes\PontoCvs\Status;
use App\Helpers\PontoCvsHelper;
use App\Models\Api\GeralModel;
use stdClass;

final class AtualizarStatusModel extends GeralModel
{
    protected string $_tabela = TABELA_PONTO_CVS;

    public function AtualizarStatus()
    {
        $pontosPendentes = $this->campo(['pedido_codigo'])->where([['status', 1], ['pedido_codigo', "!null"]])->read();
        $codigoPonto = array_column($pontosPendentes, 'pedido_codigo');

        $PontoCvsHelper = new PontoCvsHelper;
        foreach ($codigoPonto as $codigo) {
            $solicitacao = $PontoCvsHelper->buscarSolicitacao($codigo);
            $dado = $this->montarDadoSolicitacao($solicitacao);

            if (empty($dado)) {
                continue;
            }

            $atualizar = $this->dado($dado)->where(['pedido_codigo', $codigo])->update();
            if (existeErro($atualizar, 'id')) {
                mensagemErro('Erro!', 'Não foi possível atualizar o status de uma solicitação.');
            }
        }
    }

    private function montarDadoSolicitacao(stdClass $solicitacao)
    {
        switch ($solicitacao->status) {
            case 1:
                return [
                    'voucher' => $solicitacao->pedido_desc ?? '',
                    'data_voucher' => $solicitacao->pedido_vigencia ?? '',
                    'status' => (new Status('aprovado'))->numero()
                ];
            case 3:
                return [
                    'voucher' => '',
                    'data_voucher' => '',
                    'status' => (new Status('recusado'))->numero()
                ];
        }
        return;
    }
}
