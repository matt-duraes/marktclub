<?php

namespace App\Models\Api\Trait;

use Helpers\OrmHelper;

trait BuscarClienteTrait
{
    private function pegarCliente(array $where, array $campoAdicional = [])
    {
        return (new OrmHelper(TABELA_USUARIO_CLIENTE))->pegarUltimoRegistro(
            where: $where,
            campo: array_merge([
                'id', 'uuid', 'salt', 'cpf', 'nome', 'imagem', 'email_pessoal', 'email_trabalho', 'tipo',
                'grupo', 'primeiro_acesso', 'mudar_senha', 'data_termo', 'data_criacao', 'data_atualizacao'
            ], $campoAdicional),
            retorno: 'object'
        );
    }
}
