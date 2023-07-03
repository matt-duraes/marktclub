<?php

namespace Order;

use stdClass;

interface OrderInterface
{
    public function valor(string $valor): self;

    public function vazio(): bool;

    public function valido(): bool;

    public function ordem(): string;

    public function listaParaPainel(): stdClass;
}
