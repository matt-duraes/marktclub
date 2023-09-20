<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class SamsungController extends Controller
{
    public function index()
    {
        return view('samsung', [
            'menu' => 'samsung'
        ]);
    }
}
