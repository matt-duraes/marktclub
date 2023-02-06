<?php

namespace Erro\Retorno;

use Erro\Alerta;

final class AlertaRetorno extends ErrorGeral
{
    use LogTrait;

    public function __construct(Alerta $error)
    {
        $this->retorno = $error->retorno();
        $this->tipoErro = 'alerta';

        parent::__construct($error);
    }
}
