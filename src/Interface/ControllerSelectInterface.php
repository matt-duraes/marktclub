<?php

namespace System\Interface;

use Http\Request;
use Http\Response;

interface ControllerSelectInterface
{
    public function getSelect(Request $request): Response;
}
