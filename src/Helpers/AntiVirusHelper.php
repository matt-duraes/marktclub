<?php

namespace Helpers;

use phpMussel\Core\Loader;
use phpMussel\Core\Scanner;

final class AntiVirusHelper
{
    private $Scanner;

    public function __construct(
        private string $arquivo
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
        $resultado = $this->Scanner->scan($this->arquivo, 2);
        return false === $resultado ? true : false;
    }
}
