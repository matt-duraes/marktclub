<?php

namespace App\Models\Api\LoginPainel;

use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class LoginGoogleModel
{

    private EquipeEntity $Usuario;

    public function __construct(
        private string $id
    ) {
        $this->validarDadosDeLogin();
        $this->buscarUsuarioPeloGoogle();
    }

    public function pegarUsuario(): EquipeEntity
    {
        return $this->Usuario;
    }

    private function validarDadosDeLogin(): void
    {
        if (empty($this->id) || empty($this->token)) {
            mensagemErro('Erro!', 'Não foi possível validar seus dados do Google.');
        }
    }

    private function buscarUsuarioPeloGoogle(): void
    {
        $Usuario = new EquipeEntity();
        try {
            $Usuario->buscar(where: ['id_google', $this->id]);
        } catch (\Throwable) {
            mensagemErro(
                titulo: 'Dados inválidos',
                mensagem: 'Não foi encontrado nenhum usuário pelo seu ID do.',
                status: 401
            );
        }
        $this->Usuario = $Usuario;
    }
}
