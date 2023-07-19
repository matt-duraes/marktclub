<?php

namespace Painel\ComercialEmpresa\Classes;

use Http\Request;
use Http\Response;
use System\Interface\PainelClasseInterface;
use App\Classes\ComercialEmpresa\FinalidadePrivada;
use App\Classes\ComercialEmpresa\FinalidadePublica;

final class FinalidadeSecundaria implements PainelClasseInterface
{
    private array $lista;

    public function __construct(
        private Request $request
    ) {
        if ($request->tipo == 'publica') {
            $this->lista = (new FinalidadePublica())->select('Escolha uma finalidade');
            return;
        } elseif ($request->tipo == 'privada') {
            $this->lista = (new FinalidadePrivada())->select('Escolha uma finalidade');
            return;
        }
        $this->lista = ['' => 'Escolha uma finalidade principal'];
    }

    public function retorno(): Response
    {
        return mensagemSucesso($this->lista);
    }
}
