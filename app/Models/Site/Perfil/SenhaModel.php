<?php

namespace App\Models\Site\Perfil;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use App\Helpers\ClubeApiHelper;

final class SenhaModel extends ClubeApiHelper
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDado(Request $request): Response
    {
        $this
            ->validar('Ocorre um erro ao atualizar sua demanda, por favor, tente novamente.')
            ->body([
                'senha_atual'   => $this->Crypt->encode($request->senha_atual),
                'senha_nova'    => $this->Crypt->encode($request->senha_nova),
                'senha_repetir' => $this->Crypt->encode($request->genero),
            ])
            ->put('/usuario-cliente/' . $this->idUsuario);

        return new Response(status: 204);
    }
}
