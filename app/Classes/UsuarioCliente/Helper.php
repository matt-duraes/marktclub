<?php

namespace App\Classes\UsuarioCliente;

use App\Classes\ComercialEmpresa\Helper as EmpresaHelper;

final class Helper
{
    const CRIPTOGRAFAR = [
        'nome', 'siape', 'matricula', 'estado_civil', 'email', 'email_pessoal', 'email_trabalho',
        'endereco_cep', 'endereco_logradouro', 'endereco_numero', 'endereco_complemento',
        'endereco_bairro', 'federacao', 'cpf', 'rg', 'telefone_pessoal', 'telefone_trabalho', 'situacao',
        'data_nascimento', 'genero', 'endereco_cidade', 'endereco_estado', 'trabalho_empresa', 'trabalho_cargo',
        'tipo_pagamento', 'trabalho_data_inicio', 'senha', 'imagem', 'grupo', 'pesquisa',
        'empresa' => EmpresaHelper::CRIPTOGRAFAR
    ];
    const STATUS_LIBERADO = [1, 2, 3, 5];
    const PERMISSAO_EMPRESA = 'usuario_cliente_empresa';
}
