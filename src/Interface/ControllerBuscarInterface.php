<?php

namespace System\Interface;

use Http\Response;

interface ControllerBuscarInterface
{
    public function getBuscar(string $id): Response;
}
