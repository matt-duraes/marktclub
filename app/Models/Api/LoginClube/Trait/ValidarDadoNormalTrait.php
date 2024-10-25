<?php

namespace App\Models\Api\LoginClube\Trait;

use Modules\Botao;

trait ValidarDadoNormalTrait
{
    protected function validarDadosDeLogin(): void
    {
        if (empty($this->login)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar seu login para continuar.');
        } elseif (empty($this->senha)) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve digitar sua senha para continuar.');
        } elseif ($this->cadastro->valor() == Botao::SIM && $this->termo->valor() != Botao::SIM) {
            mensagemErro(titulo: 'Campo obrigatório!', mensagem: 'Você deve aceitar os termo de uso para continuar.');
        }
    }
}
