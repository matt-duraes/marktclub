<?php

namespace App\Models\Api\Saude\Contratacao\Proasa\Negociacao;

use App\Models\Api\Saude\Simulacao\ContratarModel;
use App\Models\Api\Saude\Contratacao\Proasa\ApiAbstract;
use App\Models\Api\Saude\Contratacao\Proasa\Contato\Salvar as Contato;

final class Salvar extends ApiAbstract {
    public function __construct(
        private Contato $Contato,
        private string $empresa,
        private array $simulacao
    )
    {
        parent::__construct();
        $Contato = new Buscar(Contato: $Contato);
        if($Contato->existe) {
            return;
        }
        $this->salvarContrato();
    }

    private function salvarContrato()
    {
        $salvar = $this
            ->header($this->headerAcceptJson())
            ->json([
                'distribution_settings' => [
                    'owner' => [
                        'type' => 'owner',
                        'email' => env('PROASA_RD_EMAIL', '')
                    ],
                ],
                'set_contacts' => [
                    [
                        'id' => $this->Contato->id
                    ]
                ],
                'campaign' => [
                    '_id' => env('PROASA_RD_CAMPANHA'),
                ],
                'deal' => [
                    'name' => $this->empresa . ' | ' . $this->simulacao['convenio'] . ' | ' . $this->simulacao['plano']
                ],
                'deal_products' => $this->adicionarValor()
            ])
            ->post($this->uri('/deals'))
            ->array();

        $this->validarDadoSalvo($salvar);
    }

    private function adicionarValor(): array
    {
        $retorno = [];
        foreach($this->simulacao['valor'] as $nome => $valor) {
            $retorno[] = [
                'amount' => 1,
                'name' => $nome,
                'price' => (float)$valor,
                'total' => (float)$valor
            ];
        }
        return $retorno;
    }
}
