<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class Auditoria extends Status
{
    public const NAO_CONHECIDO = 'nao-conhecido';
    public const SITE_PROBLEMA = 'site-problema';
    public const DESCONTO_ERRADO = 'desconto-errado';
    public const DADO_INCORRETO = 'dado-incorreto';
    public const OUTRO = 'outro';
    public const SEM_PROBLEMA = 'sem-problema';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NAO_CONHECIDO   => 'Não conhecido',
            self::SITE_PROBLEMA   => 'Site com problema',
            self::DESCONTO_ERRADO => 'Desconto errado',
            self::DADO_INCORRETO  => 'Dado incorreto',
            self::OUTRO           => 'Outro',
            self::SEM_PROBLEMA    => 'Sem problema',
        ]);
    }
}
