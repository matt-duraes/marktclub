<?php

namespace System\Interface;

use Http\Request;
use Http\Response;

interface ControllerAtualizarInterface
{
    public function putAtualizar(Request $request, string $id): Response;
}
