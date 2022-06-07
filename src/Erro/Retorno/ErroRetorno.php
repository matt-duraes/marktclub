<?php

namespace Erro\Retorno;

use Erro\Erro;

final class ErroRetorno extends ErrorGeral
{

    use LogTrait;

    public function __construct(Erro $error)
    {
        if (SISTEMA == 'PRODUCAO') {
            $this->salvarLogErro(
                $error->getMessage(),
                $error->getCode(),
                $error->getFile(),
                $error->getLine(),
                $error->getTrace()
            );
        }

        $this->retorno = $error->retorno();
        $this->tipoErro = 'fatal';

        parent::__construct($error);
    }
}
