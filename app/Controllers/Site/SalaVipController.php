<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class SalaVipController extends Controller
{
    public function index()
    {
        return view('salavip.index', []);
    }
}
