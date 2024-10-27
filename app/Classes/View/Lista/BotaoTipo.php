<?php

namespace App\Classes\View\Lista;

use Status\Status as StatusStatus;

final class BotaoTipo extends StatusStatus
{
    public const VOLTAR = 'voltar';
    public const NORMAL = 'normal';
    public const CINZA = 'cinza';
    public const BORDA_NORMAL = 'borda-normal';
    public const BORDA_CINZA = 'borda-cinza';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::VOLTAR => 'Voltar',
            self::NORMAL => 'Normal',
            self::CINZA => 'Cinza',
            self::BORDA_NORMAL => 'Borda normal',
            self::BORDA_CINZA => 'Borda cinza',
        ]);
    }
}
