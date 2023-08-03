<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;

final class FaqController extends Controller
{
    public function index(): Response
    {
        return view('faq.index', [
            'menu' => 'faq'
        ]);
    }
}
