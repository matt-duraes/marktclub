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
        ?string $titulo = null,
        ?string $mensagem = null
    ): null|ParceiroEntity {
        if (empty($id) && $obrigatorio) {
            mensagemErro('Campo obrigatório!', 'O campo parceiro é obrigatório.');
        } else if (empty($id)) {
            return null;
        }

        $titulo = empty($titulo) ? 'Parceiro não encontrado!' : $titulo;
        $mensagem = empty($mensagem) ? 'Não foi encontrado nenhum parceiro pelo código enviado.' : $mensagem;

        $Parceiro = new ParceiroEntity;
        $Parceiro->idSlug($id, titulo: $titulo, mensagem: $mensagem);

        return $Parceiro;
    }
}
