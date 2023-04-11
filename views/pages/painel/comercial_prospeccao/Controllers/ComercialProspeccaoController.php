<?php

namespace Painel\ComercialProspeccao\Controllers;

use Controller\Controller;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use Painel\ComercialProspeccao\Models\ProspeccaoModel;

final class ComercialProspeccaoController extends Controller
{
    public function lista()
    {
        $Prospeccao = new ProspeccaoModel();

        return view('painel.comercial_prospeccao.index', [
            'app' => 'demanda',
            'abordagem' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::ABORDAGEM),
            'apresentacao' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::APRESENTACAO),
            'negociacao' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::NEGOCIACAO),
            'avaliacao' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::AVALIACAO),
            'minuta' => $Prospeccao->buscarProspeccao(ProspeccaoStatus::MINUTA)
        ]);
    }
}
