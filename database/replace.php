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
        'cod' => 'uuid',
        'empresa' => 'id_admin_empresa',
        'site' => 'link_site'
    ],
    'tag_novo' => [
        'cod' => 'uuid'
    ],
    'contato' => [
        'cod' => 'uuid'
    ],
    'endereco_novo' => [
        'cod' => 'id_vinculo',
        'nome' => 'titulo'
    ],
    'solicitacao_voucher' => [
        'cod' => 'uuid'
    ],
    'usuario_novo' => [
        'cod' => 'uuid',
        'empresa' => 'id_admin_empresa',
        'titular' => 'id_usuario_cliente',
        'documento' => 'cpf',
        'sexo' => 'genero',
        'aniversario' => 'data_nascimento',
        'cidade' => 'endereco_cidade',
        'uf' => 'endereco_estado'
    ],
    'usuario_indicacao' => [
        'cod' => 'uuid'
    ]
];
