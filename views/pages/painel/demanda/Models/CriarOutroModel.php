<?php

namespace Painel\Demanda\Models;

use stdClass;

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
        $this->criarDemanda($titulo, $tipo, 'ti');
        $this->verificarSeSalvouDemanda();
        $this->salvarTarefa('nao-definido', $titulo, $texto);
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
