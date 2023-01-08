<?php

namespace App\Models\Api\UsuarioEquipe;

final class PerfilModel
{
    private array $usuarioVazio;
    public function __construct()
    {
        $this->usuarioVazio = [
            'id' => '',
            'perfil' => '',
            'nome' => 'Sem usuário',
            'imagem' => imagemUsuario()
        ];
    }

    public function pegarDado(string $id, bool $vazio = true)
    {
        try {
            $Equipe = new EquipeEntity();
            $Equipe->id($id);
            return [
                'id' => $Equipe->id,
                'perfil' => $Equipe->perfil,
                'nome' => $Equipe->nome,
                'imagem' => $Equipe->imagem
            ];
        } catch (\Throwable) {
            return $this->usuarioVazio;
        }
    }

    /**
     * Pega os dados da equipe em massa
     *
     * @param   array   $id     Lista de ID da equipe para buscar
     * @param   bool    $vazio  Retorna os usuários não encontrados como vazio
     * @return  array           Retorna a lista de dado
     */
    public function pegarLista(array $id, bool $vazio = true)
    {
        $retorno = [];
        $usuarioExistente = [];
        foreach ($id as $usuario) {
            $dado = $this->pegarDado($usuario, $vazio);
            if (empty($dado) || in_array($dado['id'], $usuarioExistente)) {
                continue;
            }
            $usuarioExistente[] = $dado['id'];
            $retorno[] = $dado;
        }
        return $retorno;
    }
}
