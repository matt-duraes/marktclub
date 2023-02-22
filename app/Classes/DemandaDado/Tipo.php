<?php

namespace App\Classes\DemandaDado;

use Status\Status;

final class Tipo extends Status
{
    const NOVO_CLIENTE = 'novo-cliente';
    const NOVO_ASSOCIACAO = 'novo-associacao';
    const OUTRO = 'outro';
    const BUG_CLUBE = 'bug-clube';
    const BUG_ASSOCIACAO = 'bug-associacao';
    const BUG_PAINEL = 'bug-painel';
    const BUG_APP = 'bug-app';
    const BUG_OUTRO = 'bug-outro';
    const FEATURE = 'feature';

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
                self::FEATURE => 'Feature'
            ]
        );
    }
}
