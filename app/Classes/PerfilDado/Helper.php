<?php

namespace App\Classes\PerfilDado;

use App\Classes\ComercialEmpresa\Helper as EmpresaHelper;

final class Helper
{
    public const CRIPTOGRAFAR = [
        'nome', 'siape', 'matricula', 'estado_civil', 'grupo', 'email', 'email_pessoal',
        'email_trabalho', 'endereco_cep', 'endereco_logradouro', 'endereco_numero',
        'endereco_complemento', 'endereco_bairro', 'federacao', 'cpf', 'rg',
        'telefone_pessoal', 'telefone_trabalho', 'situacao', 'data_nascimento',
        'genero', 'endereco_cidade', 'endereco_estado', 'trabalho_empresa',
        'trabalho_cargo', 'tipo_pagamento', 'trabalho_data_inicio', 'senha',
        'imagem', 'imagem_arquivo', 'grupo', 'pesquisa', 'crm_numero', 'crm_estado',
        'senha_atual', 'senha_nova',
        'empresa' => EmpresaHelper::CRIPTOGRAFAR
    ];
}
