<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\SocialHelper;
use Controller\Controller;
use App\Models\Api\Loja\LojaMapaModel;
use App\Models\Site\Perfil\{DadosModel,  SenhaModel, CarteirinhaModel};

final class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index', [
            'dado' => (object)[
                'nome' => 'Nome do usuário',
                'data_nascimento' => '',
                'genero' => '',
                'estado_civil' => '',
                'email_pessoal' => '',
                'email_trabalho' => '',
                'telefone_trabalho' => '',
                'telefone_pessoal' => '',
                'endereco_cep' => '',
                'endereco_bairro' => '',
                'endereco_logradouro' => '',
                'endereco_numero' => '',
                'endereco_complemento' => '',
                'endereco_cidade' => '',
                'endereco_estado' => '',
                'endereco_estado' => ''
            ]
        ]);
    }

    public function senha()
    {
        return view('perfil.senha');
    }

    public function dependente()
    {
        $dado[] = (object)[
            'nome' => 'Nome do dependente',
            'email'   => '',
            'cpf'     => '',
            'usuario' =>  '',
            'id' => ''
        ];

        return view('perfil.dependente', [
            'dado' => $dado
        ]);
    }

    public function carteira(): Response
    {

        $dado = (new CarteirinhaModel())->getDado();

        return view('perfil.carteira', [
            'dado' => $dado,
            'logo'=> defined('CLUBE_LOGO')
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

        if ($request->acao == 'imagem') {
            return (new DadosModel())->postImagemSocial($request);
        }
    }
}
