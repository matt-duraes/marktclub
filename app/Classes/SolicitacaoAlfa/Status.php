<?php

namespace App\Classes\SolicitacaoAlfa;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const CRIADA = 'criada';
    public const ENVIADA_ALFA = 'enviada_alfa';
    public const ERRO_ENVIAR = 'erro_enviar';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CRIADA       => 'Criada',
            self::ENVIADA_ALFA => 'Enviada p/ Alfa',
            self::ERRO_ENVIAR  => 'Não Enviado'
        ]);
    }
}
