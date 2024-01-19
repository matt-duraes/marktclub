<?php

namespace App\Classes\UsuarioIndicacao;

final class Helper
{
    public const PERMISSAO_EMPRESA = 'usuario_indicacao_empresa';
    public const CRIPTOGRAFAR = [
        'nome', 'email', 'cpf', 'telefone',
        'quem_indicou'  => ['nome', 'cpf', 'email'],
        'usuario_ativo' => ['nome', 'cpf', 'email']
    ];
}
