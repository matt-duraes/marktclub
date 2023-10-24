<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;

final class TempController extends Controller
{
    public function undefined(Request $request)
    {
        return new Response(url: LINK);
    }
}
