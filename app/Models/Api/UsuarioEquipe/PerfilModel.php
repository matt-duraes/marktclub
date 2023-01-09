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

    /**
     * Pegar um perfil da equipe
     *
     * @param   int|string  $id     ID ou UUID do usuário
     * @param   bool        $vazio  Se vai retornar um usuário vazio
     * @return  array               Array vazio ou com um usuário
     */
    public function pegarDado(int|string $id, bool $vazio = true): array
    {
        try {
            $Equipe = new EquipeEntity();
            if (is_int($id)) {
                $Equipe->_id($id);
            } else if (in_array(strlen($id), [32, 36])) {
                $Equipe->id($id);
            } else {
                return $vazio ? $this->usuarioVazio : [];
            }
            return [
                'id' => $Equipe->id,
                'perfil' => $Equipe->perfil,
                'nome' => $Equipe->nome->nome(),
                'imagem' => $Equipe->imagem
            ];
        } catch (\Throwable) {
            return $vazio ? $this->usuarioVazio : [];
        }
    }

    /**
     * Pega os dados da equipe em massa
     *
     * @param   array   $id     Lista de ID da equipe para buscar
     * @param   bool    $vazio  Retorna os usuários não encontrados como vazio
     * @return  array           Retorna a lista de usuario
     */
    public function pegarLista(array $id, bool $vazio = true)
    {
        $retorno = [];
        $usuarioExistente = [];
        foreach ($id as $usuario) {
            $dado = $this->pegarDado($usuario);
            if (empty($dado) || in_array($dado['id'], $usuarioExistente)) {
                continue;
            }
            $usuarioExistente[] = $dado['id'];
            $retorno[] = $dado;
        }
        return $retorno;
    }

    /**
     * Monta um perfil do usuário
     *
     * @param   string      $id                 UUID do usuário
     * @param   string      $perfil             Perfil do usuário
     * @param   string      $nome               Nome do usuário
     * @param   int         $imagem_tipo        Tipo da imagem do usuário
     * @param   string      $imagem_facebook    Imagem do Facebook
     * @param   string      $imagem_google      Imagem do Google
     * @param   string      $imagem_arquivo     Arquivo de imagem
     * @return  array                           Array com perfil do usuário
     */
    public function montarUsuario(
        $id,
        $perfil,
        $nome,
        $imagem_tipo,
        $imagem_facebook,
        $imagem_google,
        $imagem_arquivo
    ) {
        return [
            'id' => $id,
            'perfil' => $perfil,
            'nome' => $nome,
            'imagem' => imagemUsuario($imagem_tipo, $imagem_arquivo, $imagem_facebook, $imagem_google)
        ];
    }
}
