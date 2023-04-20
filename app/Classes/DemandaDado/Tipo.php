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

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::NOVO_CLIENTE => 'Novo Cliente',
                self::NOVO_ASSOCIACAO => 'Site associação',
                self::OUTRO => 'Outro',
                self::BUG_CLUBE => 'Bug no clube',
                self::BUG_ASSOCIACAO => 'Bug na associação',
                self::BUG_PAINEL => 'Bug no painel',
                self::BUG_APP => 'Bug no APP',
                self::BUG_OUTRO => 'Bug outros',
                self::FEATURE => 'Feature',
                self::CRIACAO => 'Criação',
            ]
        );
    }
}
