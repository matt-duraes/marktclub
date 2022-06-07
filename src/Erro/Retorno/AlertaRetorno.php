<?php

namespace Erro\Retorno;

use Erro\Alerta;

final class AlertaRetorno extends ErrorGeral
{
    use LogTrait;

    public function __construct(Alerta $error)
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
        $this->tipoErro = 'alerta';

        parent::__construct($error);
    }
}
