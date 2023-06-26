<?php

namespace App\Classes\ComercialEmpresa;

final class Helper
{
    public const CRIPTOGRAFAR = [
        'usuario' => ['nome', 'perfil', 'imagem'],
        'titulo', 'nome_fantasia', 'razao_social', 'responsavel_nome', 'responsavel_email', 'responsavel_telefone',
        'responsavel_cpf', 'valor_pago', 'renda_media', 'valor_pib', 'cnpj'
    ];
    public const STATUS_LIBERADO = [1, 3];
}
