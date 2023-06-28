<?php

namespace App\Classes\SolicitacaoDeclaracao;

use Status\Status as StatusStatus;

class Status extends StatusStatus
{
    public const NOVA = 'nova';
    public const ENVIADO_EMPRESA = 'enviado_empresa';
    public const ENVIADO_USUARIO = 'enviado_usuario';
    public const PROBLEMA = 'problema';
    public const FINALIZADO = 'finalizado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVA            => 'Nova',
            self::ENVIADO_EMPRESA => 'Enviado p/ Empresa',
            self::ENVIADO_USUARIO => 'Enviado p/ Usuário',
            self::PROBLEMA        => 'Problema',
            self::FINALIZADO      => 'Finalizado'
        ]);
    }
}
