<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class CancelarMotivo extends Status
{
    public const SEM_CONTATO = 'sem-contato';
    public const SEM_INTERESSE = 'sem-interesse';
    public const EMPRESA_FECHOU = 'empresa-fechou';
    public const RECISAO_CONTRATUAL = 'recisao-contratual';
    public const CONTATO_IGNORADO = 'contato-ignorado';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::SEM_CONTATO        => 'Sem Contato',
            self::SEM_INTERESSE      => 'Sem Interesse',
            self::EMPRESA_FECHOU     => 'Empresa Fechou',
            self::RECISAO_CONTRATUAL => 'Recisão Contratual',
            self::CONTATO_IGNORADO   => 'Contato Ignorado',
        ]);
    }
}
