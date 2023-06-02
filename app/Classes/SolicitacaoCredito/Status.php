<?php

namespace App\Classes\SolicitacaoCredito;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const CRIADA = 'criada';
    public const ENVIADA_ALFA = 'enviada_alfa';
    public const ENVIADA_SICOOB = 'enviada_sicoob';
    public const ERRO_ENVIAR = 'erro_enviar';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CRIADA         => 'Criada',
            self::ENVIADA_ALFA   => 'Enviada p/ Alfa',
            self::ENVIADA_SICOOB => 'Enviada p/ Sicoob',
            self::ERRO_ENVIAR    => 'Não Enviado'
        ]);
    }
}
