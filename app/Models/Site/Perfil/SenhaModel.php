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

        $this->validarSenhaAtual($request->senha_atual);
        $this->salvarNovaSenha($request->senha_nova);
    }

    private function validarSenhaAtual(string $senhaAtual): void
    {
        $dado = $this
            ->validar('Ocorreu um erro ao validar a senha.')
            ->body(['senha'   => $this->Crypt->encode($senhaAtual)])
            ->post('/usuario-cliente/validar-senha')
            ->array()['dado'] ?? [];

        if ($dado['senha'] == 'nao') {
            mensagemErro('Campo inválido!', 'A senha atual informada é inválida.');
        }
    }

    private function salvarNovaSenha(string $senha): void
    {
        $this
            ->validar('Ocorreu um erro ao alterar a senha.')
            ->body(['senha'   => $this->Crypt->encode($senha)])
            ->put('/usuario-cliente/' . $this->idUsuario);
    }
}
