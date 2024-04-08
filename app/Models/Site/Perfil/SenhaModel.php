<?php

namespace App\Models\Site\Perfil;

use Http\Request;
use App\Helpers\ClubeApiHelper;

final class SenhaModel extends ClubeApiHelper
{
    public function __construct(Request $request)
    {
        parent::__construct();
        $request
            ->vazio('senha_atual', mensagem: 'O campo senha atual é obrigatório.')
            ->vazio('senha_nova', mensagem: 'O campo nova senha é obrigatório.')
            ->vazio('senha_repetir', mensagem: 'O campo repetir senha é obrigatório.');

        if ($request->senha_nova != $request->senha_repetir) {
            mensagemErro('Campo inválido!', 'O campo nova senha e repetir senha devem ser iguais.');
        }

        $this
            ->validar('Ocorreu um erro ao validar a senha.')
            ->body([
                'senha_atual' => $this->Crypt->encode($request->senha_atual),
                'senha_nova'  => $this->Crypt->encode($request->senha_nova),
            ])
            ->put('/usuario-cliente/alterar-senha');
    }
}
