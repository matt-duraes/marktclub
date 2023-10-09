<?php

namespace Painel\Demanda\Models;

use stdClass;
use App\Classes\DemandaDado\Area;

final class CriarOutroModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;

    public function __construct(
        private string $empresaNome,
        private string $titulo,
        private string $empresa,
        private string $tipo
    ) {
        $this->criarDemanda($empresaNome . $titulo, $tipo, Area::TECNOLOGIA);
        $this->verificarSeSalvouDemanda();
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
