<?php

namespace App\Classes\ParceiroConvenio;

use Status\Status;

final class Categoria extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            [
                'alimentacao' => 'Alimentação',
                'beleza' => 'Beleza',
                'educacao' => 'Educação',
                'eletroeletronico' => 'Eletroeletronico',
                'outros' => 'Outros',
                'saude' => 'Saúde',
                'veiculo' => 'Veículo',
                'vestuario' => 'Vestuário'
            ]
        );
    }
}
