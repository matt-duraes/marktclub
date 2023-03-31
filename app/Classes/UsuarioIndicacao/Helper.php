<?php

namespace App\Classes\UsuarioIndicacao;

final class Helper
{
    public const CRIPTOGRAFAR = [
        'nome', 'email', 'cpf', 'telefone',
        'quem_indicou' => ['nome', 'cpf', 'email']
    ];
}
