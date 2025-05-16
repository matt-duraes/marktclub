<?php

namespace App\Models\Site\Pesquisa;

use App\Classes\Comercial\Empresa\UUID;
use Helpers\ApiHelper;
use Throwable;

final class Utilizacao extends ApiHelper
{
    public bool $ativo = false;

    public function __construct()
    {
        if (sessaoExiste('PESQUISA_UTILIZACAO')) {
            return;
        }
        sessao('PESQUISA_UTILIZACAO', true);
        $this->ativo = !($this->verificarEmpresaBloqueada() || $this->usuarioJaVotou());
    }

    private function verificarEmpresaBloqueada()
    {
        return in_array(CLUBE_EMPRESA, [UUID::DIGIO, UUID::BANCORBRAS]);
    }

    private function usuarioJaVotou()
    {
        try {
            $dado = $this
                ->post('/pesquisa-resposta')
                ->object();
            if (!validarIndiceExiste($dado, 'dado.respondeu')) {
                return false;
            }
            return $dado->dado->respondeu === 'sim';
        } catch (Throwable) {
            return false;
        }
    }
}
