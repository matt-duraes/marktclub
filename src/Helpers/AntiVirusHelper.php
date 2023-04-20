<?php

namespace Helpers;

use phpMussel\Core\Loader;
use phpMussel\Core\Scanner;

final class AntiVirusHelper
{
    private Scanner $Scanner;

    public function __construct(
        private readonly string $arquivo
    ) {
        $Loader = new Loader(
            ROOT . '/phpmussel.yml',
            ROOT . '/files/phpmussel/cache',
            ROOT . '/files/phpmussel/quarentena',
            ROOT . '/files/phpmussel/assinatura'
        );
        $this->Scanner = new Scanner($Loader);
    }

    public function validar(): bool
    {
        set_time_limit(50);
        return $this->Scanner->scan($this->arquivo, 2) === false;
    }
}
