<?php

namespace Painel\Dashboard\Controllers;

use Controller\Controller;
use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Response;

class DashboardController extends Controller
{
    private ApiHelper $Api;

    public function __construct()
    {
        parent::__construct();
        $this->Api = new ApiHelper(token: true);
    }

    /**
     * @throws Excecao
     */
    public function dashboard(): Response
    {
        $ranking = $this->Api->get('/comercial-empresa/ranking')->array();
        return view('painel.dashboard.index', [
            'appTitulo' => 'Dashboard',
            'app'       => 'dashboard',
            'ranking'   => $ranking['dado'] ?? []
        ]);
    }
}
