<?php

namespace App\Classes\DemandaTarefa;

use Status\Status;

final class Tipo extends Status
{
    public const BACKEND = 'back-end';
    public const FRONTEND = 'front-end';
    public const CRIACAO = 'criacao';
    public const APP = 'app';
    public const BANCO = 'banco';
    public const INFRA = 'infra';
    public const NAO_DEFINIDO = 'nao-definido';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::BACKEND      => 'Back-end',
            self::FRONTEND     => 'Front-End',
            self::CRIACAO      => 'Criação',
            self::APP          => 'APP',
            self::BANCO        => 'Banco de dados',
            self::INFRA        => 'Infraestrutura',
            self::NAO_DEFINIDO => 'Não definido'
        ]);
    }
}
