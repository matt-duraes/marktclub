<?php

namespace System\Interface;

use Http\Request;

interface ControllerAtualizarInterface
{
    public function putAtualizar(Request $request, string $id);
}
