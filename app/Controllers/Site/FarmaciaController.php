<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Loja\BuscarModel;
use App\Classes\ParceiroLoja\TipoProcedimento;

final class FarmaciaController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        if (!sessaoExiste('popupAlerta')) {
            sessao('popupAlerta', true);
        }
        return view('loja.index', [
            'menu'   => 'farmacia',
            'tipo'   => 'farmacia',
            'banner' => (new BannerModel())->farmacia(),
        ]);
    }

    /**
     * Acessa a página de detalhes de cada farmácia
     *
     * @param string $url
     */
    public function detalhe(string $url)
    {
        $Dado = new BuscarModel(url: $url);
        $dado = $Dado->buscarDados();

        return view('loja.detalhe', [
            'menu'         => 'farmacia',
            'dado'         => $dado,
            'tipo'         => 'farmacia',
            'procedimento' => new TipoProcedimento()
        ]);
    }

    public function carteirinha()
    {
        return view(
            'farmacia.carteirinha',
            [
                'nome' => sessao('USUARIO.nome'),
                'cpf'  => sessao('USUARIO.cpf'),
            ]
        );
    }
}
