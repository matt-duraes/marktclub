<?php

namespace Order;

use stdClass;

interface OrderInterface
{
    public function ordem(): string;
    public function listaParaPainel(): stdClass;
}
