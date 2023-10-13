<?php

namespace App\Classes\SolicitacaoLoja;

final class Helper
{
    public const CRIPTOGRAFAR = [
        'nome', 'email', 'telefone',
        'quem_indicou' => ['nome', 'cpf', 'email']
    ];
}
