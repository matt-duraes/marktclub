<?php

namespace App\Controllers\Oauth;

use Http\Response;
use Controller\Controller;
use App\Helpers\Cfm\ConselhoHelper;

final class CfmController extends Controller
{
    public function paginaLogin(): Response
    {
        return view('cfm.login', [
            'conselho' => (new ConselhoHelper())->lista('Escolha um conselho')
        ]);
    }
}
