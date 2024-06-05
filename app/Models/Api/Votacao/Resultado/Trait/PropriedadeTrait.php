<?php

namespace App\Models\Api\Votacao\Resultado\Trait;

use App\Models\Api\Votacao\Resultado\VotoModel;
use App\Models\Api\Votacao\Resultado\UsuarioModel;
use App\Models\Api\Votacao\Resultado\PerguntaModel;
use App\Models\Api\Votacao\Resultado\RespostaModel;
use App\Models\Api\Votacao\Resultado\ResultadoModel;

trait PropriedadeTrait
{
    private int $idVotacao;
    private PerguntaModel $Pergunta;
    private RespostaModel $Resposta;
    private ResultadoModel $Resultado;
    private VotoModel $Voto;
    private UsuarioModel $Usuario;
    private bool $identificarUsuario;
    public array $retorno = [
        'resultado' => [],
        'lista'     => [],
        'usuario'   => []
    ];
}
