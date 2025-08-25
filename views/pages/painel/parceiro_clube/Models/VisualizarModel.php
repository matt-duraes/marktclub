<?php

namespace Painel\ParceiroLoja\Models;

use stdClass;
use System\Interface\PainelVisualizarRetornoInterface;

final class VisualizarModel implements
    PainelVisualizarRetornoInterface
{
    public function retorno(stdClass $dado): stdClass
    {
        $dado->data_auditoria_valida =
            empty($dado->data_auditoria) || dataDiferencaDia($dado->data_auditoria, hoje()) > 30
                ? 'nao' : 'sim';
        return $dado;
    }
}
