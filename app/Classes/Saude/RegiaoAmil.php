<?php

namespace App\Classes\Saude;

use Status\Status;
use App\Classes\Saude\Interface\RegiaoInterface;

class RegiaoAmil extends Status implements RegiaoInterface
{
    public const BRASILIA = 'brasilia';
    public const RIO_DE_JANEIRO = 'rio_de_janeiro';
    public const SAO_PAULO = 'sao_paulo';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::BRASILIA       => 'Brasília',
            self::SAO_PAULO      => 'São Paulo',
            self::RIO_DE_JANEIRO => 'Rio de Janeiro'
        ]);
    }
}
