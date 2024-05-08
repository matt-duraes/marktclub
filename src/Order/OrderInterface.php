<?php

namespace Order;

use stdClass;

interface OrderInterface
{
    public function valor(?string $valor = null): self|string|null;

    public function indice(): string|null;

    public function vazio(): bool;

    public function valido(): bool;

    public function ordem(): string;

    public function listaParaPainel(): stdClass;
}
