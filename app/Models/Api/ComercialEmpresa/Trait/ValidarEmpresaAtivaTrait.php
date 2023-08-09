<?php

namespace App\Models\Api\ComercialEmpresa\Trait;

use Modules\Botao;
use App\Classes\ComercialEmpresa\TipoPagamento;
use App\Classes\ComercialEmpresa\FinalidadePrincipal;

trait ValidarEmpresaAtivaTrait
{
    private function validarEmpresaAtiva()
    {
        $this->setarValidacaoPadraoORM();
    }

    private function setarValidacaoPadraoORM()
    {
        $this->ormValidarSalvar = '
            titulo|Título|vazio
            finalidade_principal|Finalidade principal|vazio|valido
            finalidade_secundaria|Finalidade principal|vazio|valido
            cadastro_usuario|Como será o cadastro|vazio|valido
            id_usuario_equipe|Responsável pelo contrato|vazio|int>0
            renda_media|Renda média|vazio|valido
            tipo_pagamento|Tipo de pagamento|vazio|valido
            contrato_data|Data do contrato|vazio|valido
            contrato_prazo|Prazo do contrato|vazio|valido
            contrato_dia_fechamento|Dia de fechamento|vazio|valido
            contrato_dia_pagamento|Dia de pagamento|vazio|valido
            contrato_renovacao|Tipo de renovação do contrato|vazio|valido
            razao_social|Razão Social|vazio
            cnpj|CNPJ|vazio|valido
            responsavel_nome|Nome do responsável|vazio|valido
            responsavel_telefone|Telefone do responsável|vazio|valido
            responsavel_email|E-mail do responsável|vazio|valido
            estado_principal|Estado principal|valido
            status|Status|vazio|valido
        ';

        if (
            $this->finalidade_principal->indice() == FinalidadePrincipal::PUBLICA &&
            (!$this->propriedadeExiste('data_eleicao') || !$this->data_eleicao->valido())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo Data da eleição é obrigatório.');
        }

        if (
            $this->propriedadeExiste('produto_clube') &&
            $this->produto_clube->valor() == Botao::SIM &&
            (!$this->propriedadeExiste('tipo_site') || !$this->tipo_site->valido())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo Tipo de site do clube é obrigatório.');
        }

        $pagamentoMisto = $this->propriedadeExiste('tipo_pagamento')
            && $this->tipo_pagamento->indice() == TipoPagamento::MISTO;
        if (
            $pagamentoMisto &&
            (!$this->propriedadeExiste('contrato_valor_minimo') || !$this->contrato_valor_minimo->valido())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo Valor mínimo do contrato é obrigatório.');
        }
        if (
            $pagamentoMisto &&
            (!$this->propriedadeExiste('contrato_usuario_minimo') || !$this->contrato_usuario_minimo->valido())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo Número mínimo de usuário é obrigatório.');
        }

        $this->validarComunicacao('email');
        $this->validarComunicacao('whatsapp');
        $this->validarComunicacao('rede_social');
    }

    private function validarComunicacao($tipo)
    {
        $botao = 'comunicacao_' . $tipo;
        $dia = $tipo . '_dia';
        $campo = [
            'email'       => 'Qual dia será enviado o e-mail',
            'whatsapp'    => 'Qual dia será enviado o whatsapp',
            'rede_social' => 'Qual dia será enviado as peças para rede social',
        ][$tipo];

        if (
            !$this->propriedadeExiste($botao) || !$this->propriedadeExiste($dia) ||
            ($this->$botao->bool() && count($this->$dia) == 0)
        ) {
            mensagemErro('Campo obrigatório!', 'O campo ' . $campo . ' é obrigatório.');
        }

        if (
            $tipo == 'email' &&
            $this->comunicacao_email->bool() &&
            (!$this->propriedadeExiste('email_disparo') || !$this->email_disparo->valido())
        ) {
            mensagemErro('Campo obrigatório!', 'O campo quem dispara o e-mail é obrigatório.');
        }
    }
}
