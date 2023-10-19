<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class Categoria extends Status
{
    public const ALIMENTACAO = 'alimentacao';
    public const BELEZA = 'beleza';
    public const EDUCACAO = 'educacao';
    public const ELETROELETRONICO = 'eletroeletronico';
    public const OUTROS = 'outros';
    public const SAUDE = 'saude';
    public const VEICULO = 'veiculo';
    public const VESTUARIO = 'vestuario';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::ALIMENTACAO      => 'Alimentação',
            self::BELEZA           => 'Beleza',
            self::EDUCACAO         => 'Educação',
            self::ELETROELETRONICO => 'Eletroeletronico',
            self::OUTROS           => 'Outros',
            self::SAUDE            => 'Saúde',
            self::VEICULO          => 'Veículo',
            self::VESTUARIO        => 'Vestuário'
        ]);
    }
}
