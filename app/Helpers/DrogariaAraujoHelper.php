<?php

namespace App\Helpers;

use Erro\Erro;

class DrogariaAraujoHelper
{
    private string $codigoPlano;

    /**
     * @throws Erro
     */
    public function __construct()
    {
        $envsAlfa = [
            'DROGARIA_ARAUJO_PLANO' => env('DROGARIA_ARAUJO_PLANO')
        ];

        foreach ($envsAlfa as $index => $value) {
            if (empty($value)) {
                throw new Erro(
                    "Variável de ambiente $index não foi seta ou está vazia",
                    'Variáveis de Ambiente',
                    "A variável de ambiente $index deve ser preenchida corretamente"
                );
            } elseif (!is_string($value)) {
                throw new Erro(
                    "Esperavamos um valor do tipo STRING na variável de ambiente $index",
                    'Tipagem da variável de ambiente',
                    "A variável de ambiente $index deve ser do tipo STRING"
                );
            }
        }

        $this->codigoPlano = env('DROGARIA_ARAUJO_PLANO');
    }

    /**
     * @return int
     */
    public function getCodigoPlano(): int
    {
        return (int)$this->codigoPlano;
    }
}
