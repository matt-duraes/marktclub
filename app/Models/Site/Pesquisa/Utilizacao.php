<?php

namespace App\Models\Site\Pesquisa;

use Helpers\ApiHelper;
use App\Classes\Comercial\Empresa\UUID;

final class Utilizacao extends ApiHelper
{
    public bool $ativo = false;
    public function __construct()
    {
        if(sessaoExiste('PESQUISA_UTILIZACAO')) {
            return;
        }
        sessao('PESQUISA_UTILIZACAO', true);
        if(
            $this->verificarEmpresaBloqueada() ||
            $this->usuarioJaVotou()
        ) {
            return;
        }
        $this->ativo = true;
    }

    private function verificarEmpresaBloqueada()
    {
        return in_array(CLUBE_EMPRESA, [UUID::DIGIO, UUID::BANCORBRAS]);
    }

    private function usuarioJaVotou()
    {
        // Remover isso aqui
        return false;
        try {
            $dado = $this
                ->post('/pesquisa-resposta')
                ->object();
            if(!validarIndiceExiste($dado, 'dado.respondeu')) {
                return false;
            }
            return $dado->dado->respondeu === 'sim';
        } catch (\Throwable) {
            return false;
        }
    }
}
