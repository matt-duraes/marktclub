<?php

namespace App\Controllers\Api\Interface;

use Http\Request;

interface SalvarInterface
{
    public function postSalvar(Request $request);
}
