<?php

namespace App\Models\Api\Votacao\Resultado;

use ORM\ORM;

final class ResultadoModel extends ORM
{
    public array $resultado = [];

    /**
     * Pega a lista de perguntas
     *
     * @param integer $id ID da votação
     */
    public function __construct(
        private PerguntaModel $Pergunta,
        private RespostaModel $Resposta,
    ) {
        $this->montarEstrutura();
    }

    private function montarEstrutura()
    {
        $pergunta = $this->Pergunta->lista;
        $retorno = [];
        foreach ($this->Resposta->banco as $r) {
            if (!array_key_exists($r->id_votacao_pergunta, $retorno)) {
                $retorno[$r->id_votacao_pergunta] = [
                    'pergunta' => $pergunta[$r->id_votacao_pergunta],
                    'resposta' => []
                ];
            }
            if (!array_key_exists($r->id, $retorno[$r->id_votacao_pergunta]['resposta'])) {
                $retorno[$r->id_votacao_pergunta]['resposta'][$r->id] = [
                    'resposta' => $r->titulo,
                    'voto'     => 0
                ];
            }
        }
        $this->resultado = $retorno;
    }

    /**
     * Adicionar um voto no resultado
     *
     * @param int $pergunta
     * @param int $resposta
     */
    public function voto(int $pergunta, int $resposta)
    {
        $this->resultado[$pergunta]['resposta'][$resposta]['voto']++;
    }
}
