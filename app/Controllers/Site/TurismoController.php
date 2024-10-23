<?php

namespace App\Controllers\Site;

use Erro\Excecao;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Hash\HashModel;
use App\Models\Site\Loja\FiltroModel;
use App\Models\Site\Loja\ListarModel;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Models\Site\Pagina\BuscarModel;
use App\Models\Site\Turismo\PromocaoModel;

final class TurismoController extends Controller
{
    /**
     * @return Response
     * @throws Excecao
     */
    public function index(): Response
    {
        $Pagina = new BuscarModel('teste');
        // ppe($Pagina->html);
        return view('pagina', [
            'menu'    => 'turismo',
            'html'    => $Pagina->html,
            'url'     => 'turismo'
        ]);
    }

    public function redirecionar()
    {
        $Hash = new HashModel('loja');
        return new Response(url: env('LINK_INTEGRACAO', '') . '/loja/64616b205573748269a95cd3c9dc8553/' . $Hash->hash);
    }

    public function redirecionarCampanha(string $id)
    {
        $Hash = new HashModel('campanha');
        return new Response(url: env('LINK_INTEGRACAO', '') . '/campanha/' . $id . '/' . $Hash->hash);
    }

    public function postHotel()
    {
        $Listar = new ListarModel(
            quantidade: 3,
            tipo: new TipoLoja(TipoLoja::LOJA),
            Filtro: new FiltroModel([
                'categoria'    => 'outros',
                'subcategoria' => '2a13da6ab3ac2b233bb0dc7f57e3ac49'
            ])
        );

        return mensagemSucesso($Listar->listarDados());
    }

    public function postPromocao()
    {
        $Listar = new PromocaoModel(parceiro: '64616b205573748269a95cd3c9dc8553');
        return mensagemSucesso($Listar->retorno);
    }
}
