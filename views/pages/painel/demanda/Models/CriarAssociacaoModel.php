<?php

namespace Painel\Demanda\Models;

use stdClass;
use App\Classes\DemandaDado\Area;

final class CriarAssociacaoModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;

    public function __construct(
        private string $empresaNome,
        private string $empresa,
        private string $texto
    ) {
        $this->criarDemanda($empresaNome . 'Novo site para associação', $texto, 'novo-associacao', Area::TECNOLOGIA);
        $this->verificarSeSalvouDemanda();
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
