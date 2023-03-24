<?php

namespace App\Controllers\Api\Trait;

use App\Models\Api\UsuarioCliente\ClienteEntity;

trait ClienteTrait
{
    /**
     * Pega uma entidade do cliente
     *
     * @param   null|string         $id             Uuid ou url do parceiro
     * @param   bool                $obrigatorio    Se o id deve ser obrigatório
     * @param   null|string         $titulo         Título de erro personalizado
     * @param   null|string         $mensagem       Mensagem de erro personalizada
     * @return  null|ClienteEntity                  Retorna null para se o ID for vazio e obrigatorio false ou um ClienteEntity
     * @throws  Excessao                            Erro caso o ID seja vazio e obrigatorio true ou se não achar o cliente
     */
    private function pegarCliente(
        ?string $id,
        bool $obrigatorio = false,
        ?string $titulo = null,
        ?string $mensagem = null
    ): null|ClienteEntity {
        if (empty($id) && $obrigatorio) {
            mensagemErro('Campo obrigatório!', 'O campo usuario é obrigatório.');
        } else if (empty($id)) {
            return null;
        }

        $titulo = empty($titulo) ? 'Usuário não encontrado!' : $titulo;
        $mensagem = empty($mensagem) ? 'Não foi encontrado nenhum usuário pelo código enviado.' : $mensagem;

        $Cliente = new ClienteEntity(validarToken: false);
        $Cliente->id($id, titulo: $titulo, mensagem: $mensagem);

        return $Cliente;
    }
}
