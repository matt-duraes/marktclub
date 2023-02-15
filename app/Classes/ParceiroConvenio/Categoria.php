<?php

namespace App\Classes\ParceiroConvenio;

use Status\Status;

final class Categoria extends Status
{
    const ALIMENTACAO = 'alimentacao';
    const BELEZA = 'beleza';
    const EDUCACAO = 'educacao';
    const ELETROELETRONICO = 'eletroeletronico';
    const OUTROS = 'outros';
    const SAUDE = 'saude';
    const VEICULO = 'veiculo';
    const VESTUARIO = 'vestuario';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            [
                self::ALIMENTACAO => 'Alimentação',
                self::BELEZA => 'Beleza',
                self::EDUCACAO => 'Educação',
                self::ELETROELETRONICO => 'Eletroeletronico',
                self::OUTROS => 'Outros',
                self::SAUDE => 'Saúde',
                self::VEICULO => 'Veículo',
                self::VESTUARIO => 'Vestuário'
            ]
        );
    }
}
