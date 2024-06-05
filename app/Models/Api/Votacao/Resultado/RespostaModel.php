<?php

namespace App\Models\Api\Votacao\Resultado;

use ORM\ORM;
use Helpers\OrmHelper;

final class RespostaModel extends ORM
{
    public array $banco = [];
    public array $lista = [];

    /**
     * Pega a lista de respostas
     *
     * @param PerguntaModel $Pergunta O model com a lista de perguntas
     */
    public function __construct(
        private PerguntaModel $Pergunta
    ) {
        $resposta = $this->pegarResposta();
        $this->montarResposta($resposta);
    }

    private function pegarResposta()
    {
        $this->banco = (new OrmHelper(TABELA_VOTACAO_RESPOSTA))->listar(
            campo: ['id', 'id_votacao_pergunta', 'titulo'],
            where: ['id_admin_pergunta', 'in', $this->Pergunta->idPergunta]
        );
    }

    private function montarResposta($lista)
    {
        $resposta = [];
        foreach ($this->banco as $r) {
            $resposta[$r->id] = $r->titulo;
        }
        $this->lista = $resposta;
    }
}
