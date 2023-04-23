<?php

namespace App\Controllers\Site;

use Controller\Controller;
use Helpers\ApiHelper;
use Http\Request;
use Http\Response;

final class SalaVipController extends Controller
{
    public function index()
    {
        return view('salavip.index', []);
    }
}
