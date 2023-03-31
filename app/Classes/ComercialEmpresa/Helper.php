<?php

namespace App\Classes\ComercialEmpresa;

final class Helper
{
    public const CRIPTOGRAFAR = [
        'titulo', 'nome_fantasia', 'razao_social', 'cnpj', 'slug', 'imagem'
    ];
    public const STATUS_LIBERADO = [1, 2];
}
