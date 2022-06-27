<?php

return [
    [
        'id_admin_empresa' => 1,
        'tipo' => 1,
        'nome_real' => 'Usuário CPF obrigatório',
        'email_trabalho' => 'cpf@teste.com',
        'telefone_trabalho' => telefoneAleatorio(),
        'documento_cpf' => '00000000001',
        'salt' => password('123456'),
        'permissao' => [
            'tabela_usuario_salvar', 'tabela_usuario_bloquear',
        ],
        'status' => 1,
    ],
    [
        'id_admin_empresa' => 2,
        'tipo' => 1,
        'nome_real' => 'Usuario Matrícula obrigatório',
        'email_trabalho' => 'matricula@teste.com',
        'telefone_trabalho' => telefoneAleatorio(),
        'documento_cpf' => '00000000002',
        'salt' => password('123456'),
        'permissao' => [
            'relatorio_usuario_index', 'relatorio_acesso_index',
            'solicitacao_voucher_index', 'solicitacao_voucher_visualizar'
        ],
        'status' => 1,
    ],
    [
        'id_admin_empresa' => 3,
        'tipo' => 1,
        'nome_real' => 'Usuario Matrícula obrigatório',
        'email_trabalho' => 'siape@teste.com',
        'telefone_trabalho' => telefoneAleatorio(),
        'documento_cpf' => '00000000004',
        'salt' => password('123456'),
        'permissao' => [
            'usuario_cliente_index', 'usuario_cliente_add', 'usuario_cliente_visualizar', 'usuario_cliente_download', 'usuario_cliente_editar', 'usuario_cliente_deletar',
        ],
        'status' => 1,
    ],
    [
        'id_admin_empresa' => 4,
        'tipo' => 1,
        'nome_real' => 'Download usuário',
        'email_trabalho' => 'download@teste.com',
        'telefone_trabalho' => telefoneAleatorio(),
        'documento_cpf' => '00000000005',
        'salt' => password('123456'),
        'permissao' => [
            'usuario_cliente_index', 'usuario_cliente_add', 'usuario_cliente_visualizar', 'usuario_cliente_editar',
            'usuario_equipe_index', 'usuario_equipe_add', 'usuario_equipe_editar', 'usuario_equipe_deletar',
            'usuario_lead_index', 'usuario_lead_visualizar', 'usuario_indicacao_status',
            'usuario_indicacao_index', 'usuario_indicacao_visualizar', 'usuario_indicacao_status',
        ],
        'status' => 1,
    ],
    [
        'id_admin_empresa' => 1,
        'tipo' => 3,
        'nome_real' => 'André Rodrigues',
        'email_trabalho' => 'andrerodrigues@andrerodrigues.com',
        'telefone_pessoal' => '61981777773',
        'documento_cpf' => '01495180131',
        'salt' => password('123456'),
        'permissao' => json_encode([
            'usuario_cliente_index', 'usuario_cliente_add', 'usuario_cliente_visualizar', 'usuario_cliente_download', 'usuario_cliente_editar', 'usuario_cliente_deletar',
            'usuario_dependente_index', 'usuario_dependente_add', 'usuario_cliente_deletar',
            'usuario_equipe_index', 'usuario_equipe_add', 'usuario_equipe_editar', 'usuario_equipe_deletar',
            'usuario_lead_index', 'usuario_lead_visualizar', 'usuario_indicacao_status',
            'usuario_indicacao_index', 'usuario_indicacao_visualizar', 'usuario_indicacao_status',
            'tabela_usuario_salvar', 'tabela_usuario_bloquear',
            'relatorio_usuario_index', 'relatorio_acesso_index',
            'solicitacao_voucher_index', 'solicitacao_voucher_visualizar',
            'solicitacao_salavip_index', 'solicitacao_salavip_download'
        ]),
        'primeiro_acesso' => null,
        'mudar_senha' => null,
        'desenvolvedor' => 1,
        'admin' => 1,
        'status' => 1,
    ]
];
