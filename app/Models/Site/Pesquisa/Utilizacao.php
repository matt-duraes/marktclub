<?php

namespace App\Models\Site\Pesquisa;

use Throwable;
use App\Helpers\ClubeApiHelper;
use App\Classes\Comercial\Empresa\UUID;

final class Utilizacao extends ClubeApiHelper
{
    public bool $mostrarPesquisa = false;

    public function __construct()
    {
        if (sessaoExiste('PESQUISA_UTILIZACAO')) {
            return;
        }
        parent::__construct();
        sessao('PESQUISA_UTILIZACAO', true);
        $this->mostrarPesquisa = !$this->verificarEmpresaBloqueada() && !$this->usuarioJaVotou();
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
