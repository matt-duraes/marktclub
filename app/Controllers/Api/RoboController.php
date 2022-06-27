<?php

namespace App\Controllers\Api;

use Http\Response;
use Controller\Controller;
use App\Models\Api\Emenda\RoboModel as RoboEmenda;

final class RoboController extends Controller
{
    public function getEmenda()
    {
        (new RoboEmenda)->buscar('Porto Nacional');
        return new Response('');
    }
    public function teste()
    {
        return view(arquivo: 'teste');
    }
}
