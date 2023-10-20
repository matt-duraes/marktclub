<?php

namespace App\Classes\LoginClube;

use Helpers\OrmHelper;

trait PegarClienteTrait
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
