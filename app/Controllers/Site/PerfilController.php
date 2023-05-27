<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\SocialHelper;
use Controller\Controller;
use App\Models\Api\Loja\LojaMapaModel;
use App\Models\Site\Perfil\{DadosModel,  SenhaModel};

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

    public function carteira()
    {
        //Irei alterar a verificação usando CLUBE_ID dps
        define('CLUBE_ID', '80b010d457c4329f4aadacd5b57766c8');

        if (CLUBE_ID == 'cemecard') {
            $dado = (object) [
                'nome' => 'Usuario Teste',
                'status' => 'ativo',
                'numero_cartao' => '123456',
                'dt_validade' => '01/09/2023',
                'logo' => 'https://arquivo.marktclub.com.br/construtor/31d15fa1d817f8a83ca627c73c548852.png' ,
                'frente_carteirinha' => 'https://cemecard.temmaisvantagens.com.br/images/carteirinha_cemecard/frente.png',
                'fundo' => 'proprio'
            ];
        }
        if (CLUBE_ID == 'sindfazenda') {
            $dado = (object)[
                'nome' => 'Usuario Teste',
                'documento'     => '012.345.678.90',
                'estado' => 'DF',
                'frente_carteirinha' => 'https://arquivo.marktclub.com.br/construtor/card_bg_asagu.png',
                'logo' => 'https://arquivo.marktclub.com.br/construtor/31d15fa1d817f8a83ca627c73c548852.png',
                'fundo' => 'padrao'
            ];
        }
        if (CLUBE_ID == 'sinpoldf') {
            $dado = (object) [
                'nome' => 'Usuario Teste',
                'dt_filiacao' => '01/01/2023',
                'matricula_pcdf' => '123456',
                'rg'    => '123321',
                'cpf'     => '012.345.678.90',
                'dt_nascimento' => '01/09/2000',
                'dt_emissao' => '22/05/2023',
                'frente_carteirinha' => 'https://arquivo.marktclub.com.br/construtor/frente_sinpoldf.png',
                'verso_carteirinha' => 'https://arquivo.marktclub.com.br/construtor/fundo_sinpoldf.png',
                'fundo' => 'proprio'

            ];
        }

        if (!in_array(CLUBE_ID, ["sindfazenda", "sinpoldf", "cemecard"])) {
            $dado = (object) [
                'nome' => 'Usuario Teste',
                'frente_carteirinha' => 'https://arquivo.marktclub.com.br/construtor/card_bg_asagu.png',
                'logo' => 'https://arquivo.marktclub.com.br/construtor/logo_marktclub_tem_mais.png',
                'fundo' => 'padrao'
            ];
        }

        return view('perfil.carteira', [
            'dado' => $dado
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
