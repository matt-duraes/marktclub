<?php

namespace App\Models\Api\UsuarioEquipe;

use Helpers\OrmHelper;
use Modules\Botao;

final class PerfilModel
{
    private array $usuarioVazio;
    private array $listaUsuario = [];

    public function __construct()
    {
        $this->usuarioVazio = [
            'id'      => '',
            'perfil'  => '',
            'nome'    => 'Sem usuário',
            'imagem'  => imagemUsuario(),
            'gerente' => 'nao'
        ];
    }

    /**
     * Pega os dados da equipe em massa
     *
     * @param array $id    Lista de ID da equipe para buscar
     * @param bool  $vazio Retorna os usuários não encontrados como vazio
     *
     * @return array Retorna a lista de usuario
     */
    public function pegarLista(array $id, bool $vazio = true): array
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
     * Pegar um perfil da equipe
     *
     * @param null|int|string $id    ID ou UUID do usuário
     * @param bool            $vazio Se vai retornar um usuário vazio
     *
     * @return array Array vazio ou com um usuário
     */
    public function pegarDado(null|int|string $id, bool $vazio = true): array
    {
        if (empty($id)) {
            return $vazio ? $this->usuarioVazio : [];
        } elseif (array_key_exists($id, $this->listaUsuario)) {
            return $this->listaUsuario[$id];
        } elseif (is_int($id)) {
            $where = ['id', $id];
        } elseif (in_array(strlen($id), [32, 36])) {
            $where = ['uuid', $id];
        } else {
            return $vazio ? $this->usuarioVazio : [];
        }

        $equipe = (new OrmHelper(TABELA_USUARIO_EQUIPE))
            ->pegarUltimoRegistro(
                where: $where,
                campo: [
                    'uuid', 'nome_perfil', 'nome_real', 'imagem_tipo', 'imagem_arquivo', 'imagem_facebook',
                    'imagem_google', 'gerente'
                ],
                retorno: 'object'
            );

        $dado = [
            'id'      => $equipe->uuid,
            'perfil'  => $equipe->nome_perfil,
            'nome'    => $equipe->nome_real,
            'imagem'  => imagemUsuario(
                $equipe->imagem_tipo,
                $equipe->imagem_arquivo,
                $equipe->imagem_facebook,
                $equipe->imagem_google
            ),
            'gerente' => $equipe->gerente == 1 ? 'sim' : 'nao'
        ];
        $this->listaUsuario[$id] = $dado;
        return $dado;
    }

    /**
     * Monta um perfil do usuário
     *
     * @param string      $id              UUID do usuário
     * @param string      $perfil          Perfil do usuário
     * @param string      $nome            Nome do usuário
     * @param int|null    $imagem_tipo     Tipo da imagem do usuário
     * @param string|null $imagem_facebook Imagem do Facebook
     * @param string|null $imagem_google   Imagem do Google
     * @param string|null $imagem_arquivo  Arquivo de imagem
     * @param string      $gerente         Se o usuário é um gerente, passar sim ou nao
     *
     * @return array Array com perfil do usuário
     */
    public function montarUsuario(
        string $id,
        string $perfil,
        string $nome,
        int $imagem_tipo = null,
        string $imagem_facebook = null,
        string $imagem_google = null,
        string $imagem_arquivo = null,
        string $gerente = 'nao'
    ): array {
        return [
            'id'      => $id,
            'perfil'  => $perfil,
            'nome'    => $nome,
            'imagem'  => imagemUsuario($imagem_tipo, $imagem_arquivo, $imagem_facebook, $imagem_google),
            'gerente' => (new Botao($gerente))->valor()
        ];
    }
}
