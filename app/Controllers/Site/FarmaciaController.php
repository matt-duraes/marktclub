<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\BannerModel;
use App\Models\Site\Loja\BuscarModel;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Loja\ListarModel;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\TipoProcedimento;

final class FarmaciaController extends Controller
{
    public function index()
    {
        $Farmacia = new ListarModel(
            tipo: new TipoLoja(TipoLoja::FARMACIA)
        );

        if (!sessaoExiste('popupAlerta')) {
            sessao('popupAlerta', true);
        }

        return view('farmacia.index', [
            'menu'        => 'farmacia',
            'banner'      => (new BannerModel())->farmacia(),
            'lista'       => $Farmacia->listarDados(),
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
            tipo: new TipoLoja(TipoLoja::FARMACIA),
            Filtro: new FiltroModel(['quantidade' => 3])
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
                'procedimento' => new TipoProcedimento()
            ]
        );
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
