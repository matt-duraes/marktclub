<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use Modules\Data;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Ordem;
use App\Classes\SolicitacaoVoucher\Status;
use App\Classes\SolicitacaoVoucher\TipoUsuario;

trait ValidarRequestTrait
{
    private function validarRequest()
    {
        $tipo = new Tipo($this->request->tipo);
        if (!$tipo->vazio() && !$tipo->valido()) {
            mensagemErro('Campo inválido!', 'O tipo de voucher informado não é válido.');
        }
        $tipoUsuario = new TipoUsuario($this->request->tipo_usuario);
        if (!$tipoUsuario->vazio() && !$tipoUsuario->valido()) {
            mensagemErro('Campo inválido!', 'O tipo de usuário informado não é válido.');
        }
        $dataCriacaoDe = new Data($this->request->data_criacao_de);
        if (!$dataCriacaoDe->vazio() && (!$dataCriacaoDe->valido() || !$dataCriacaoDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação de início não está no formato válido.');
        }
        $dataCriacaoAte = new Data($this->request->data_criacao_ate);
        if (!$dataCriacaoAte->vazio() && (!$dataCriacaoAte->valido() || !$dataCriacaoAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação final não está no formato válido.');
        }
        $dataValidacaoDe = new Data($this->request->data_validacao_de);
        if (!$dataValidacaoDe->vazio() && (!$dataValidacaoDe->valido() || !$dataValidacaoDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de validação de início não está no formato válido.');
        }
        $dataValidacaoAte = new Data($this->request->data_validacao_ate);
        if (!$dataValidacaoAte->vazio() && (!$dataValidacaoAte->valido() || !$dataValidacaoAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de validação final não está no formato válido.');
        }
        $Status = new Status($this->request->status);
        if (!$Status->vazio() && !$Status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
        $Ordem = new Ordem($this->request->ordem);
        if (!$Ordem->vazio() && !$Ordem->valido()) {
            mensagemErro('Campo inválido!', 'A ordem informada não é válida.');
        }
    }
}
