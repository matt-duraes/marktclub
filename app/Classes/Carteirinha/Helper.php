<?php

namespace App\Classes\Carteirinha;

class Helper
{
    public const CRIPTOGRAFAR = [
        'usuario' => [
            'nome', 'cpf', 'matricula', 'data_nascimento', 'data_filiacao'
        ]
    ];
    public const STATUS_LIBERADO = 1;
}
