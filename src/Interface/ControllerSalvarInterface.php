<?php

namespace System\Interface;

use Http\Request;
use Http\Response;

interface ControllerSalvarInterface
{
    public function postSalvar(Request $request): Response;
}
