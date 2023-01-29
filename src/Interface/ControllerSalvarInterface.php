<?php

namespace System\Interface;

use Http\Request;

interface ControllerSalvarInterface
{
    public function postSalvar(Request $request);
}
