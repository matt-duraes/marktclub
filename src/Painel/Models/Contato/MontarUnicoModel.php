<?php

namespace PainelModel\Contato;

use stdClass;
use Helpers\ApiHelper;

final class MontarUnicoModel
{
    public stdClass $contato;

    public function __construct(
        private string $id
    ) {
        $this->buscarUnico();
    }

    private function buscarUnico()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->validar('Erro ao buscar o contato, por favor, tente novamente.')
            ->get('/contato/' . $this->id)
            ->object();
        $this->contato = $dado->dado;
    }
}
