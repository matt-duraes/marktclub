<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class EtapaNegociacao extends StatusStatus
{
    public const ATENDIMENTO = 'atendimento';
    public const GESTAO = 'gestao';
    public const DIRETORIA = 'diretoria';
    public const CONSELHO = 'conselho';
    public const PRESIDENCIA = 'presidencia';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ATENDIMENTO => 'Atendimento',
            self::GESTAO      => 'Gestão',
            self::DIRETORIA   => 'Diretoria',
            self::CONSELHO    => 'Conselho',
            self::PRESIDENCIA => 'Presidência'
        ]);
    }
}
