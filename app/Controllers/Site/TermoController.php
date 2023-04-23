<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class TermoController extends Controller
{
    public function termosite()
    {
        return view('termo.site');
    }

    public function termocashback()
    {
        return view('termo.cashback');
    }


}
