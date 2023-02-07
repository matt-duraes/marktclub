<?php

namespace Painel\Demanda\Models;

use stdClass;

final class CriarAssociacaoModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;

    public function __construct(
        private string $empresa,
        private string $texto
    ) {
        $this->criarDemanda('Novo site para associação', 'novo-associacao');
        $this->verificarSeSalvouDemanda();
        $this->salvarTarefa('nao-definido', 'Novo site para associação', $texto);
    }


    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
