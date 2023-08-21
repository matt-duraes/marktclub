<?php

namespace App\Classes\Geral;

use Modules\Data;
use Status\Status as StatusStatus;

final class Publicado extends StatusStatus
{
    public const SIM = 'sim';
    public const NAO = 'nao';

    public function __construct(
        protected Data $inicio,
        protected Data $final,
        protected bool $ativo
    ) {
        $this->valor = $inicio->date() <= hoje() && $final->date() >= hoje() && $ativo ? 'sim' : 'nao';
        parent::__construct([
            self::SIM   => 'Sim',
            self::NAO   => 'Não'
        ], [
            self::SIM   => 'verde',
            self::NAO   => 'vermelho'
        ]);
    }
}
