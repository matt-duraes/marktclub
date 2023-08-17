<?php

namespace App\Models\Api\SolicitacaoCredito;

trait ValidarTrait
{
    private function validarOperadora()
    {
        if (!$this->operadora->valido()) {
            mensagemErro('Campo inválido!', 'A operadora enviada não é válida.');
        }
        return $this;
    }

    private function validarTipo()
    {
        if (!$this->tipo->valido()) {
            mensagemErro('Campo inválido!', 'O tipo de crédito enviado não é válido.');
        }
        return $this;
    }

    private function validarValor()
    {
        if (!$this->valor_total->valido()) {
            mensagemErro('Campo inválido!', 'O valor para o crédito não é válido.');
        }
        return $this;
    }

    private function validarParcela()
    {
        if (!$this->parcela->valido()) {
            mensagemErro('Campo inválido!', 'A quantidade de parcelas para o crédito não é válida.');
        }
        $parcelaMaxima = ParcelaModel::TIPO_PRAZO_MAXIMO[$this->tipo->indice()];
        if ($this->parcela->numero() > $parcelaMaxima) {
            mensagemErro(
                'Campo inválido!',
                'A quantidade de parcelas está maior que o máximo permitido para o tipo de crédito.'
            );
        }
        return $this;
    }
}
