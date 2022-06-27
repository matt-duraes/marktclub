<?php

namespace App\Controllers\Site;

use Modules\Cpf;
use Http\Request;
use Modules\Cnpj;
use Http\Response;
use Modules\Email;
use Modules\Telefone;
use Helpers\ListaHelper;
use Controller\Controller;
use App\Models\Site\ContratarEntity;

final class SiteController extends Controller
{
    public function index()
    {
        return view('site.index');
    }

    public function fazemos()
    {
        return view('site.fazemos', [
            'menu' => 'fazemos'
        ]);
    }

    public function quemSomos()
    {
        return view('site.quem_somos', [
            'menu' => 'quemSomos'
        ]);
    }

    public function dicas()
    {
        return view('site.dicas', [
            'menu' => 'dicas'
        ]);
    }

    public function sistema()
    {
        return view('site.sistema', [
            'menu' => 'sistema'
        ]);
    }

    public function contratar()
    {
        return view('site.contratar', [
            'listaEstado' => (new ListaHelper)->add('', 'Escolha um estado')->estado()->r()
        ]);
    }
    public function postContratar(Request $request)
    {
        $Contratar = new ContratarEntity(
            nome: $request->nome,
            cpf: new Cpf($request->cpf),
            email: new Email($request->email),
            telefone: new Telefone($request->telefone),
            razao_social: $request->razao_social,
            nome_fantasia: $request->nome_fantasia,
            cnpj: new Cnpj($request->cnpj),
            endereco_cep: (int)$request->endereco_cep,
            endereco_logradouro: $request->endereco_logradouro,
            endereco_numero: (int)$request->endereco_numero,
            endereco_complemento: $request->endereco_complemento,
            endereco_referencia: $request->endereco_referencia,
            endereco_bairro: $request->endereco_bairro,
            endereco_cidade: $request->endereco_cidade,
            endereco_estado: $request->endereco_estado
        );
        $Contratar->salvar();

        return new Response(status: 201);
    }
}
