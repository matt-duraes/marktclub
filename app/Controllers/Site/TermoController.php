<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class TermoController extends Controller
{
    public function termosite()
    {
        return view('termo.site', [
            'clube' => '1'
        ]);
    }

    public function termocashback()
    {
        return view('termo.cashback', [
            'clube' => '1'
        ]);
    }

    public function app()
    {
        return view('termo.app');
    }
}
