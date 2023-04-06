<?php

namespace App\Classes\UsuarioCliente;

use App\Classes\ComercialEmpresa\Helper as EmpresaHelper;

final class Helper
{
    public const CRIPTOGRAFAR = [
        'nome', 'siape', 'matricula', 'estado_civil', 'email', 'email_pessoal', 'email_trabalho',
        'endereco_cep', 'endereco_logradouro', 'endereco_numero', 'endereco_complemento',
        'endereco_bairro', 'federacao', 'cpf', 'rg', 'telefone_pessoal', 'telefone_trabalho', 'situacao',
        'data_nascimento', 'genero', 'endereco_cidade', 'endereco_estado', 'trabalho_empresa', 'trabalho_cargo',
        'tipo_pagamento', 'trabalho_data_inicio', 'senha', 'imagem', 'grupo', 'pesquisa',
        'crm_numero', 'crm_estado',
        'empresa' => EmpresaHelper::CRIPTOGRAFAR
    ];
    public const STATUS_LIBERADO = [1, 2, 3, 5];
    public const PERMISSAO_EMPRESA = 'usuario_cliente_empresa';
    public const PERMISSAO_VISUALIZAR = 'usuario_cliente_index';
}
