<?php

namespace App\Models\Api\Votacao\Resultado\Trait;

use App\Models\Api\Votacao\Resultado\VotoModel;
use App\Models\Api\Votacao\Resultado\UsuarioModel;
use App\Models\Api\Votacao\Resultado\VotacaoModel;
use App\Models\Api\Votacao\Resultado\PerguntaModel;
use App\Models\Api\Votacao\Resultado\RespostaModel;
use App\Models\Api\Votacao\Resultado\ResultadoModel;

trait PropriedadeTrait
{
    private VotacaoModel $Votacao;
    private PerguntaModel $Pergunta;
    private RespostaModel $Resposta;
    private ResultadoModel $Resultado;
    private VotoModel $Voto;
    private UsuarioModel $Usuario;
    public array $retorno = [
        'resultado' => [],
        'lista'     => [],
        'usuario'   => []
    ];
}
