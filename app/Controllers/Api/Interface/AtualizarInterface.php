<?php

namespace App\Controllers\Api\Interface;

use Http\Request;

interface AtualizarInterface
{
    public function putAtualizar(Request $request, string $id);
}
