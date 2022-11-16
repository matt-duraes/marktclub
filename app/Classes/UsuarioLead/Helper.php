<?php

namespace App\Classes\UsuarioLead;

final class Helper
{
    const CRIPTOGRAFAR = [
        'nome', 'email_trabalho', 'email_pessoal', 'email_funcional', 'email', 'telefone_pessoal', 'telefone_trabalho',
        'cpf', 'rg', 'siape', 'genero', 'data_nascimento', 'trabalho_empresa', 'trabalho_cargo', 'trabalho_data_inicio',
        'endereco_cep', 'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro',
        'endereco_cidade', 'endereco_estado', 'cnpj_trabalho'
    ];
}
