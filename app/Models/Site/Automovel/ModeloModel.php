<?php

namespace App\models\Site\Automovel;

use stdClass;
use Helpers\ApiHelper;
use App\Models\Site\ListarInterface;

final class ModeloModel extends ApiHelper implements ListarInterface
{
    public function __construct()
    {
        parent::__construct(scope: '');
    }
    public function listarDados(): stdClass
    {
        return $this->montarRetorno();
    }

    private function montarRetorno(): stdClass
    {
        return (object)[
            'tipo' => 'veiculo',
            'lista' => [
                (object)[
                    'id' => uuid(),
                    'titulo' => 'Veículo 01',
                    'link' => route('automovel.modelo') . '/modelo/veiculo',
                    'imagem' => 'https://arquivo.marktclub.com.br/carro/21d2e08eaa1fbda5dae0138c160f710b.png',
                    'de' => 'Carta bônus de:',
                    'por' => 'R$ 200,00'
                ],
                (object)[
                    'id' => uuid(),
                    'titulo' => 'Veículo 02',
                    'link' => route('automovel.modelo') . '/modelo/veiculo',
                    'imagem' => 'https://arquivo.marktclub.com.br/carro/2a5f98e0f7dcfbc5a8daf2c2b9e3d0b6.png',
                    'de' => 'De: R$ 200,00',
                    'por' => 'Por: R$ 150,00'
                ],
                (object)[
                    'id' => uuid(),
                    'titulo' => 'Veículo 03',
                    'link' => route('automovel.modelo') . '/modelo/veiculo',
                    'imagem' => 'https://arquivo.marktclub.com.br/carro/bdb9fa3b66a6c9884125b2be674afd48.png',
                    'de' => 'De: R$ 200,00',
                    'por' => 'Por: R$ 150,00'
                ],
            ]
        ];
    }
}
