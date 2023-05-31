<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class RegulamentoController extends Controller
{
    public function sorteio()
    {
        return view('regulamento.sorteio', [
            'dado' => true
        ]);
    }
}
