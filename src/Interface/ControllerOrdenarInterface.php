<?php

namespace System\Interface;

use Http\Request;
use Http\Response;

interface ControllerOrdenarInterface
{
    public function putOrdenar(Request $request): Response;
}
