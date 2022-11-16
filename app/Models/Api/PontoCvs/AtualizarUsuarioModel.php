<?php

namespace App\Models\Api\PontoCvs;

use App\Models\Api\GeralModel;

final class AtualizarUsuarioModel extends GeralModel
{
    protected string $_tabela = TABELA_USUARIO_NOVO;

    public function atualizarUsuario(array $dado)
    {
        $cpf = $dado['cpf'];
        if (!$this->existe([
            ['documento', $cpf],
            [
                'OR',
                ['empresa', 198],
                [
                    ['empresa', 1],
                    ['tipo', 3]
                ]
            ]
        ])) {
            mensagemErro('Erro!', 'Seu Usuário não foi encontrado.');
        }

        if (empty($dado["nome"])) {
            unset($dado['nome']);
        }

        unset($dado['cpf']);

        $atualizar = $this->dado($dado)->where(['documento', $cpf])->update();
        if (existeErro($atualizar, 'id')) {
            mensagemErro('Erro!', 'O Ponto foi solicitado mas ouve um erro ao atualizar os dados de usuário.');
        }
    }
}
