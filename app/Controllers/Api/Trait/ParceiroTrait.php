<?php

namespace App\Controllers\Api\Trait;

use App\Models\Api\ConvenioParceiro\ParceiroEntity;

trait ParceiroTrait
{
    /**
     * Pega uma entidade do parceiro
     *
     * @param   null|string             $id             Uuid ou url do parceiro
     * @param   bool                    $obrigatorio    Se o id deve ser obrigatório
     * @return  bool|ParceiroEntity                     Retorna null para se o ID for vazio e obrigatorio false ou um ParceiroEntity
     * @throws  Excesao                                 Erro caso o ID seja vazio e obrigatorio true ou se não achar o parceiro
     */
    private function pegarParceiro(
        ?string $id,
        bool $obrigatorio = false,
        ?string $tituloVazio = null,
        ?string $mensagemVazio = null,
        ?string $tituloErro = null,
        ?string $mensagemErro = null
    ): null|ParceiroEntity {
        if (empty($id) && $obrigatorio) {
            mensagemErro(
                empty($tituloVazio) ? 'Campo obrigatório!' : $tituloVazio,
                empty($mensagemVazio) ? 'O campo parceiro é obrigatório.' : $mensagemVazio,
            );
        } else if (empty($id)) {
            return null;
        }

        $tituloErro = empty($tituloErro) ? 'Parceiro não encontrado!' : $tituloErro;
        $mensagemErro = empty($mensagemErro) ? 'Não foi encontrado nenhum parceiro pelo código enviado.' : $mensagemErro;

        $Parceiro = new ParceiroEntity;
        $Parceiro->idSlug($id, titulo: $tituloErro, mensagem: $mensagemErro);

        return $Parceiro;
    }
}
