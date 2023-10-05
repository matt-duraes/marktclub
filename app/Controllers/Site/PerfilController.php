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

    /*
    |--------------------------------------------------------------------------
    | PONTO MAIS AÇÃO CVS
    |--------------------------------------------------------------------------
    */

    public function pontoCvs()
    {
        $cvs = (object)(new DadosModel())->cvs();
        $erro = !empty($cvs->erro) ? $cvs->texto : '';
        $resultado = isset($cvs->dado) ? $cvs->dado  : [];

        return view('perfil.ponto_cvs', [
            'r' => $resultado,
            'erro' => $erro
        ]);
    }

    public function popupSolicitaPontoCvs()
    {
        return view('perfil.solicita_cvs');
    }

    public function postCvsSolicitar(Request $request)
    {
        if (!in_array($_SESSION['CLUBE']->id, ['5cc28dab736cb7d4ef436ee2447a07ee', '80b010d457c4329f4aadacd5b57766c8'])) {
            return mensagemErro('Erro','Não foi possível acessar' ,status: 404);
        }
        return (new DadosModel())->solicitarPontoCvs($request);
    }

    public function buscarMais()
    {

    }
}
