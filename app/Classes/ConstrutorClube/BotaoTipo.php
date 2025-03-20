<?php

namespace App\Classes\ConstrutorClube;

use Status\Status as StatusStatus;

final class BotaoTipo extends StatusStatus
{
    public const BOTAO = 'botao';
    public const LINK = 'link';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::BOTAO => 'Botão',
            self::LINK  => 'Link'
        ]);
    }
}
