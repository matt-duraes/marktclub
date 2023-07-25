<?php

namespace App\Models\Api\LoginPainel;

use stdClass;
use App\Classes\LoginPainel\PegarEquipeTrait;

final class LoginGoogleModel
{
    use PegarEquipeTrait;

    private stdClass $Usuario;

    public function __construct(
        private string $id
    ) {
        if (empty($id)) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar o id do usuário para fazer login.');
        }
        $this->buscarUsuarioPeloGoogle();
    }

    public function pegarUsuario(): stdClass
    {
        return $this->Usuario;
    }

    private function buscarUsuarioPeloGoogle()
    {
        $Usuario = $this->pegarEquipe([
            ['id_google', $this->id],
            ['status', 1]
        ]);
        if (vazio($Usuario)) {
            mensagemErro(
                titulo: 'Conta inválida!',
                mensagem: 'Não existe usuário vinculado a sua conta do Google.',
                status: 400
            );
        }
        $this->Usuario = $Usuario;
    }
}
