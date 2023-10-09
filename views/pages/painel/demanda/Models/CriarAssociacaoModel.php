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
        $this->criarDemanda($empresaNome . 'Novo site para associação', 'novo-associacao', Area::TECNOLOGIA);
        $this->verificarSeSalvouDemanda();
        $this->salvarTarefa('nao-definido', 'Novo site para associação', $texto);
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
