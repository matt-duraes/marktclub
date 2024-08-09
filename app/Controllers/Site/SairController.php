<?php

namespace App\Controllers\Site;

use Http\Response;
use Controller\Controller;

final class SairController extends Controller
{
    public function index()
    {
        return $this->destruirSessao(LINK.'/login');
    }

    public function sairPersonalizado()
    {
        return $this->destruirSessao(LINK_BOTAO_SAIR);
    }

    /**
     * Destruir sessão e redirecionar para página de login ou link personalizado
     *
     * @param string $link_sair
     * @return Response
     */
    private function destruirSessao(string $link_sair): Response
    {
        sessaoDestruir();
        cookieDeletar('CLT');
        return new Response(url: $link_sair);
    }
}
