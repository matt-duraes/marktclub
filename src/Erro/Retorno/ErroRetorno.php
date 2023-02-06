<?php

namespace Erro\Retorno;

use Erro\Erro;

final class ErroRetorno extends ErrorGeral
{

    use LogTrait;

    public function __construct(Erro $error)
    {
        $this->retorno = $error->retorno();
        $this->tipoErro = 'fatal';

        parent::__construct($error);
    }
}
