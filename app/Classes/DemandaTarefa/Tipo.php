<?php

namespace App\Classes\DemandaTarefa;

use Status\Status;

final class Tipo extends Status
{
    const BACKEND = 'back-end';
    const FRONTEND = 'front-end';
    const CRIACAO = 'criacao';
    const APP = 'app';
    const BANCO = 'banco';
    const INFRA = 'infra';
    const NAO_DEFINIDO = 'nao-definido';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::BACKEND => 'Back-end',
                self::FRONTEND => 'Front-End',
                self::CRIACAO => 'Criação',
                self::APP => 'APP',
                self::BANCO => 'Banco de dados',
                self::INFRA => 'Infraestrutura',
                self::NAO_DEFINIDO => 'Não definido'
            ]
        );
    }
}
