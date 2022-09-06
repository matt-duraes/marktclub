<?php

return [
    [
        'id_admin_empresa' => 1,
        'permissao' => json_encode([
            'usuario_cliente' => ['titulo' => 'Cliente', 'acao' => ['index', 'add', 'editar', 'visualizar', 'deletar', 'download']],
            'usuario_grupo' => ['titulo' => 'Grupo', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'usuario_dependente' => ['titulo' => 'Dependente', 'acao' => ['index', 'add', 'deletar']],
            'usuario_indicacao' => ['titulo' => 'Indicação', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_lead' => ['titulo' => 'Lead', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_equipe' => ['titulo' => 'Equipe', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'relatorio' => ['titulo' => 'Relatório', 'permissao' => ['relatorio_acesso_index' => 'Relatório de acesso', 'relatorio_usuario_index' => 'Relatório de usuário']],
            'tabela' => ['titulo' => 'Tabela', 'permissao' => ['tabela_usuario_salvar' => 'Relatório de acesso', 'tabela_usuario_bloquear' => 'Relatório de usuário']],
            'ponto_cvs' => ['titulo' => 'Ponto+Ação', 'acao' => ['index', 'visualizar', 'editar']],
            'solicitacao_voucher' => ['titulo' => 'Voucher', 'acao' => ['index', 'visualizar']],
            'solicitacao_salavip' => ['titulo' => 'Salavip', 'acao' => ['index', 'download']],
            'api_app' => ['titulo' => 'API APP', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'api_usuario' => ['titulo' => 'API Usuário', 'acao' => ['index', 'add', 'editar', 'deletar']],
        ]),
        'configuracao' => ['agenda', 'perfil', 'bloquear'],
        'campo_permitido' => [
            'usuario_cliente' => [
                'geral' => [
                    'nome', 'cpf', 'matricula', 'siape', 'genero', 'estado_civil', 'data_nascimento', 'email_trabalho',
                    'email_pessoal', 'telefone_pessoal', 'telefone_trabalho', 'endereco_estado', 'endereco_cidade', 'senha',
                    'status', 'primeiro_acesso', 'mudar_senha', 'imagem', 'dependente', 'pagamento', 'data_criacao_de',
                    'data_criacao_ate', 'data_upload', 'grupo', 'trabalho_cargo', 'trabalho_empresa', 'tipo_pagamento',
                    'trabalho_data_inicio', 'grupo', 'origem'
                ],
                'download' => [
                    'status', 'tipo', 'rg', 'email_funcional', 'data_acesso', 'data_atualizacao', 'data_criacao',
                    'endereco_cidade', 'endereco_bairro', 'endereco_complemento', 'endereco_numero',
                    'endereco_logradouro', 'endereco_cep', 'endereco_estado', 'federacao', 'grupo',
                    'matricula', 'data_nascimento', 'genero', 'estado_civil', 'cpf', 'telefone_pessoal',
                    'telefone_trabalho', 'email_pessoal', 'email_trabalho', 'nome', 'siape', 'data_upload', 'origem'
                ]
            ]
        ],
        'campo_obrigatorio' => [
            'usuario_cliente' => ['cpf', 'email', 'status']
        ]
    ],
    [
        'id_admin_empresa' => 2,
        'permissao' => json_encode([
            'usuario_cliente' => ['titulo' => 'Cliente', 'acao' => ['index', 'add', 'editar', 'visualizar', 'download']],
            'usuario_indicacao' => ['titulo' => 'Indicação', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_lead' => ['titulo' => 'Lead', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_equipe' => ['titulo' => 'Equipe', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'relatorio' => ['titulo' => 'Relatório', 'permissao' => ['relatorio_acesso_index' => 'Relatório de acesso', 'relatorio_usuario_index' => 'Relatório de usuário']],
            'tabela' => ['titulo' => 'Tabela', 'permissao' => ['tabela_usuario_salvar' => 'Relatório de acesso', 'tabela_usuario_bloquear' => 'Relatório de usuário']],
            'solicitacao_voucher' => ['titulo' => 'Voucher', 'acao' => ['index', 'visualizar']],
            'solicitacao_salavip' => ['titulo' => 'Salavip', 'acao' => ['index', 'download']]
        ]),
        'configuracao' => ['perfil', 'bloquear'],
        'campo_permitido' => [
            'usuario_cliente' => [
                'geral' => [
                    'nome', 'cpf', 'matricula', 'siape', 'genero', 'estado_civil', 'data_nascimento', 'email_trabalho',
                    'email_pessoal', 'telefone_celular', 'telefone_fixo', 'endereco_estado', 'endereco_cidade', 'senha',
                    'status', 'primeiro_acesso', 'mudar_senha', 'data_upload'
                ],
                'filtrar' => [
                    'nome', 'cpf', 'matricula', 'siape', 'data_upload', 'data_criacao_de', 'data_criacao_ate', 'status'
                ]
            ]
        ],
        'campo_obrigatorio' => [
            'usuario_cliente' => ['matricula', 'email', 'status']
        ]
    ],
    [
        'id_admin_empresa' => 3,
        'permissao' => json_encode([
            'usuario_cliente' => ['titulo' => 'Cliente', 'acao' => ['index', 'add', 'editar', 'visualizar', 'download']],
            'usuario_indicacao' => ['titulo' => 'Indicação', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_lead' => ['titulo' => 'Lead', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_equipe' => ['titulo' => 'Equipe', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'relatorio' => ['titulo' => 'Relatório', 'permissao' => ['relatorio_acesso_index' => 'Relatório de acesso', 'relatorio_usuario_index' => 'Relatório de usuário']],
            'tabela' => ['titulo' => 'Tabela', 'permissao' => ['tabela_usuario_salvar' => 'Relatório de acesso', 'tabela_usuario_bloquear' => 'Relatório de usuário']],
            'solicitacao_voucher' => ['titulo' => 'Voucher', 'acao' => ['index', 'visualizar']],
        ]),
        'configuracao' => ['perfil', 'bloquear'],
        'campo_permitido' => [
            'usuario_cliente' => [
                'geral' => [
                    'nome', 'cpf', 'matricula', 'siape', 'genero', 'estado_civil', 'data_nascimento', 'email_trabalho',
                    'email_pessoal', 'telefone_celular', 'telefone_fixo', 'endereco_estado', 'endereco_cidade', 'senha',
                    'status', 'primeiro_acesso', 'mudar_senha', 'data_upload'
                ],
                'download' => [
                    'status', 'tipo', 'rg', 'email_funcional', 'data_acesso', 'data_atualizacao', 'data_criacao',
                    'endereco_cidade', 'endereco_bairro', 'endereco_complemento', 'endereco_numero',
                    'endereco_logradouro', 'endereco_cep', 'endereco_estado', 'federacao', 'grupo',
                    'matricula', 'data_nascimento', 'genero', 'estado_civil', 'cpf', 'telefone_pessoal',
                    'telefone_trabalho', 'email_pessoal', 'email_trabalho', 'nome', 'siape'
                ]
            ]
        ],
        'campo_obrigatorio' => [
            'usuario_cliente' => ['siape', 'email', 'status']
        ]
    ]
];
