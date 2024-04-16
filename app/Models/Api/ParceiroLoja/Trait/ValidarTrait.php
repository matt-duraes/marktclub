<?php

namespace App\Models\Api\ParceiroLoja\Trait;

use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\TipoProcedimento;

trait ValidarTrait
{
    private function validarSalvar()
    {
        $this->validarPadrao();
        if ($this->tipo_loja->indice() == TipoLoja::CASHBACK) {
            $this->validarCashback();
        } else {
            $this->validarLoja();
        }
        if ($this->status->indice() == Status::CONCLUIDO) {
            $this->validarConcluido();
        }
    }

    private function validarPadrao()
    {
        $this->ormValidarSalvar = '
            titulo_interno|Título para o painel|obrigatorio|vazio
            tipo_loja|Tipo de loja|obrigatorio|vazio|valido
            url|URL|obrigatorio|vazio
            id_usuario_equipe|Equipe|obrigatorio|vazio
            empresa|Empresa|obrigatorio|vazio
            categoria_principal|Categoria Principal|obrigatorio|vazio|valido
        ';
    }

    private function validarConcluido()
    {
        $this->ormValidarSalvar .= '
            titulo|Título para o clube|obrigatorio|vazio
            imagem_logo|Imagem do logo|obrigatorio|vazio|valido
            texto_descricao|Texto da descrição|obrigatorio|vazio
            texto_desconto|Texto do desconto|obrigatorio|vazio
            desconto|Desconto curto|obrigatorio|vazio
        ';
        if ($this->tipo_loja->indice() != $this->tipo_loja::CASHBACK) {
            $this->ormValidarSalvar .= '
                texto_procedimento|Texto do procedimento|obrigatorio|vazio
            ';
        }
    }

    private function validarLoja()
    {
        $this->ormValidarSalvar .= '
            tipo_estabelecimento|Tipo de estabelecimento|obrigatorio|vazio|valido
        ';
        if ($this->status->indice() == Status::CONCLUIDO) {
            $this->validarDataVencimentoContrato();
            $this->validarTipoProcedimento();
        }
    }

    private function validarCashback()
    {
        if (empty($this->comissao_minima)) {
            mensagemErro('Campo obrigatorio!', 'A comissão é obrigatória.');
        } elseif (is_float($this->comissao_minima)) {
            mensagemErro('Campo inválido!', 'A comissão deve ser a porcentagem do cashback no formato: 1.00');
        } elseif (empty($this->comissao_maxima)) {
            mensagemErro('Campo obrigatorio!', 'A comissão máxima é obrigatório.');
        } elseif (is_float($this->comissao_maxima)) {
            mensagemErro('Campo inválido!', 'A comissão máxima deve ser a porcentagem do cashback no formato: 1.00');
        }
        $this->ormValidarSalvar .= '
            link_site|link do site|obrigatorio|vazio
        ';
    }

    private function validarDataVencimentoContrato()
    {
        $dataContrato = $this->data_contrato_inicio;
        $dataVencimento = $this->data_contrato_vencimento;
        if ($dataContrato->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo data do contrato é obrigatório.');
        } elseif (!$dataContrato->valido()) {
            mensagemErro('Campo inválido!', 'O campo data do contrato está inválida.');
        } elseif ($dataVencimento->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo data de vencimento do contrato é obrigatório.');
        } elseif (!$dataVencimento->valido()) {
            mensagemErro('Campo inválido!', 'O campo data de vencimento do contrato está inválida.');
        } elseif ($dataVencimento->date() <= $dataContrato->date()) {
            mensagemErro('Campo inválido!', 'A data de vencimento do contrato deve ser maior que a data do contrato.');
        } elseif ($dataVencimento->date() <= hoje()) {
            mensagemErro('Campo inválido!', 'O contrato desse parceiro venceu, renove o contrato para continuar.');
        }
    }

    private function validarTipoProcedimento()
    {
        $procedimento = $this->tipo_procedimento;
        $site = $procedimento->indice() == TipoProcedimento::WEBSITE;
        if ($procedimento->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo tipo de procedimento é obrigatório.');
        } elseif (!$procedimento->valido()) {
            mensagemErro('Campo inválido!', 'O campo tipo de procedimento é inválido.');
        } elseif ($site && empty($this->link_site)) {
            mensagemErro('Campo obrigatório!', 'O campo link do site é obrigatório.');
        } elseif ($site && !validarUrl($this->link_site)) {
            mensagemErro('Campo inválido!', 'O campo link do site está inválido.');
        }
    }
}
