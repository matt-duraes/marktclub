<?php

namespace App\Models\Api\Votacao\Resultado;

use ORM\ORM;
use Helpers\OrmHelper;

final class PerguntaModel extends ORM
{
    public array $banco = [];
    public array $lista = [];
    public array $idPergunta = [];

    /**
     * Pega a lista de perguntas
     *
     * @param integer $id ID da votação
     */
    public function __construct(
        private int $id
    ) {
        $this->pegarPergunta();
        $this->montarPergunta();
    }

    private function pegarPergunta()
    {
        $this->banco = (new OrmHelper(TABELA_VOTACAO_PERGUNTA))->listar(
            where: ['id_votacao_dado', $this->id],
            campo: ['id', 'titulo'],
        );
    }

    private function montarPergunta()
    {
        $pergunta = [];
        foreach ($this->banco as $r) {
            $this->idPergunta[] = $r->id;
            $pergunta[$r->id] = $r->titulo;
        }
        $this->lista = $pergunta;
    }
}
