<?php

namespace App\Controllers\Oauth;

use Modules\Cpf;
use Http\Request;
use Modules\Data;
use Http\Response;
use Controller\Controller;
use App\Helpers\Cfm\UsuarioHelper;
use App\Helpers\Cfm\ConselhoHelper;
use App\Models\Oauth\Usuario\SalvarModel;

final class CfmController extends Controller
{
    public function login(): Response
    {
        return view('cfm.login', [
            'conselho' => (new ConselhoHelper())->lista('Escolha um conselho')
        ]);
    }

    public function postLogin(Request $request)
    {
        $Cfm = new UsuarioHelper(
            tipo: UsuarioHelper::TIPO_FUNCIONARIO,
            cpf: new Cpf($request->cpf),
            inscricao: $request->inscricao,
            estado: $request->estado,
            dataNascimento: new Data($request->data_nascimento),
            nomeMae: $request->nome_mae
        );

        $Login = new SalvarModel(
            empresa: 1981,
            nome: $Cfm->nome,
            cpf: $Cfm->cpf->numero(),
            email_pessoal: $Cfm->email_pessoal,
            email_trabalho: $Cfm->email_trabalho,
            dataNascimento: $Cfm->dataNascimento->date(),
            crmNumero: $Cfm->inscricao,
            crmEstado: $Cfm->estado,
            cadastro: $request->cadastro
        );

        if ($Login->cadastro == 'sim') {
            return mensagemSucesso([
                'cadastro' => 'sim'
            ]);
        }
        return new Response(url: $Login->pegarLink());
    }
}
