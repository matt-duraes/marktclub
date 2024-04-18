<?php

namespace PainelModel\Endereco;

use stdClass;
use Helpers\ApiHelper;

final class MontarUnicoModel
{
    public stdClass $endereco;

    public function __construct(
        private string $id
    ) {
        $this->buscarUnico();
        $this->montarDado();
    }

    private function buscarUnico()
    {
        $Api = new ApiHelper(token: true);
        $this->endereco = $Api
            ->validar('Erro ao buscar o endereço, por favor, tente novamente.')
            ->get('/endereco/' . $this->id)
            ->object()->dado;
    }

    private function montarDado()
    {
        $brasil = $this->endereco->pais == 'BR';
        $this->endereco->cep = $brasil ? strCep($this->endereco->cep) : $this->endereco->cep;
    }
}
