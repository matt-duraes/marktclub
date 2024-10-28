<?php

return [
    'api_token' => [
        'token_acesso' => 'access_token'
    ],
    'empresa_novo' => [
        'cod' => 'uuid'
    ],
    'cupom_bloqueio' => [
        'cod' => 'uuid'
    ],
    'tag_novo' => [
        'cod' => 'uuid',
        'menu' => 'clube'
    ],
    'solicitacao_cheque_bonus' => [
        'cod' => 'uuid',
        'dependente_documento' => 'dependente_cpf'
    ],
    'solicitacao_declaracao' => [
        'cod' => 'uuid',
        'empresa' => 'id_admin_empresa',
        'usuario' => 'id_usuario_cliente',
        'vinculo' => 'id_parceiro_loja'
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
        'uf' => 'endereco_estado',
        'hash' => 'hash_valor'
    ],
    'usuario_indicacao' => [
        'cod' => 'uuid'
    ]
];
