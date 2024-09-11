<?php

namespace App\Classes\Geral;

use Status\Status as StatusStatus;

final class Metodo extends StatusStatus
{
    public const GET = 'GET';
    public const POST = 'POST';
    public const PUT = 'PUT';
    public const DELETE = 'DELETE';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::GET     => 'GET',
            self::POST    => 'POST',
            self::PUT     => 'PUT',
            self::DELETE  => 'DELETE'
        ]);
    }
}
