<?php

namespace App\Classes\UsuarioCliente;

final class Helper
{
    const CRIPTOGRAFAR = [
        'nome', 'siape', 'matricula', 'estado_civil', 'email', 'email_pessoal', 'email_trabalho', 'endereco_cep',
        'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro',
        'tipo', 'federacao', 'grupo', 'cpf', 'rg', 'telefone_pessoal', 'telefone_trabalho', 'situacao',
        'data_nascimento', 'genero', 'endereco_cidade', 'endereco_estado', 'trabalho_empresa', 'trabalho_cargo',
        'tipo_pagamento', 'trabalho_data_inicio', 'senha'
    ];
}
