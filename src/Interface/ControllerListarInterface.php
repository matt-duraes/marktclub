<?php

namespace System\Interface;

use Http\Request;

interface ControllerListarInterface
{
    public function getListar(Request $request);
}
