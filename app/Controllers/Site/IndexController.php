<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class IndexController extends Controller
{
    public function index()
    {
        return view('index', [
            'menu' => 'home'
        ]);
    }
}
