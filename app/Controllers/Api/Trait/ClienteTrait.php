<?php

namespace App\Controllers\Api\Trait;

use App\Models\Api\UsuarioCliente\ClienteEntity;

trait ClienteTrait
{
    /**
     * Pega uma entidade do cliente
     *
     * @param  null|string        $id            Uuid ou url do parceiro
     * @param  bool               $obrigatorio   Se o id deve ser obrigatório
     * @param  null|string        $tituloVazio   Título para o erro caso esteja vazio
     * @param  null|string        $MensagemVazio Mensagem para o erro caso esteja vazio
     * @param  null|string        $tituloErro    Título para o erro caso de algum problema
     * @param  null|string        $mensagemErro  Mensagem para o erro caso de algum problema
     * @return null|ClienteEntity Retorna null para se o ID for vazio e
     *                            obrigatorio false ou um ClienteEntity
     * @throws Excessao           Erro caso o ID seja vazio e obrigatorio
     *                            true ou se não achar o cliente
     */
    private function pegarCliente(
        ?string $id,
        bool $obrigatorio = false,
        ?string $tituloVazio = null,
        ?string $mensagemVazio = null,
        ?string $tituloErro = null,
        ?string $mensagemErro = null
    ): null|ClienteEntity {
        if (empty($id) && $obrigatorio) {
            mensagemErro(
                empty($tituloVazio) ? 'Campo obrigatório!' : $tituloVazio,
                empty($mensagemVazio) ? 'O campo usuario é obrigatório.' : $mensagemVazio
            );
        } elseif (empty($id)) {
            return null;
        }

        $tituloErro = empty($tituloErro) ? 'Usuário não encontrado!' : $tituloErro;
        $mensagemErro = empty($mensagemErro) ? 'Não foi encontrado nenhum usuário pelo código enviado.' : $mensagemErro;

        $Cliente = new ClienteEntity(validarToken: false);
        $Cliente->uuid($id, titulo: $tituloErro, mensagem: $mensagemErro);

        return $Cliente;
    }
}
