<?php

namespace Painel\Demanda\Controllers;

use Helpers\ApiHelper;
use Controller\Controller;

final class SprintController extends Controller
{
    private ApiHelper $Api;

    public function __construct()
    {
        parent::__construct();
        $this->Api = new ApiHelper(token: true);
    }

    public function postSprintAtiva()
    {
        $dado = $this->Api
            ->validar(status: 404)
            ->get('/demanda-sprint/ativa')
            ->object();

        if (!chaveExiste('dado.id', $dado)) {
            return mensagemStatus(404);
        }
        return mensagemSucesso($dado->dado);
    }
}
