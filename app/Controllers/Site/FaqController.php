<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;

final class FaqController extends Controller
{
    public function cfm(): Response
    {
        return view('faq.cfm', [
            'menu' => 'faq'
        ]);
    }

    public function favorito(): Response
    {
        return view('faq.favorito');
    }
}
