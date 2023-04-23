<?php

namespace App\Models\Site\Perfil;

use stdClass;
use App\Classes\UsuarioCliente\Helper;
use Http\Request;
use Http\Response;
use Helpers\ListaHelper;
use Helpers\ApiHelper;
use Helpers\CryptHelper;

final class SenhaModel
{
    protected string $chave;

    public function __construct()
    {

        $Curl = new ApiHelper('admin:chave_publica');
        $chave = $Curl->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $this->chave = $chave;
    }

    public function postDado(Request $request): Response
    {
        $Crypt = new CryptHelper(chavePublica: $this->chave);
        $Api = new ApiHelper('usuario_cliente:atualizar');

        $id = '5595203c-f7b1-4211-9981-bf09eb236b35';

        $salvar = $Api->body([
            'senha_atual' => $Crypt->encode($request->senha_atual),
            'senha_nova' => $Crypt->encode($request->senha_nova),
            'senha_repetir' => $Crypt->encode($request->genero),
        ])->put('/usuario-cliente/'.$id);


        respostaJson(
            resposta: $salvar,
            mensagem: 'Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.'
        );

        return new Response(status: 204);
    }
}
