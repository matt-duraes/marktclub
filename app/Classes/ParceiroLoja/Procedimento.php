<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class Procedimento extends Status
{
    public const VOUCHER = 'voucher';
    public const WEBSITE = 'website';
    public const DECLARACAO = 'declaracao';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::VOUCHER      => 'Voucher',
            self::WEBSITE      => 'Website',
            self::DECLARACAO   => 'Declaração',
        ]);
    }
}
