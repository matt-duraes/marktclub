<?php

namespace App\Classes\ComercialEmpresa;

final class Helper
{
    public const CRIPTOGRAFAR = [
        'usuario' => ['nome', 'perfil', 'imagem'],
        'titulo', 'nome_fantasia', 'razao_social', 'responsavel_nome', 'responsavel_cargo', 'responsavel_email',
        'responsavel_telefone', 'responsavel_cpf', 'valor_pago', 'renda_media', 'valor_pib', 'cnpj', 'contrato_valor',
        'contrato_valor_minimo'
    ];
    public const STATUS_LIBERADO = [1, 4];
}
