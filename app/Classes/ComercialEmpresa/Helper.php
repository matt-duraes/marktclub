<?php

namespace App\Classes\ComercialEmpresa;

final class Helper
{
    public const CRIPTOGRAFAR = [
        'nome_fantasia', 'razao_social', 'cnpj', 'slug', 'imagem', 'responsavel_nome',
        'responsavel_cpf', 'responsavel_email', 'responsavel_telefone'
    ];
    public const STATUS_LIBERADO = [1, 2];
}
