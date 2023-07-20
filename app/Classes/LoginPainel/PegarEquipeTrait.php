<?php

namespace App\Classes\LoginPainel;

use Helpers\OrmHelper;

trait PegarEquipeTrait
{
    private function pegarEquipe(array $where)
    {
        return (new OrmHelper(TABELA_USUARIO_EQUIPE))->pegarUltimoRegistro(
            where: $where,
            campo: [
                'id', 'uuid', 'salt', 'imagem_tipo', 'imagem_arquivo', 'imagem_facebook', 'imagem_google',
                'data_criacao', 'data_atualizacao', 'nome_real', 'email_pessoal', 'email_trabalho'
            ],
            retorno: 'object'
        );
    }
}
