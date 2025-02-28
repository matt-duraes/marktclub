<?php

namespace Painel\Ranking\Controllers;

use Controller\Controller;
use Erro\Excecao;
use Helpers\ApiHelper;
use Http\Response;

class RankingController extends Controller
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
    public function ranking(): Response
    {
        $ranking = $this->Api->get('/comercial-empresa/ranking')->array();
        return view('painel.ranking.index', [
            'appTitulo' => 'Ranking de Indicação',
            'app'       => 'ranking',
            'ranking'   => $this->montarColocacao($ranking['dado'] ?? [])
        ]);
    }

    private function montarColocacao(array $ranking): array
    {
        if (empty($ranking)) {
            return $ranking;
        }

        foreach ($ranking as $item) {
            if ($item['me'] == false) {
                continue;
            }
            array_unshift($ranking, $item);
        }
        return $ranking;
    }
}
