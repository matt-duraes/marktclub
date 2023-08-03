<?php

namespace App\Controllers\Site;

use Modules\Inteiro;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Classes\ParceiroLoja\Tipo;
use App\Models\Site\Loja\BuscarModel;
use App\Models\Site\Loja\ListarModel;
use App\Classes\ParceiroLoja\Procedimento;

final class FarmaciaController extends Controller
{
    public function index()
    {
        $Farmacia = new ListarModel(
            tipo: new Tipo(Tipo::FARMACIA)
        );

        return view('farmacia.index', [
            'menu'   => 'farmacia',
            'banner' => (new BannerModel())->farmacia(),
            'lista'  => $Farmacia->listarDados()
        ]);
    }

    /**
     * Acessa a página de detalhes de cada farmácia
     *
     * @param string $url
     */
    public function detalhe(string $url)
    {
        $Listar = new ListarModel(
            quantidade: new Inteiro(3),
            tipo: new Tipo(Tipo::FARMACIA)
        );

        return view(
            'farmacia.detalhe',
            [
                'menu'         => 'farmacia',
                'dado'         => (new BuscarModel($url))->buscarDados(),
                'tipo'         => 'farmacia',
                'lista'        => $Listar->listarDados(),
                'telefone'     => [],
                'email'        => [],
                'endereco'     => [],
                'procedimento' => new Procedimento()
            ]
        );
    }

    public function carteirinha()
    {
        //     $Api = new ApiHelper('carteirinha:buscar');
        //     $carteira = $Api->get('/carteirinha/5595203c-f7b1-4211-9981-bf09eb236b35')->object();
        $carteira = (object) [
            'cpf'  => '07385666167',
            'nome' => 'Mateus'
        ];

        return view(
            'farmacia.carteirinha',
            [
                'carteira' => $carteira
            ]
        );
    }
}
