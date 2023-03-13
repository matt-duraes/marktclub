<?php

namespace App\Models\Api\SolicitacaoSalavip\Trait;

use Modules\Data;
use App\Classes\SolicitacaoSalavip\Ordem;
use App\Classes\SolicitacaoSalavip\Empresa;

trait ValidarRequestTrait
{
    private function validarRequest()
    {
        $Empresa = new Empresa($this->request->empresa);
        if (!$Empresa->vazio() && !$Empresa->valido()) {
            mensagemErro('Campo inválido!', 'A empresa informada não é válida.');
        }
        $DataDe = new Data($this->request->data_de);
        if (!$DataDe->vazio() && (!$DataDe->valido() || !$DataDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de início da busca não é válida.');
        }
        $DataAte = new Data($this->request->data_ate);
        if (!$DataAte->vazio() && (!$DataAte->valido() || !$DataAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de final da busca não é válida.');
        }
        $Ordem = new Ordem($this->request->ordem);
        if (!$Ordem->vazio() && !$Ordem->valido()) {
            mensagemErro('Campo inválido!', 'A ordem informada não é válida.');
        }
    }
}
