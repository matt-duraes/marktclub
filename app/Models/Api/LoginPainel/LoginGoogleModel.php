<?php

namespace App\Models\Api\LoginPainel;

use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class LoginGoogleModel
{
    private EquipeEntity $Usuario;

    public function __construct(
        private string $id
    ) {
        if (empty($id)) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar o id do usuário para fazer login.');
        }
        $this->buscarUsuarioPeloGoogle();
    }

    public function pegarUsuario(): EquipeEntity
    {
        return $this->Usuario;
    }

    private function buscarUsuarioPeloGoogle()
    {
        $Equipe = new EquipeEntity(validarToken: false);
        try {
            $Equipe->buscar([
                ['id_google', $this->id],
                ['status', 1]
            ]);
        } catch (\Throwable) {
            mensagemErro(
                titulo: 'Conta inválida!',
                mensagem: 'Não existe usuário vinculado a sua conta do Google.',
                status: 400
            );
        }
        $this->Usuario = $Equipe;
    }
}
