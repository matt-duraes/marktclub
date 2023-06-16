<?php

namespace App\Models\Site\Sicoob;

use stdClass;

final class ParcelaModel
{
    public function listarConsignado(): stdClass
    {
        $parcela = [
            '' => 'Escolha uma opção',
            1  => '1 mês - 1,59%'
        ];
        for ($i = 2; $i <= 96; ++$i) {
            $parcela[$i] = $i . ' meses - 1,59%';
        }

        return (object)[
            'tipo'  => 'consignado',
            'lista' => (object)[
                'titulo' => 'Com o Crédito Consignado SICOOB Judiciário, você tem as melhores taxas do mercado.',
                'parcela' => $parcela,
            ]
        ];
    }
    public function listarCreditoPessoal(): stdClass
    {
        $parcela = [
            '' => 'Escolha uma opção',
            1  => '1 mês - 3,10%'
        ];
        for ($i = 2; $i <= 96; ++$i) {
            $parcela[$i] = $i . ' meses - 3,10%';
        }

        return (object)[
            'tipo'  => 'credito_pessoal',
            'lista' => (object)[
                'titulo' => 'Agora você conta com uma linha de crédito feita especialmente para você.',
                'parcela'   => $parcela,
            ]
        ];
    }
    public function listarVeiculoZero(): stdClass
    {
        $parcela = [
            '' => 'Escolha uma opção',
            1  => '1 mês - 2,10%'
        ];
        for ($i = 2; $i <= 96; ++$i) {
            $parcela[$i] = $i . ' meses - 2,10%';
        }

        return (object)[
            'tipo'  => 'veiculo_novo',
            'lista' => (object)[
                'titulo' => 'A realização do sonho do carro 0km está mais próximo.
                Confira o que preparamos para você.',
                'parcela'   => $parcela
            ]
        ];
    }
    public function listarVeiculoSeminovo(): stdClass
    {
        $parcela = [
            '' => 'Escolha uma opção',
            1  => '1 mês - 3,50%'
        ];
        for ($i = 2; $i <= 96; ++$i) {
            $parcela[$i] = $i . ' meses - 3,50%';
        }

        return (object)[
            'tipo'  => 'veiculo_seminovo',
            'lista' => (object)[
                'titulo' => 'Se você quer dar um upgrade no seu carro, a hora é agora!
                Linha específica para carros seminovos.',
                'parcela'   => $parcela,
            ]
        ];
    }
}
