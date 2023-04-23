<?php

namespace App\Models\Site\Perfil;

use stdClass;
use App\Classes\UsuarioCliente\Helper;
use Http\Request;
use Http\Response;
use Helpers\ListaHelper;
use Helpers\ApiHelper;
use Helpers\CryptHelper;

final class DependenteModel
{
    protected string $chave;

    public function __construct()
    {

        $Curl = new ApiHelper('admin:chave_publica');
        $chave = $Curl->get('/admin/chave-publica')->object()->dado->chave ?? '';

        $this->chave = $chave;
    }


    public function getDado()
    {
        $id = '5595203c-f7b1-4211-9981-bf09eb236b35';
        $Api = new ApiHelper('usuario_dependente:listar');

        $dado = $Api->validar('Página não encontrada!', status: 404)
            ->json([
                'usuario' => $id
            ])
            ->get('/usuario-dependente')
            ->object();

        $retorno  = $dado->dado ?? [];

        return $retorno;
    }


    public function postDado(Request $request)
    {
        $Crypt = new CryptHelper(chavePublica: $this->chave);
        $Api = new ApiHelper('usuario_dependente:salvar');

        $id = '5595203c-f7b1-4211-9981-bf09eb236b35';

        $salvar = $Api->body([
            'nome' => $Crypt->encode($request->nome),
            'email' => $Crypt->encode($request->email),
            'cpf' => $Crypt->encode($request->cpf),
            'usuario' => $id
        ])->post('/usuario-dependente')->object();

        respostaJson(
            resposta: $salvar,
            mensagem: 'Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.'
        );
        return $this->montarRetornoPostDado($salvar);

    }


    private function montarRetornoPostDado($dado)
    {

        $Curl = new ApiHelper('admin:chave_privada');
        $chave = $Curl->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $Crypt = new CryptHelper(chavePrivada: $chave);

        $retorno = [];
        if($dado->dado) {
            $r =  $dado->dado;
            $retorno = (object)[
                'id' => $r->id,
                'nome' => $Crypt->decode($r->nome),
            ];
        }

        return mensagemSucesso([
            'id' => $retorno->id,
            'nome' => $retorno->nome
        ], status: 201);
    }


    public function postDeleta(Request $request)
    {
        $Crypt = new CryptHelper(chavePublica: $this->chave);
        $Api = new ApiHelper('usuario_dependente:deletar');

        $id = $request->id;

        $salvar = $Api->delete('/usuario-dependente/'.$id);

        respostaJson(
            resposta: $salvar,
            mensagem: 'Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.'
        );

        return new Response(status: 204);

    }

}
