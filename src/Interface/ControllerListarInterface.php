<?php

namespace System\Interface;

use Http\Request;
use Http\Response;

interface ControllerListarInterface
{
    public function getListar(Request $request): Response;
}
