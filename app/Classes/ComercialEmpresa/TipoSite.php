<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class TipoSite extends StatusStatus
{
    public const PROPRIO = 'proprio';
    public const TEMVANTAGENS = 'temvantagens';
    public const TEMMAISVANTAGENS = 'temmaisvantagens';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::PROPRIO => 'Site próprio',
            self::TEMVANTAGENS => 'temvantagens.com.br',
            self::TEMMAISVANTAGENS => 'temMAISvantagens.com.br',
        ]);
    }
}
