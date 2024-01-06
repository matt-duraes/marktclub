<?php

namespace App\Classes\SiteMenu;

use Status\Status as StatusStatus;

final class Tipo extends StatusStatus
{
    public const MENU = 'menu';
    public const SUB_MENU = 'sub_menu';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::MENU      => 'Menu',
            self::SUB_MENU  => 'Submenu'
        ], [
            self::MENU      => 'verde',
            self::SUB_MENU  => 'azul'
        ]);
    }
}
