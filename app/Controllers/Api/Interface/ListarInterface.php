<?php

namespace App\Controllers\Api\Interface;

use Http\Request;

interface ListarInterface
{
    public function getListar(Request $request);
}
