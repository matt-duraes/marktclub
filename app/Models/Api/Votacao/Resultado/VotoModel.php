<?php

namespace App\Models\Api\Votacao\Resultado;

use ORM\ORM;
use Helpers\OrmHelper;

final class VotoModel extends ORM
{
    public array $voto = [];

    /**
     * Pegar a lista de votos
     *
     * @param integer $id ID da votação
     */
    public function __construct(
        private int $id
    ) {
        $this->pegarVoto();
    }

    private function pegarVoto()
    {
        $this->voto = (new OrmHelper(TABELA_VOTACAO_VOTO))->listar(
            campo: [
                'id_usuario_cliente', 'id_votacao_pergunta', 'id_votacao_resposta',
                'resposta_outro', 'voto_livre', 'data_criacao'
            ],
            where: ['id_votacao_dado', $this->id]
        );
    }
}
