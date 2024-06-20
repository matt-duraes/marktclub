<?php

namespace Painel\ParceiroEquipe\Models;

use stdClass;
use App\Classes\ParceiroLoja\Status;
use System\Interface\PainelVisualizarRetornoInterface;

final class VisualizarModel implements
    PainelVisualizarRetornoInterface
{
    public function retorno(stdClass $dado): stdClass
    {
        if ($dado->status != Status::PROSPECCAO || !empty($dado->equipe)) {
            mensagemStatus(404);
        }
        return $dado;
    }
}
