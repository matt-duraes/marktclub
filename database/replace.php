<?php

return [
    'empresa_novo' => [
        'cod' => 'uuid'
    ],
    'construtor_novo' => [
        'cod' => 'uuid'
    ],
    'cupom_bloqueio' => [
        'cod' => 'uuid'
    ],
    'parceiro_novo' => [
        'cod'     => 'uuid',
        'empresa' => 'id_admin_empresa',
        'site'    => 'link_site'
    ],
    'tag_novo' => [
        'cod' => 'uuid'
    ],
    'saude_contratacao' => [
        'id_usuario'       => 'id_usuario_cliente',
        'id_simulacao'     => 'id_saude_simulacao',
        'sexo'             => 'genero',
        'filiacao'         => 'nome_mae',
        'cpf_responsavel'  => 'responsavel_cpf',
        'rg_responsavel'   => 'responsavel_rg',
        'nome_responsavel' => 'responsavel_nome',
        'ramal'            => 'telefone_comercial_ramal',
        'endereco'         => 'endereco_logradouro',
        'cep'              => 'endereco_cep',
        'estado'           => 'endereco_estado',
        'cidade'           => 'endereco_cidade',
        'bairro'           => 'endereco_bairro',
        'numero'           => 'endereco_numero',
        'complemento'      => 'endereco_complemento'
    ],
    'saude_simulacao' => [
        'id_usuario' => 'id_usuario_cliente'
    ],
    'contato' => [
        'cod' => 'uuid'
    ],
    'endereco_novo' => [
        'cod'  => 'id_vinculo',
        'nome' => 'titulo'
    ],
    'solicitacao_voucher' => [
        'cod' => 'uuid'
    ],
    'usuario_novo' => [
        'cod'         => 'uuid',
        'empresa'     => 'id_admin_empresa',
        'titular'     => 'id_usuario_cliente',
        'documento'   => 'cpf',
        'sexo'        => 'genero',
        'aniversario' => 'data_nascimento',
        'cidade'      => 'endereco_cidade',
        'uf'          => 'endereco_estado'
    ],
    'usuario_indicacao' => [
        'cod' => 'uuid'
    ]
];
