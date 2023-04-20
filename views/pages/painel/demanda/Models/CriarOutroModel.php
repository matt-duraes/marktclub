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
        private string $titulo,
        private string $empresa,
        private string $texto,
        private string $tipo
    ) {
        $this->criarDemanda($titulo, $tipo, Area::TECNOLOGIA);
        $this->verificarSeSalvouDemanda();
        $this->salvarTarefa('nao-definido', $titulo, $texto);
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
