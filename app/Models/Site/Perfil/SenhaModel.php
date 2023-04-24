<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Http\Request;
use Http\Response;

final class SenhaModel
{
    protected string $chave;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $Curl = new ApiHelper('admin:chave_publica');
        $chave = $Curl->get('/admin/chave-publica')
            ->object()->dado->chave ?? '';
        $this->chave = $chave;
    }

    /**
     * @param  Request  $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDado(Request $request): Response
    {
        $Crypt = new CryptHelper(chavePublica: $this->chave);
        $Api = new ApiHelper('usuario_cliente:atualizar');

        $salvar = $Api->body([
            'senha_atual'   => $Crypt->encode($request->senha_atual),
            'senha_nova'    => $Crypt->encode($request->senha_nova),
            'senha_repetir' => $Crypt->encode($request->genero),
        ])->put('/usuario-cliente/5595203c-f7b1-4211-9981-bf09eb236b35');

        respostaJson(
            $salvar,
            'Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.'
        );

        return new Response(status: 204);
    }
}
