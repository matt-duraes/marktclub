<?php

namespace App\Models\Site\Saude\simulacao;

use App\Helpers\ClubeApiHelper;

final class EscolhidaModel extends ClubeApiHelper
{
    public string $id = '';
    public function __construct(
        string $plano,
        array $dado
    )
    {
        parent::__construct();
        $this->salvarSimulacao($plano, $dado);
    }

    private function salvarSimulacao(string $plano, array $dado)
    {
        $salvar = $this
            ->body([
                'convenio' => $plano,
                'simulacao' => $dado
            ])
            ->post('/saude-simulacao')
            ->object();

        if(!validarIndiceExiste($salvar, 'dado.id')) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar sua simulação, por favor, tente novamente.');
        }
        $this->id = $salvar->dado->id;
    }
}
