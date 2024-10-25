<?php

namespace App\Models\Api\LoginClube\EmporioNaval;

use Modules\Botao;

trait UsuarioTrait
{
    private function pegarUsuarioEmporioNaval()
    {
        $cpf = str_pad(soNumero($this->login), 11, '0', STR_PAD_LEFT);
        $usuario = (new LoginModel(
            login: $cpf,
            senha: $this->senha,
            empresa: $this->idEmpresa,
            cadastro: $this->cadastro,
            termo: new Botao($this->termo)
        ))->Usuario;

        return $usuario;
    }
}
