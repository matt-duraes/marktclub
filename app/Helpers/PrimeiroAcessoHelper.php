<?php

namespace App\Helpers;

final class PrimeiroAcessoHelper
{
    public const CAMPOS_PADRAO = [
        'nome', 'cpf', 'data_nascimento', 'genero', 'estado_civil', 'email_pessoal',
        'email_trabalho', 'telefone_pessoal', 'telefone_trabalho', 'endereco_cep',
        'endereco_logradouro', 'endereco_numero', 'endereco_complemento', 'endereco_bairro',
        'endereco_estado', 'endereco_cidade', 'senha'
    ];
    public const CAMPOS = [
        'nome'                 => 'Nome',
        'cpf'                  => 'CPF',
        'data_nascimento'      => 'Data de nascimento',
        'genero'               => 'Gênero',
        'estado_civil'         => 'Estado civil',
        'email_pessoal'        => 'E-mail pessoal',
        'email_trabalho'       => 'E-mail do trabalho',
        'telefone_pessoal'     => 'Telefone pessoal',
        'telefone_trabalho'    => 'Telefone do trabalho',
        'endereco_cep'         => 'CEP',
        'endereco_logradouro'  => 'Logradouro',
        'endereco_numero'      => 'Número',
        'endereco_complemento' => 'Complemento',
        'endereco_bairro'      => 'Bairro',
        'endereco_estado'      => 'Estado',
        'endereco_cidade'      => 'Cidade',
        'senha'                => 'Senha',
        'grupo'                => 'Grupo',
        'lotacao'              => 'Lotação',
        'trabalho_cargo'       => 'Cargo'
    ];
}
