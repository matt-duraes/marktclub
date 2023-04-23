<?php

namespace App\Controllers\Site;

use Controller\Controller;
use Helpers\ApiHelper;
use Helpers\SocialHelper;
use Http\Request;
use Http\Response;
use App\Models\Site\Perfil\{DadosModel, DependenteModel, SenhaModel};
use App\Models\Api\Loja\LojaMapaModel;

final class PerfilController extends Controller
{
    public function index()
    {

        $Perfil = new DadosModel();
        $perfil = $Perfil->getDado();

        return view('perfil.index', [
            'tituloPagina' => 'Atualize seus dados',
            'dado' => $perfil
        ]);
    }

    public function senha()
    {
        return view('perfil.senha', [
            'tituloPagina' => 'Atualize sua senha'
        ]);
    }

    public function dependente()
    {
        $Perfil = new DependenteModel();
        $perfil = $Perfil->getDado();

        return view('perfil.dependente', [
            'tituloPagina' => 'Adicione dependentes',
            'dado' => $perfil
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
    | SALVAR DEPENDENTE
    |--------------------------------------------------------------------------
    */
    public function postSalvaDependente(Request $request)
    {

        return (new DependenteModel())->postDado($request);

    }

    /*
    |--------------------------------------------------------------------------
    | DELETAR DEPENDENTE
    |--------------------------------------------------------------------------
    */
    public function postDeletaDependente(Request $request)
    {

        return (new DependenteModel())->postDeleta($request);

    }

    /*
    |--------------------------------------------------------------------------
    | ALTERAR SENHA
    |--------------------------------------------------------------------------
    */
    public function postAlteraSenha(Request $request)
    {

        return (new SenhaModel())->postDado($request);

    }

    /*
    |--------------------------------------------------------------------------
    | VINCULANDO CONTA SOCIAL
    |--------------------------------------------------------------------------
    */
    public function postSocial(Request $request)
    {

        $Salvar = (new DadosModel())->postImagemSocial($request);

        return mensagemSucesso([], status: 201);
    }

}
