<?php

namespace App\Models\Api\LoginPainel;

use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class LoginFacebookModel
{

    private EquipeEntity $Usuario;

    public function __construct(private string $id)
    {
        if (empty($id)) {
            mensagemErro('Campo obrigatório!', 'Você deve enviar o id do usuário para fazer login.');
        }
        $this->buscarUsuarioPeloFacebook();
    }

    private function buscarUsuarioPeloFacebook()
    {
        $Equipe = new EquipeEntity(validarToken: false);
        try {
            $Equipe->buscar([
                ['id_facebook', $this->id],
                ['status', 1]
            ]);
        } catch (\Throwable $e) {
            mensagemErro(
                titulo: 'Conta inválida!',
                mensagem: 'Não existe usuário vinculado a sua conta do Facebook.',
                status: 400
            );
        }

        $this->Usuario = $Equipe;
    }

    public function pegarUsuario(): EquipeEntity
    {
        return $this->Usuario;
    }
}
