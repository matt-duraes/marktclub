<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class ExemploController extends Controller
{
    public function index()
    {
        return view('!exemplo');
    }
}
