<?php

return [
    [
        'id_admin_empresa'  => 1,
        'permissao'         => [
            'usuario_cliente'         => [
                'titulo'    => 'Cliente',
                'permissao' => [
                    'usuario_cliente_index'      => 'Listar',
                    'usuario_cliente_add'        => 'Salvar',
                    'usuario_cliente_editar'     => 'Editar',
                    'usuario_cliente_visualizar' => 'Visualizar',
                    'usuario_cliente_deletar'    => 'Deletar',
                    'usuario_cliente_download'   => 'Download',
                    'usuario_cliente_empresa'    => 'Todas as empresas',
                    'usuario_cliente_analytics'  => 'Analytics',
                    'usuario_cliente_apple'      => 'Apple',
                ]
            ],
            'usuario_grupo'           => ['titulo' => 'Grupo', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'usuario_dependente'      => ['titulo' => 'Dependente', 'acao' => ['index', 'add', 'deletar']],
            'usuario_indicacao'       => ['titulo' => 'Indicação', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_lead'            => ['titulo' => 'Lead', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_equipe'          => [
                'titulo'    => 'Equipe',
                'permissao' => [
                    'usuario_equipe_index'     => 'Listar',
                    'usuario_equipe_add'       => 'Salvar',
                    'usuario_equipe_editar'    => 'Editar',
                    'usuario_equipe_deletar'   => 'Deletar',
                    'usuario_equipe_empresa'   => 'Todas as empresas',
                    'usuario_equipe_permissao' => 'Todas as permissões',
                ]
            ],
            'comunicacao_publicidade' => ['titulo' => 'Publicidade', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'solicitacao_contato'     => [
                'titulo'    => 'Contato',
                'acao'      => ['index', 'visualizar', 'status', 'empresa'],
                'permissao' => [
                    'solicitacao_contato_empresa' => 'Todas as empresas'
                ]
            ],
            'comunicacao_popup'       => [
                'titulo'    => 'Popup',
                'acao'      => ['index', 'add', 'editar', 'deletar', 'status', 'empresa'],
                'permissao' => [
                    'comunicacao_popup_empresa' => 'Todas as empresas'
                ]
            ],
            'enquete_satisfacao'      => [
                'titulo'    => 'Pesquisa Satisfação',
                'acao'      => ['index', 'visualizar', 'deletar', 'status', 'empresa'],
                'permissao' => [
                    'enquete_satisfacao_empresa' => 'Todas as empresas'
                ]
            ],
            'construtor_clube'        => ['titulo' => 'Clube', 'acao' => ['index', 'add', 'editar', 'deletar']],

            'publicacao_noticia'   => ['titulo' => 'Notícia', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'publicacao_pagina'    => ['titulo' => 'Páginas', 'acao' => ['index', 'editar']],
            'publicacao_diretoria' => ['titulo' => 'Diretoria', 'acao' => ['index', 'add', 'editar', 'deletar']],

            'texto_clube' => ['titulo' => 'Texto do clube', 'acao' => ['index', 'add', 'editar', 'deletar']],

            'parceiro_relatorio'       => [
                'titulo' => 'Relatório do parceiro', 'acao' => ['index', 'add', 'editar', 'deletar']
            ],
            'parceiro_cashback'        => [
                'titulo' => 'Cashback', 'acao' => ['index', 'add', 'editar', 'deletar', 'empresa']
            ],
            'parceiro_easylive'        => ['titulo' => 'Easylive', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'parceiro_automovel'       => [
                'titulo' => 'Automóvel', 'acao' => ['index', 'add', 'editar', 'visualizar', 'deletar']
            ],
            'relatorio_acesso'         => [
                'titulo'    => 'Relatório Acesso',
                'permissao' => [
                    'relatorio_acesso_index'   => 'Relatório de acesso',
                    'relatorio_acesso_empresa' => 'Todas as empresas'
                ]
            ],
            'relatorio_usuario'        => [
                'titulo'    => 'Relatório de usuário',
                'permissao' => [
                    'relatorio_usuario_index'   => 'Relatório de usuário',
                    'relatorio_usuario_empresa' => 'Todas as empresas'
                ]
            ],
            'relatorio_loja_venda'     => [
                'titulo'    => 'Relatório de vendas',
                'permissao' => [
                    'relatorio_loja_venda_index'   => 'Relatório de vendas',
                    'relatorio_loja_venda_empresa' => 'Todas as empresas'
                ]
            ],
            'tabela'                   => [
                'titulo'    => 'Tabela',
                'permissao' => [
                    'tabela_usuario_salvar'   => 'Cadastrar usuário',
                    'tabela_usuario_bloquear' => 'Bloquear usuário'
                ]
            ],
            'solicitacao_loja'         => [
                'titulo'    => 'Solicitação Loja',
                'acao'      => ['index', 'add', 'deletar', 'visualizar', 'status', 'empresa'],
                'permissao' => [
                    'solicitacao_loja_empresa' => 'Todas as empresas'
                ]
            ],
            'solicitacao_voucher'      => [
                'titulo'    => 'Voucher',
                'acao'      => ['index', 'visualizar', 'download', 'empresa'],
                'permissao' => [
                    'solicitacao_voucher_empresa' => 'Todas as empresas'
                ]
            ],
            'solicitacao_premium'      => [
                'titulo'    => 'Voucher Premium',
                'acao'      => ['index', 'visualizar', 'download', 'empresa'],
                'permissao' => [
                    'solicitacao_premium_empresa' => 'Todas as empresas'
                ]
            ],
            'solicitacao_salavip'      => [
                'titulo'    => 'Salavip',
                'acao'      => ['index', 'download', 'empresa'],
                'permissao' => [
                    'solicitacao_salavip_empresa' => 'Todas as empresas'
                ]
            ],
            'solicitacao_declaracao'   => [
                'titulo'    => 'Declaração',
                'acao'      => ['index', 'visualizar', 'status', 'empresa'],
                'permissao' => [
                    'solicitacao_declaracao_empresa' => 'Todas as empresas'
                ]
            ],
            'solicitacao_automovel'    => [
                'titulo'    => 'Automóvel',
                'acao'      => ['index', 'visualizar', 'status', 'empresa'],
                'permissao' => [
                    'solicitacao_automovel_empresa' => 'Todas as empresas'
                ]
            ],
            'solicitacao_cheque_bonus' => [
                'titulo'    => 'Cheque Bônus',
                'acao'      => ['index', 'visualizar', 'status', 'empresa'],
                'permissao' => [
                    'solicitacao_cheque_bonus_empresa' => 'Todas as empresas'
                ]
            ],
            'solicitacao_credito'      => [
                'titulo'    => 'Crédito',
                'acao'      => ['index', 'visualizar', 'status', 'empresa'],
                'permissao' => [
                    'solicitacao_credito_empresa' => 'Todas as empresas'
                ]
            ],
            'saude_contratacao'        => [
                'titulo'    => 'Saúde Contratação',
                'acao'      => ['index', 'visualizar', 'status', 'empresa'],
                'permissao' => [
                    'solicitacao_credito_empresa' => 'Todas as empresas'
                ]
            ],
            'comercial_empresa'        => [
                'titulo' => 'Comercial Empresa', 'acao' => ['index', 'visualizar', 'editar']
            ],
            'comercial_prospeccao'     => [
                'titulo' => 'Comercial Prospecção', 'acao' => ['index', 'add', 'editar', 'visualizar']
            ],
            'comercial_perdido'        => [
                'titulo' => 'Comercial Perdidos', 'acao' => ['index', 'visualizar', 'editar', 'status']
            ],
            'comercial_atendimento'    => [
                'titulo'    => 'Comercial Atendimento',
                'permissao' => ['comercial_atendimento_index' => 'Comercial Atendimento']
            ],
            'comercial_regra'          => [
                'titulo' => 'Comercial Regra de Negócio',
                'acao'   => ['index', 'add', 'visualizar', 'editar', 'deletar']
            ],
            'demanda'                  => [
                'titulo'    => 'Demanda',
                'permissao' => [
                    'demanda_tecnologia' => 'Tecnologia', 'demanda_criacao' => 'Criação'
                ]
            ],
            'log_erro'                 => [
                'titulo' => 'Log de erro', 'acao' => ['index', 'visualizar', 'status']
            ]
        ],
        'configuracao'      => ['agenda', 'perfil', 'bloquear'],
        'campo_permitido'   => [
            'usuario_cliente' => [
                'geral'    => [
                    'nome', 'cpf', 'matricula', 'siape', 'genero', 'estado_civil', 'data_nascimento', 'email_trabalho',
                    'email_pessoal', 'telefone_pessoal', 'telefone_trabalho', 'endereco_cidade', 'endereco_bairro',
                    'endereco_complemento', 'endereco_numero', 'tipo_pagamento', 'analytics', 'salavip',
                    'endereco_logradouro', 'endereco_cep', 'endereco_estado', 'senha',
                    'status', 'primeiro_acesso', 'mudar_senha', 'imagem', 'dependente', 'pagamento', 'data_criacao_de',
                    'data_criacao_ate', 'data_criacao', 'data_upload', 'grupo', 'trabalho_cargo', 'trabalho_empresa',
                    'trabalho_data_inicio', 'grupo', 'lead', 'origem', 'tipo', 'empresa', 'subempresa', 'federacao'
                ],
                'download' => [
                    'status', 'rg', 'email_funcional', 'data_acesso', 'data_atualizacao', 'data_criacao',
                    'endereco_cidade', 'endereco_bairro', 'endereco_complemento', 'endereco_numero',
                    'endereco_logradouro', 'endereco_cep', 'endereco_estado', 'federacao', 'grupo',
                    'matricula', 'data_nascimento', 'genero', 'estado_civil', 'cpf', 'telefone_pessoal',
                    'telefone_trabalho', 'email_pessoal', 'email_trabalho', 'nome', 'siape', 'data_upload',
                    'lead', 'origem', 'empresa', 'subempresa', 'federacao', 'trabalho_empresa', 'trabalho_cargo',
                    'tipo_pagamento'
                ]
            ],
            'usuario_equipe'  => [
                'geral' => [
                    'nome', 'cpf', 'genero', 'data_nascimento', 'email_trabalho', 'senha', 'status', 'primeiro_acesso',
                    'email_trabalho', 'email_pessoal', 'telefone_trabalho', 'telefone_pessoal', 'mudar_senha',
                    'permissao',
                    'empresa', 'subempresa'
                ]
            ]
        ],
        'campo_obrigatorio' => [
            'usuario_cliente' => ['cpf', 'email', 'status']
        ],
        'upload_grupo'      => [
            'imagem'  => 'e53ae4e0-7b33-4988-99ad-50433a29b544',
            'arquivo' => '2d978fba-4bd2-4af7-80bf-ebb94d9ac991'
        ]
    ],
    [
        'id_admin_empresa'  => 2,
        'permissao'         => json_encode([
            'usuario_cliente'     => [
                'titulo' => 'Cliente', 'acao' => ['index', 'add', 'editar', 'visualizar', 'download']
            ],
            'usuario_indicacao'   => ['titulo' => 'Indicação', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_lead'        => ['titulo' => 'Lead', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_equipe'      => ['titulo' => 'Equipe', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'relatorio'           => [
                'titulo' => 'Relatório', 'permissao' => [
                    'relatorio_acesso_index'  => 'Relatório de acesso',
                    'relatorio_usuario_index' => 'Relatório de usuário'
                ]
            ],
            'tabela'              => [
                'titulo' => 'Tabela', 'permissao' => [
                    'tabela_usuario_salvar'   => 'Relatório de acesso',
                    'tabela_usuario_bloquear' => 'Relatório de usuário'
                ]
            ],
            'solicitacao_voucher' => ['titulo' => 'Voucher', 'acao' => ['index', 'visualizar']],
            'solicitacao_salavip' => ['titulo' => 'Salavip', 'acao' => ['index', 'download']]
        ]),
        'configuracao'      => ['perfil', 'bloquear'],
        'campo_permitido'   => [
            'usuario_cliente' => [
                'geral'   => [
                    'nome', 'cpf', 'matricula', 'siape', 'genero', 'estado_civil', 'data_nascimento', 'email_trabalho',
                    'email_pessoal', 'telefone_celular', 'telefone_fixo', 'endereco_estado', 'endereco_cidade', 'senha',
                    'status', 'primeiro_acesso', 'mudar_senha', 'data_upload', 'subempresa', 'grupo'
                ],
                'filtrar' => [
                    'nome', 'cpf', 'matricula', 'siape', 'data_upload', 'data_criacao_de', 'data_criacao_ate', 'status'
                ]
            ],
            'usuario_equipe'  => [
                'geral' => [
                    'nome', 'cpf', 'genero', 'data_nascimento', 'email_trabalho', 'senha', 'status', 'primeiro_acesso',
                    'email_trabalho', 'email_pessoal', 'telefone_trabalho', 'telefone_pessoal', 'mudar_senha',
                    'permissao',
                    'subempresa'
                ]
            ]
        ],
        'campo_obrigatorio' => [
            'usuario_cliente' => ['matricula', 'email', 'status']
        ]
    ],
    [
        'id_admin_empresa'  => 3,
        'permissao'         => json_encode([
            'usuario_cliente'     => [
                'titulo' => 'Cliente', 'acao' => ['index', 'add', 'editar', 'visualizar', 'download']
            ],
            'usuario_indicacao'   => ['titulo' => 'Indicação', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_lead'        => ['titulo' => 'Lead', 'acao' => ['index', 'visualizar', 'status']],
            'usuario_equipe'      => ['titulo' => 'Equipe', 'acao' => ['index', 'add', 'editar', 'deletar']],
            'relatorio'           => [
                'titulo' => 'Relatório', 'permissao' => [
                    'relatorio_acesso_index'  => 'Relatório de acesso',
                    'relatorio_usuario_index' => 'Relatório de usuário'
                ]
            ],
            'tabela'              => [
                'titulo' => 'Tabela', 'permissao' => [
                    'tabela_usuario_salvar'   => 'Relatório de acesso',
                    'tabela_usuario_bloquear' => 'Relatório de usuário'
                ]
            ],
            'solicitacao_voucher' => ['titulo' => 'Voucher', 'acao' => ['index', 'visualizar']],
        ]),
        'configuracao'      => ['perfil', 'bloquear'],
        'campo_permitido'   => [
            'usuario_cliente' => [
                'geral'    => [
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
