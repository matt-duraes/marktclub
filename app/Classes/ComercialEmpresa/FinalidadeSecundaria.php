<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

class FinalidadeSecundaria extends StatusStatus
{
    public const ASSOCIACAO = 'associacao';
    public const SINDICATO = 'sindicato';
    public const EMBAIXADA = 'embaixada';
    public const CONSELHO = 'conselho';
    public const FACULDADE = 'faculdade';
    public const BANCO = 'banco';
    public const COOPERATIVA = 'cooperativa';
    public const ASSOCIACAO_PRIVADA = 'associacao_privada';
    public const OUTRO = 'outro';

    public function __construct(
        protected string|int|null $valor = null,
        array $lista = [],
        array $cor = null,
        array $numero = null
    ) {
        if (empty($lista)) {
            $lista = [
                self::ASSOCIACAO         => 'Associação',
                self::SINDICATO          => 'Sindicato',
                self::EMBAIXADA          => 'Embaixada',
                self::CONSELHO           => 'Conselho de classe',
                self::FACULDADE          => 'Faculdade',
                self::BANCO              => 'Banco',
                self::COOPERATIVA        => 'Cooperativa',
                self::ASSOCIACAO_PRIVADA => 'Associação Privada',
                self::OUTRO              => 'Outro'
            ];
        }
        parent::__construct(lista: $lista, cor: $cor, numero: $numero);
    }
}
