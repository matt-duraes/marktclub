<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\BannerModel;
use App\Classes\SolicitacaoCredito\Tipo;
use App\Classes\SolicitacaoCredito\Operadora;

final class SicoobController extends Controller
{
    public function index()
    {
        return view('sicoob.index', [
            'menu'   => 'sicoob',
            'banner' => (new BannerModel())->sicoob()
        ]);
    }

    public function simulacao(string $tipo)
    {
        $parcela = ((new ClubeApiHelper()))
            ->json([
                'operadora' => Operadora::SICOOB,
                'tipo'      => $tipo,
                'titulo'    => 'Escolha uma parcela'
            ])
            ->get('/solicitacao-credito/parcela')
            ->array()['dado'] ?? [];

        return view('sicoob.simulacao', [
            'menu'    => 'sicoob',
            'tipo'    => $tipo,
            'banner'  => (new BannerModel())->sicoob(),
            'parcela' => $parcela
        ]);
    }

    public function consignado()
    {
        return $this->simulacao(Tipo::CONSIGNADO);
    }

    public function creditoPessoal()
    {
        return $this->simulacao(Tipo::CREDITO_PESSOAL);
    }

    public function veiculoZero()
    {
        return $this->simulacao(Tipo::VEICULO_NOVO);
    }

    public function veiculoSeminovo()
    {
        return $this->simulacao(Tipo::VEICULO_SEMINOVO);
    }

    public function regulamento(string $tipo)
    {
        return view('sicoob.regulamento', [
            'tipo' => $tipo
        ]);
    }
}
