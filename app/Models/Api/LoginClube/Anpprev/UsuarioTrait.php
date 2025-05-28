<?php

namespace App\Models\Api\LoginClube\Anpprev;

use Erro\Excecao;
use Modules\Botao;
use stdClass;

trait UsuarioTrait
{
    /**
     * @return stdClass
     * @throws Excecao
     */
    private function pegarUsuarioAnpprev(): stdClass
    {
        $cpf = str_pad(soNumero($this->login), 11, '0', STR_PAD_LEFT);
        return (new LoginModel(
            login: $cpf,
            senha: $this->senha,
            empresa: $this->idEmpresa,
            cadastro: $this->cadastro,
            termo: new Botao($this->termo)
        ))->Usuario;
    }
}
