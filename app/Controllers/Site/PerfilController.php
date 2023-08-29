<?php

namespace App\Controllers\Site;

use Http\Request;
use Http\Response;
use Controller\Controller;
use Helpers\LocalizacaoHelper;
use App\Models\Site\Perfil\DadosModel;
use App\Models\Site\Perfil\SenhaModel;
use App\Models\Site\Perfil\DependenteModel;
use App\Models\Site\Perfil\CarteirinhaModel;

final class PerfilController extends Controller
{
    public function index()
    {
        $Perfil = (new DadosModel())->getDado();
        return view('perfil.index', [
            'dado' => (object)[
                'nome'                 => $Perfil->nome,
                'data_nascimento'      => $Perfil->data_nascimento,
                'genero'               => $Perfil->genero,
                'estado_civil'         => $Perfil->estado_civil,
                'email_pessoal'        => $Perfil->email_pessoal,
                'email_trabalho'       => $Perfil->email_trabalho,
                'telefone_trabalho'    => $Perfil->telefone_trabalho,
                'telefone_pessoal'     => $Perfil->telefone_pessoal,
                'endereco_cep'         => $Perfil->endereco_cep,
                'endereco_bairro'      => $Perfil->endereco_bairro,
                'endereco_logradouro'  => $Perfil->endereco_logradouro,
                'endereco_numero'      => $Perfil->endereco_numero,
                'endereco_complemento' => $Perfil->endereco_complemento,
                'endereco_cidade'      => $Perfil->endereco_cidade,
                'endereco_estado'      => $Perfil->endereco_estado,
                'imagem'               => $Perfil->imagem,
            ],
            'menu' => 'alterar_dados'
        ]);
    }

    public function postBuscarCep(Request $request): Response
    {
        $cep = (new LocalizacaoHelper())->pegarEnderecoPeloCep($request->cep);
        return mensagemSucesso($cep);
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
            'menu'         => 'adicionar_dependentes',
            'dado'         => $dado
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
            'logo' => defined('CLUBE_LOGO')
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
