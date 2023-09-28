<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Site\Perfil\DadosModel;
use App\Models\Site\Perfil\SenhaModel;
use App\Models\Site\Perfil\DependenteModel;
use App\Models\Site\Perfil\CarteirinhaModel;

final class PerfilController extends Controller
{
    public function index()
    {
        $dado = (new DadosModel())->getDado();
        return view('perfil.index', [
            'dado' => $dado,
            'menu' => 'alterar_dados'
        ]);
    }

    public function senha()
    {
        return view(
            'perfil.senha',
            [
                'menu' => 'alterar_senha',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DEPENDENTE
    |--------------------------------------------------------------------------
    */
    public function dependente()
    {
        $dado = (new DependenteModel())->listarDependente();
        return view('perfil.dependente', [
            'menu' => 'adicionar_dependentes',
            'dado' => $dado
        ]);
    }

    public function postSalvaDependente(Request $request)
    {
        return mensagemSucesso((new DependenteModel())->salvarDependente($request), 201);
    }

    public function postDeletaDependente(Request $request)
    {
        (new DependenteModel())->deletarDependente($request);
        return new Response(status: 204);
    }

    public function carteira(): Response
    {
        $dado = (new CarteirinhaModel())->getDado();
        return view('perfil.carteira', [
            'dado' => $dado,
            'logo' => defined('CLUBE_LOGO_PRINCIPAL')
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR DADOS
    |--------------------------------------------------------------------------
    */
    public function postSalvaDados(Request $request)
    {
        $Salvar = (new DadosModel())->postDado($request);

        return new Response($Salvar);
    }

    /*
    |--------------------------------------------------------------------------
    | ALTERAR SENHA
    |--------------------------------------------------------------------------
    */
    public function postAlteraSenha(Request $request)
    {
        new SenhaModel($request);
        return new Response(status: 204);
    }

    /*
    |--------------------------------------------------------------------------
    | VINCULANDO CONTA SOCIAL
    |--------------------------------------------------------------------------
    */
    public function postSocial(Request $request)
    {
        return (new DadosModel())->postImagemSocial($request);
    }
}
