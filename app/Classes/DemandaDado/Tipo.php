<?php

namespace App\Classes\DemandaDado;

use Status\Status;

final class Tipo extends Status
{
    public const NOVO_CLIENTE = 'novo-cliente';
    public const NOVO_ASSOCIACAO = 'novo-associacao';
    public const OUTRO = 'outro';
    public const BUG_CLUBE = 'bug-clube';
    public const BUG_ASSOCIACAO = 'bug-associacao';
    public const BUG_PAINEL = 'bug-painel';
    public const BUG_APP = 'bug-app';
    public const BUG_OUTRO = 'bug-outro';
    public const FEATURE = 'feature';
    public const CRIACAO = 'criacao';
    public const SORTEIO = 'sorteio';
    public const EVENTO = 'evento';
    public const BRINDE = 'brinde';
    public const CAMPANHA = 'campanha';
    public const INDICACAO = 'indicacao';
    public const AUTOINDICACAO = 'autoindicacao';
    public const COTACAO_AUTOMOVEL = 'cotacao_automovel';
    public const COTACAO_PRODUTO = 'cotacao_produto';
    public const AUDITORIA = 'auditoria';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct(
            array_merge($this->selectTecnologia(), $this->selectCriacao(), $this->selectConvenio())
        );
    }

    public function selectTecnologia($titulo = '')
    {
        $select = [
            self::NOVO_CLIENTE    => 'Novo Cliente',
            self::NOVO_ASSOCIACAO => 'Site associação',
            self::BUG_CLUBE       => 'Bug no clube',
            self::BUG_ASSOCIACAO  => 'Bug na associação',
            self::BUG_PAINEL      => 'Bug no painel',
            self::BUG_APP         => 'Bug no APP',
            self::BUG_OUTRO       => 'Bug outros',
            self::FEATURE         => 'Feature',
            self::OUTRO           => 'Outros',
        ];

        if (!empty($titulo)) {
            return ['' => $titulo] + $select;
        }

        return $select;
    }

    public function selectCriacao($titulo = '')
    {
        $select = [
            self::CRIACAO => 'Criação',
            self::SORTEIO => 'Sorteio',
        ];

        if (!empty($titulo)) {
            return ['' => $titulo] + $select;
        }

        return $select;
    }

    public function selectConvenio($titulo = '')
    {
        $select = [
            self::EVENTO            => 'Evento',
            self::BRINDE            => 'Brinde',
            self::CAMPANHA          => 'Campanha',
            self::INDICACAO         => 'Indicação',
            self::AUTOINDICACAO     => 'Autoindicação',
            self::COTACAO_AUTOMOVEL => 'Cotação - Automóvel',
            self::COTACAO_PRODUTO   => 'Cotação - Produto',
            self::AUDITORIA         => 'Auditoria',
        ];

        if (!empty($titulo)) {
            return ['' => $titulo] + $select;
        }

        return $select;
    }
}
