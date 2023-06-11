<?php

namespace App\Classes\Carteirinha;

class Helper
{
    public const CRIPTOGRAFAR = [
        'usuario' => ['nome', 'matricula', 'numero_cartao', 'documento',
        'documento_rg', 'aniversario', 'data_filiacao']
    ];
    public const STATUS_LIBERADO = [1];
}
