<?php

namespace App\Classes\Carteirinha;

class Helper
{
    public const CRIPTOGRAFAR = [
        'nome', 'matricula', 'numero_cartao',
        'documento_cpf', 'documento_rg', 'data_nascimento',
        'data_filiacao', 'data_emissao', 'data_validade'
    ];
}
