<?php

namespace App\Controllers\Site;

use Controller\Controller;
use Helpers\ApiHelper;
use Helpers\SocialHelper;
use Http\Request;
use Http\Response;
use App\Models\Site\Loja\ListarModel;

final class PreferenciaController extends Controller
{
    public function index()
    {
        return view('preferencia.index', [
            'parceiro' => [1,2,3],
            'url' => 'preferencia',
            'lista'        => (new ListarModel())->listarDados(),
        ]);
    }

    public function boasVindas()
    {

        return view('preferencia.boas_vindas', [
            'lista'        => (new ListarModel())->listarDados(),
            'parceiro' => [1,2,3]
        ]);
    }

}
