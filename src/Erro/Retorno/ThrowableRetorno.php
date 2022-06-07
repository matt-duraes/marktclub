<?php

namespace Erro\Retorno;

final class ThrowableRetorno extends ErrorGeral
{
    public function __construct(\throwable $error)
    {
        if (SISTEMA == 'producao') {
            return '';
        }
        $this->tipoErro = 'fatal';
        $this->retorno = [];

        parent::__construct($error);
    }
}
