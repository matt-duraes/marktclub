<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;

final class TempController extends Controller
{
    public function undefined()
    {
        return new Response(url: LINK);
    }

    public function teste()
    {
        return view('teste');
    }
}
