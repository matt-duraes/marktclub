<?php

namespace App\Models\Api\LoginClube\EmporioNaval;

use Modules\Botao;

trait UsuarioEmporioNavalTrait {
    private function pegarUsuarioEmporioNaval()
    {
        $cpf = str_pad(soNumero($this->login), 11, '0', STR_PAD_LEFT);
        $usuario = (new LoginEmporioNavalModel(
            login: $cpf,
            senha: $this->senha,
            empresa: $this->idEmpresa,
            cadastro: $this->cadastro,
            termo: new Botao($this->termo)
        ))->Usuario;

        return $usuario;
    }
}
