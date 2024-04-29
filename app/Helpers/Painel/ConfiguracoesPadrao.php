<?php

namespace App\Helpers\Painel;

final class ConfiguracoesPadrao
{
    public const RECURSOS = [
        'perfil'   => 'Perfil',
        'agenda'   => 'Agenda',
        'google'   => 'Google',
        'darkMode' => 'Dark Mode (ALPHA)'
    ];
    public const CAMPOS_OBRIGATORIOS = [
        'usuario_cliente' => [
            'nome', 'cpf', 'email', 'status'
        ]
    ];
    public const UPLOAD_GRUPO = [
        'imagem'      => 'e53ae4e0-7b33-4988-99ad-50433a29b544',
        'arquivo'     => '2d978fba-4bd2-4af7-80bf-ebb94d9ac991',
        'site_config' => '93bb55cb-56fb-4d42-af81-c8100b229348'
    ];
    public const CAMPOS_PERMITIDOS = [
        'usuario_cliente' => [
            'titulo'   => 'Usuário Cliente',
            'recursos' => [
                'geral'    => [
                    'nome'                 => 'Nome',
                    'cpf'                  => 'CPF',
                    'matricula'            => 'Matrícula',
                    'siape'                => 'SIAPE',
                    'genero'               => 'Gênero',
                    'estado_civil'         => 'Estado Civil',
                    'data_nascimento'      => 'Data de Nascimento',
                    'email_trabalho'       => 'E-mail de Trabalho',
                    'email_pessoal'        => 'E-mail de Pessoal',
                    'telefone_pessoal'     => 'Telefone Pessoal',
                    'telefone_trabalho'    => 'Telefone Trabalho',
                    'endereco_cidade'      => 'Cidade',
                    'endereco_bairro'      => 'Bairro',
                    'endereco_complemento' => 'Complemento',
                    'endereco_numero'      => 'Número Residencial/Lote',
                    'tipo_pagamento'       => 'Metódo de Pagamento',
                    'analytics'            => 'Analytics',
                    'salavip'              => 'Sala VIP',
                    'endereco_logradouro'  => 'Logradouro',
                    'endereco_cep'         => 'CEP',
                    'endereco_estado'      => 'Estado',
                    'senha'                => 'Senha',
                    'status'               => 'Status',
                    'primeiro_acesso'      => 'Primeiro Acesso',
                    'mudar_senha'          => 'Mudar Senha',
                    'imagem'               => 'Imagem',
                    'dependente'           => 'Dependente',
                    'pagamento'            => 'Pagamento',
                    'data_criacao_de'      => 'Data de Criação de',
                    'data_criacao_ate'     => 'Data de Criação Até',
                    'data_criacao'         => 'Data de Criação',
                    'data_upload'          => 'Data de Upload',
                    'grupo'                => 'Grupo',
                    'trabalho_cargo'       => 'Trabalho no Cargo',
                    'trabalho_empresa'     => 'Trabalho na Empresa',
                    'trabalho_data_inicio' => 'Data de Início do Trabalho',
                    'lead'                 => 'Lead',
                    'origem'               => 'Origem',
                    'tipo'                 => 'Tipo',
                    'empresa'              => 'Empresa',
                    'subempresa'           => 'Sub Empresa',
                    'federacao'            => 'Federação'
                ],
                'download' => [
                    'nome'                 => 'Nome',
                    'cpf'                  => 'CPF',
                    'rg'                   => 'RG',
                    'siape'                => 'SIAPE',
                    'matricula'            => 'Matrícula',
                    'data_nascimento'      => 'Data de Nascimento',
                    'genero'               => 'Gênero',
                    'estado_civil'         => 'Estado Civil',
                    'telefone_pessoal'     => 'Telefone Pessoal',
                    'telefone_trabalho'    => 'Telefone de Trabalho',
                    'email_pessoal'        => 'E-mail Pessoal',
                    'email_trabalho'       => 'E-mail de Trabalho',
                    'email_funcional'      => 'E-mail Funcional',
                    'endereco_cidade'      => 'Cidade',
                    'endereco_bairro'      => 'Bairro',
                    'endereco_complemento' => 'Complemento',
                    'endereco_numero'      => 'Número Residencial/Lote',
                    'endereco_logradouro'  => 'Logradouro',
                    'endereco_cep'         => 'CEP',
                    'endereco_estado'      => 'Estado',
                    'empresa'              => 'Empresa',
                    'subempresa'           => 'Sub Empresa',
                    'federacao'            => 'Federação',
                    'trabalho_empresa'     => 'Trabalho na Empresa',
                    'trabalho_cargo'       => 'Trabalho no Cargo',
                    'tipo_pagamento'       => 'Metódo de Pagamento',
                    'grupo'                => 'Grupo',
                    'lead'                 => 'Lead',
                    'origem'               => 'Origem',
                    'data_acesso'          => 'Data de Acesso',
                    'data_upload'          => 'Data de Upload',
                    'data_criacao'         => 'Data de Criação',
                    'data_atualizacao'     => 'Data de Atualização',
                    'status'               => 'Status'
                ]
            ]
        ],
        'usuario_equipe'  => [
            'titulo'   => 'Usuário Equipe',
            'recursos' => [
                'geral' => [
                    'nome'                 => 'Nome',
                    'cpf'                  => 'CPF',
                    'rg'                   => 'RG',
                    'siape'                => 'SIAPE',
                    'matricula'            => 'Matrícula',
                    'data_nascimento'      => 'Data de Nascimento',
                    'genero'               => 'Gênero',
                    'estado_civil'         => 'Estado Civil',
                    'telefone_pessoal'     => 'Telefone Pessoal',
                    'telefone_trabalho'    => 'Telefone de Trabalho',
                    'email_pessoal'        => 'E-mail Pessoal',
                    'email_trabalho'       => 'E-mail de Trabalho',
                    'email_funcional'      => 'E-mail Funcional',
                    'endereco_cidade'      => 'Cidade',
                    'endereco_bairro'      => 'Bairro',
                    'endereco_complemento' => 'Complemento',
                    'endereco_numero'      => 'Número Residencial/Lote',
                    'endereco_logradouro'  => 'Logradouro',
                    'endereco_cep'         => 'CEP',
                    'endereco_estado'      => 'Estado',
                    'empresa'              => 'Empresa',
                    'subempresa'           => 'Sub Empresa',
                    'federacao'            => 'Federação',
                    'trabalho_empresa'     => 'Trabalho na Empresa',
                    'trabalho_cargo'       => 'Trabalho no Cargo',
                    'tipo_pagamento'       => 'Metódo de Pagamento',
                    'grupo'                => 'Grupo',
                    'lead'                 => 'Lead',
                    'origem'               => 'Origem',
                    'senha'                => 'Senha',
                    'mudar_senha'          => 'Mudar Senha',
                    'primeiro_acesso'      => 'Primeito Acesso',
                    'permissao'            => 'Permissão',
                    'data_acesso'          => 'Data de Acesso',
                    'data_upload'          => 'Data de Upload',
                    'data_criacao'         => 'Data de Criação',
                    'data_atualizacao'     => 'Data de Atualização',
                    'status'               => 'Status'
                ]
            ]
        ]
    ];
    public const PERMISSOES = [
        'usuario_cliente'          => [
            'titulo'    => 'Usuário Cliente',
            'acao'      => [
                'index', 'visualizar', 'add', 'editar', 'deletar', 'download', 'analytics', 'apple', 'empresa'
            ],
            'permissao' => [
                'usuario_cliente_index'      => 'Listar',
                'usuario_cliente_visualizar' => 'Visualizar',
                'usuario_cliente_add'        => 'Salvar',
                'usuario_cliente_editar'     => 'Editar',
                'usuario_cliente_deletar'    => 'Deletar',
                'usuario_cliente_download'   => 'Download',
                'usuario_cliente_analytics'  => 'Analytics',
                'usuario_cliente_apple'      => 'Apple',
                'usuario_cliente_empresa'    => 'Todas as empresas'
            ]
        ],
        'usuario_grupo'            => [
            'titulo'    => 'Usuário Grupo',
            'acao'      => [
                'index', 'add', 'editar', 'deletar'
            ],
            'permissao' => [
                'usuario_grupo_index'   => 'Listar',
                'usuario_grupo_add'     => 'Salvar',
                'usuario_grupo_editar'  => 'Editar',
                'usuario_grupo_deletar' => 'Deletar'
            ]
        ],
        'usuario_dependente'       => [
            'titulo'    => 'Usuário Dependente',
            'acao'      => [
                'index', 'add', 'editar', 'deletar'
            ],
            'permissao' => [
                'usuario_dependente_index'   => 'Listar',
                'usuario_dependente_add'     => 'Salvar',
                'usuario_dependente_editar'  => 'Editar',
                'usuario_dependente_deletar' => 'Deletar'
            ]
        ],
        'usuario_indicacao'        => [
            'titulo'    => 'Usuário Indicação',
            'acao'      => [
                'index', 'visualizar', 'status', 'empresa'
            ],
            'permissao' => [
                'usuario_indicacao_index'      => 'Listar',
                'usuario_indicacao_visualizar' => 'Visualizar',
                'usuario_indicacao_status'     => 'Status',
                'usuario_indicacao_empresa'    => 'Todas as empresas'
            ]
        ],
        'usuario_lead'             => [
            'titulo'    => 'Usuário Lead',
            'acao'      => [
                'index', 'visualizar', 'status', 'empresa'
            ],
            'permissao' => [
                'usuario_lead_index'      => 'Listar',
                'usuario_lead_visualizar' => 'Visualizar',
                'usuario_lead_status'     => 'Status',
                'usuario_lead_empresa'    => 'Todas as empresas'
            ]
        ],
        'usuario_equipe'           => [
            'titulo'    => 'Usuário Equipe',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'permissao', 'empresa'
            ],
            'permissao' => [
                'usuario_equipe_index'     => 'Listar',
                'usuario_equipe_add'       => 'Salvar',
                'usuario_equipe_editar'    => 'Editar',
                'usuario_equipe_deletar'   => 'Deletar',
                'usuario_equipe_permissao' => 'Todas as permissões',
                'usuario_equipe_empresa'   => 'Todas as empresas'
            ]
        ],
        'comunicacao_login'        => [
            'titulo'    => 'Banners de Login',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'comunicacao_login_index'   => 'Listar',
                'comunicacao_login_add'     => 'Salvar',
                'comunicacao_login_editar'  => 'Editar',
                'comunicacao_login_deletar' => 'Deletar',
                'comunicacao_login_empresa' => 'Todas as empresas'
            ]
        ],
        'comunicacao_publicidade'  => [
            'titulo'    => 'Publicidade',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'comunicacao_publicidade_index'   => 'Listar',
                'comunicacao_publicidade_add'     => 'Salvar',
                'comunicacao_publicidade_editar'  => 'Editar',
                'comunicacao_publicidade_deletar' => 'Deletar',
                'comunicacao_publicidade_empresa' => 'Todas as empresas'
            ]
        ],
        'solicitacao_contato'      => [
            'titulo'    => 'Solicitação Contato',
            'acao'      => [
                'index', 'visualizar', 'status', 'empresa'
            ],
            'permissao' => [
                'solicitacao_contato_index'      => 'Listar',
                'solicitacao_contato_visualizar' => 'Visualizar',
                'solicitacao_contato_status'     => 'Status',
                'solicitacao_contato_empresa'    => 'Todas as empresas'
            ]
        ],
        'comercial_popup'          => [
            'titulo'    => 'Popup',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'comercial_popup_index'   => 'Listar',
                'comercial_popup_add'     => 'Salvar',
                'comercial_popup_editar'  => 'Editar',
                'comercial_popup_deletar' => 'Deletar',
                'comercial_popup_empresa' => 'Todas as empresas'
            ]
        ],
        'enquete_satisfacao'       => [
            'titulo'    => 'Pesquisa Satisfação',
            'acao'      => [
                'index', 'visualizar', 'deletar', 'status', 'empresa'
            ],
            'permissao' => [
                'enquete_satisfacao_index'      => 'Listar',
                'enquete_satisfacao_visualizar' => 'Visualizar',
                'enquete_satisfacao_deletar'    => 'Deletar',
                'enquete_satisfacao_status'     => 'Status',
                'enquete_satisfacao_empresa'    => 'Todas as empresas'
            ]
        ],
        'construtor_clube'         => [
            'titulo'    => 'Construtor Clube',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'status', 'empresa'
            ],
            'permissao' => [
                'construtor_clube_index'   => 'Listar',
                'construtor_clube_add'     => 'Salvar',
                'construtor_clube_editar'  => 'Editar',
                'construtor_clube_deletar' => 'Deletar',
                'construtor_clube_empresa' => 'Todas as empresas'
            ]
        ],
        'publicacao_noticia'       => [
            'titulo'    => 'Notícias',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'publicacao_noticia_index'   => 'Listar',
                'publicacao_noticia_add'     => 'Salvar',
                'publicacao_noticia_editar'  => 'Editar',
                'publicacao_noticia_deletar' => 'Deletar',
                'publicacao_noticia_empresa' => 'Todas as empresas'
            ]
        ],
        'publicacao_lista'       => [
            'titulo'    => 'Lista geral',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'publicacao_lista_index'   => 'Listar',
                'publicacao_lista_add'     => 'Salvar',
                'publicacao_lista_editar'  => 'Editar',
                'publicacao_lista_deletar' => 'Deletar',
                'publicacao_lista_empresa' => 'Todas as empresas',
            ]
        ],
        'publicacao_home'          => [
            'titulo'    => 'Notícia da Home',
            'acao'      => [
                'editar'
            ],
            'permissao' => [
                'publicacao_home_editar' => 'Editar'
            ]
        ],
        'publicacao_pagina'        => [
            'titulo'    => 'Páginas',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'publicacao_pagina_index'   => 'Listar',
                'publicacao_pagina_add'     => 'Salvar',
                'publicacao_pagina_editar'  => 'Editar',
                'publicacao_pagina_deletar' => 'Deletar',
                'publicacao_pagina_empresa' => 'Todas as empresas'
            ]
        ],
        'publicacao_youtube'       => [
            'titulo'    => 'Youtube',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'publicacao_youtube_index'   => 'Listar',
                'publicacao_youtube_add'     => 'Salvar',
                'publicacao_youtube_editar'  => 'Editar',
                'publicacao_youtube_deletar' => 'Deletar',
                'publicacao_youtube_empresa' => 'Todas as empresas'
            ]
        ],
        'publicacao_arquivo'       => [
            'titulo'    => 'Arquivo',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'publicacao_arquivo_index'   => 'Listar',
                'publicacao_arquivo_add'     => 'Salvar',
                'publicacao_arquivo_editar'  => 'Editar',
                'publicacao_arquivo_deletar' => 'Deletar',
                'publicacao_arquivo_empresa' => 'Todas as empresas'
            ]
        ],
        'publicacao_diretoria'     => [
            'titulo'    => 'Diretoria',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'publicacao_diretoria_index'   => 'Listar',
                'publicacao_diretoria_add'     => 'Salvar',
                'publicacao_diretoria_editar'  => 'Editar',
                'publicacao_diretoria_deletar' => 'Deletar',
                'publicacao_diretoria_empresa' => 'Todas as empresas'
            ]
        ],
        'texto_clube'              => [
            'titulo'    => 'Texto do clube',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'texto_clube_index'   => 'Listar',
                'texto_clube_add'     => 'Salvar',
                'texto_clube_editar'  => 'Editar',
                'texto_clube_deletar' => 'Deletar',
                'texto_clube_empresa' => 'Todas as empresas'
            ]
        ],
        'parceiro_relatorio'       => [
            'titulo'    => 'Relatório do parceiro',
            'acao'      => [
                'index', 'add', 'editar', 'deletar'
            ],
            'permissao' => [
                'parceiro_relatorio_index'   => 'Listar',
                'parceiro_relatorio_add'     => 'Salvar',
                'parceiro_relatorio_editar'  => 'Editar',
                'parceiro_relatorio_deletar' => 'Deletar'
            ]
        ],
        'parceiro_loja'            => [
            'titulo'    => 'Loja',
            'acao'      => [
                'index', 'add', 'visualizar', 'editar', 'deletar', 'status', 'empresa'
            ],
            'permissao' => [
                'parceiro_loja_index'      => 'Listar',
                'parceiro_loja_add'        => 'Salvar',
                'parceiro_loja_visualizar' => 'Visualizar',
                'parceiro_loja_editar'     => 'Editar',
                'parceiro_loja_deletar'    => 'Deletar',
                'parceiro_loja_status'     => 'Status',
                'parceiro_loja_empresa'    => 'Todas as empresas'
            ]
        ],
        'parceiro_cupom'           => [
            'titulo'    => 'Cupom',
            'acao'      => [
                'index', 'status'
            ],
            'permissao' => [
                'parceiro_cupom_index'  => 'Listar',
                'parceiro_cupom_status' => 'Status'
            ]
        ],
        'parceiro_easylive'        => [
            'titulo'    => 'Easylive',
            'acao'      => [
                'index', 'add', 'editar', 'deletar'
            ],
            'permissao' => [
                'parceiro_easylive_index'   => 'Listar',
                'parceiro_easylive_add'     => 'Salvar',
                'parceiro_easylive_editar'  => 'Editar',
                'parceiro_easylive_deletar' => 'Deletar'
            ]
        ],
        'parceiro_automovel'       => [
            'titulo'    => 'Automóvel',
            'acao'      => [
                'index', 'visualizar', 'add', 'editar', 'deletar'
            ],
            'permissao' => [
                'parceiro_automovel_index'      => 'Listar',
                'parceiro_automovel_visualizar' => 'Visualizar',
                'parceiro_automovel_add'        => 'Salvar',
                'parceiro_automovel_editar'     => 'Editar',
                'parceiro_automovel_deletar'    => 'Deletar'
            ]
        ],
        'relatorio_acesso'         => [
            'titulo'    => 'Relatório Acesso',
            'acao'      => [
                'index', 'empresa'
            ],
            'permissao' => [
                'relatorio_acesso_index'   => 'Relatório de acesso',
                'relatorio_acesso_empresa' => 'Todas as empresas'
            ]
        ],
        'relatorio_usuario'        => [
            'titulo'    => 'Relatório de usuário',
            'acao'      => [
                'index', 'empresa'
            ],
            'permissao' => [
                'relatorio_usuario_index'   => 'Relatório de usuário',
                'relatorio_usuario_empresa' => 'Todas as empresas'
            ]
        ],
        'relatorio_loja_venda'     => [
            'titulo'    => 'Relatório de vendas',
            'acao'      => [
                'index', 'empresa', 'parceiro'
            ],
            'permissao' => [
                'relatorio_loja_venda_index'    => 'Relatório de vendas',
                'relatorio_loja_venda_empresa'  => 'Todas as empresas',
                'relatorio_loja_venda_parceiro' => 'Todos os parceiros'
            ]
        ],
        'tabela_usuario'           => [
            'titulo'    => 'Tabela de Usuário',
            'permissao' => [
                'tabela_usuario_salvar'   => 'Salvar',
                'tabela_usuario_bloquear' => 'Bloquear',
                'tabela_historico_index'  => 'Histórico',
                'tabela_usuario_empresa'  => 'Todas as empresas'
            ]
        ],
        'solicitacao_loja'         => [
            'titulo'    => 'Solicitação Loja',
            'acao'      => [
                'index', 'visualizar', 'add', 'deletar', 'status', 'empresa'
            ],
            'permissao' => [
                'solicitacao_loja_index'      => 'Listar',
                'solicitacao_loja_visualizar' => 'Visualizar',
                'solicitacao_loja_add'        => 'Salvar',
                'solicitacao_loja_deletar'    => 'Deletar',
                'solicitacao_loja_status'     => 'Status',
                'solicitacao_loja_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_voucher'      => [
            'titulo'    => 'Solicitação Voucher',
            'acao'      => [
                'index', 'visualizar', 'download', 'empresa'
            ],
            'permissao' => [
                'solicitacao_voucher_index'      => 'Listar',
                'solicitacao_voucher_visualizar' => 'Visualizar',
                'solicitacao_voucher_download'   => 'Download',
                'solicitacao_voucher_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_premium'      => [
            'titulo'    => 'Solicitacação Voucher Premium',
            'acao'      => [
                'index', 'visualizar', 'download', 'empresa'
            ],
            'permissao' => [
                'solicitacao_premium_index'      => 'Listar',
                'solicitacao_premium_visualizar' => 'Visualizar',
                'solicitacao_premium_download'   => 'Download',
                'solicitacao_premium_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_salavip'      => [
            'titulo'    => 'Solicitação Salavip',
            'acao'      => [
                'index', 'download', 'empresa'
            ],
            'permissao' => [
                'solicitacao_salavip_index'    => 'Listar',
                'solicitacao_salavip_download' => 'Download',
                'solicitacao_salavip_empresa'  => 'Todas as empresas'
            ]
        ],
        'solicitacao_declaracao'   => [
            'titulo'    => 'Solicitação Declaração',
            'acao'      => [
                'index', 'visualizar', 'status', 'empresa'
            ],
            'permissao' => [
                'solicitacao_declaracao_index'      => 'Listar',
                'solicitacao_declaracao_visualizar' => 'Visualizar',
                'solicitacao_declaracao_status'     => 'Status',
                'solicitacao_declaracao_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_automovel'    => [
            'titulo'    => 'Solicitação Automóvel',
            'acao'      => [
                'index', 'visualizar', 'status', 'empresa'
            ],
            'permissao' => [
                'solicitacao_automovel_index'      => 'Listar',
                'solicitacao_automovel_visualizar' => 'Visualizar',
                'solicitacao_automovel_status'     => 'Status',
                'solicitacao_automovel_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_cheque_bonus' => [
            'titulo'    => 'Solicitação Cheque Bônus',
            'acao'      => [
                'index', 'visualizar', 'status', 'empresa'
            ],
            'permissao' => [
                'solicitacao_cheque_bonus_index'      => 'Listar',
                'solicitacao_cheque_bonus_visualizar' => 'Visualizar',
                'solicitacao_cheque_bonus_status'     => 'Status',
                'solicitacao_cheque_bonus_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_credito'      => [
            'titulo'    => 'Solicitação Crédito',
            'acao'      => [
                'index', 'visualizar', 'status', 'empresa'
            ],
            'permissao' => [
                'solicitacao_credito_index'      => 'Listar',
                'solicitacao_credito_visualizar' => 'Visualizar',
                'solicitacao_credito_status'     => 'Status',
                'solicitacao_credito_empresa'    => 'Todas as empresas'
            ]
        ],
        'saude_contratacao'        => [
            'titulo'    => 'Saúde Contratação',
            'acao'      => [
                'index', 'visualizar', 'status', 'empresa'
            ],
            'permissao' => [
                'saude_contratacao_index'      => 'Listar',
                'saude_contratacao_visualizar' => 'Visualizar',
                'saude_contratacao_status'     => 'Status',
                'saude_contratacao_empresa'    => 'Todas as empresas'
            ]
        ],
        'comercial_empresa'        => [
            'titulo'    => 'Comercial Empresa',
            'acao'      => [
                'index', 'visualizar', 'editar'
            ],
            'permissao' => [
                'comercial_empresa_index'      => 'Listar',
                'comercial_empresa_visualizar' => 'Visualizar',
                'comercial_empresa_editar'     => 'Editar'
            ]
        ],
        'comercial_subempresa'     => [
            'titulo'    => 'Comercial Subempresa',
            'acao'      => [
                'index', 'visualizar', 'add', 'editar', 'deletar', 'status', 'empresa'
            ],
            'permissao' => [
                'comercial_subempresa_index'      => 'Listar',
                'comercial_subempresa_visualizar' => 'Visualizar',
                'comercial_subempresa_add'        => 'Salvar',
                'comercial_subempresa_editar'     => 'Editar',
                'comercial_subempresa_deletar'    => 'Deletar',
                'comercial_subempresa_empresa'    => 'Todas as empresas'
            ]
        ],
        'comercial_prospeccao'     => [
            'titulo'    => 'Comercial Prospecção',
            'acao'      => [
                'index', 'visualizar', 'add', 'editar'
            ],
            'permissao' => [
                'comercial_prospeccao_index'      => 'Listar',
                'comercial_prospeccao_visualizar' => 'Visualizar',
                'comercial_prospeccao_add'        => 'Salvar',
                'comercial_prospeccao_editar'     => 'Editar'
            ]
        ],
        'comercial_perdido'        => [
            'titulo'    => 'Comercial Perdidos',
            'acao'      => [
                'index', 'visualizar', 'editar', 'status'
            ],
            'permissao' => [
                'comercial_perdido_index'      => 'Listar',
                'comercial_perdido_visualizar' => 'Visualizar',
                'comercial_perdido_editar'     => 'Editar',
                'comercial_perdido_status'     => 'Status'
            ]
        ],
        'comercial_atendimento'    => [
            'titulo'    => 'Comercial Atendimento',
            'acao'      => [
                'index'
            ],
            'permissao' => [
                'comercial_atendimento_index' => 'Comercial Atendimento'
            ]
        ],
        'comercial_regra'          => [
            'titulo'    => 'Comercial Regra de Negócio',
            'acao'      => [
                'index', 'visualizar', 'add', 'editar', 'deletar'
            ],
            'permissao' => [
                'comercial_regra_index'      => 'Listar',
                'comercial_regra_visualizar' => 'Visualizar',
                'comercial_regra_add'        => 'Salvar',
                'comercial_regra_editar'     => 'Editar',
                'comercial_regra_deletar'    => 'Deletar'
            ]
        ],
        'carteirinha'              => [
            'titulo'    => 'Carteirinha',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'status', 'empresa'
            ],
            'permissao' => [
                'carteirinha_index'   => 'Listar',
                'carteirinha_add'     => 'Salvar',
                'carteirinha_editar'  => 'Editar',
                'carteirinha_deletar' => 'Deletar',
                'carteirinha_status'  => 'Status',
                'carteirinha_empresa' => 'Todas as empresas'
            ]
        ],
        'demanda'                  => [
            'titulo'    => 'Demanda',
            'acao'      => [
                'tecnologia', 'criacao', 'convenio'
            ],
            'permissao' => [
                'demanda_tecnologia' => 'Tecnologia',
                'demanda_criacao'    => 'Criação',
                'demanda_convenio'   => 'Convênio'
            ]
        ],
        'log_erro'                 => [
            'titulo'    => 'Log de erro',
            'acao'      => [
                'index', 'visualizar', 'status'
            ],
            'permissao' => [
                'log_erro_index'      => 'Listar',
                'log_erro_visualizar' => 'Visualizar',
                'log_erro_status'     => 'Status'
            ]
        ],
        'painel_config'            => [
            'titulo'    => 'Painel Configurações',
            'acao'      => [
                'index', 'add', 'editar', 'deletar'
            ],
            'permissao' => [
                'painel_config_index'   => 'Listar',
                'painel_config_add'     => 'Salvar',
                'painel_config_editar'  => 'Editar',
                'painel_config_deletar' => 'Deletar'
            ]
        ],
        'album_dado'               => [
            'titulo'    => 'Album de fotos',
            'acao'      => [
                'index', 'add', 'editar', 'deletar', 'foto'
            ],
            'permissao' => [
                'album_dado_index'   => 'Listar',
                'album_dado_add'     => 'Salvar',
                'album_dado_editar'  => 'Editar',
                'album_dado_deletar' => 'Deletar',
                'album_dado_foto'    => 'Gerenciar Foto'
            ]
        ],
        'votacao'                  => [
            'titulo'    => 'Votação',
            'acao'      => [
                'index', 'visualizar', 'add', 'editar', 'deletar'
            ],
            'permissao' => [
                'votacao_index'      => 'Listar',
                'votacao_visualizar' => 'Visualizar',
                'votacao_add'        => 'Salvar',
                'votacao_editar'     => 'Editar',
                'votacao_deletar'    => 'Deletar'
            ]
        ],
        'enquete'                  => [
            'titulo'    => 'Enquete',
            'acao'      => [
                'index', 'visualizar', 'add', 'editar', 'deletar'
            ],
            'permissao' => [
                'enquete_index'      => 'Listar',
                'enquete_visualizar' => 'Visualizar',
                'enquete_add'        => 'Salvar',
                'enquete_editar'     => 'Editar',
                'enquete_deletar'    => 'Deletar'
            ]
        ],
        'site_config'                  => [
            'titulo'    => 'Configurações do Site',
            'acao'      => [
                'index', 'visualizar', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'site_config_index'      => 'Listar',
                'site_config_visualizar' => 'Visualizar',
                'site_config_add'        => 'Salvar',
                'site_config_editar'     => 'Editar',
                'site_config_deletar'    => 'Deletar',
                'site_config_empresa'    => 'Todas as Empresas'
            ]
        ],
        'site_menu'                  => [
            'titulo'    => 'Configurações do Menu (Site)',
            'acao'      => [
                'index', 'visualizar', 'add', 'editar', 'deletar', 'empresa'
            ],
            'permissao' => [
                'site_menu_index'      => 'Listar',
                'site_menu_visualizar' => 'Visualizar',
                'site_menu_add'        => 'Salvar',
                'site_menu_editar'     => 'Editar',
                'site_menu_deletar'    => 'Deletar',
                'site_menu_empresa'    => 'Todas as Empresas'
            ]
        ]
    ];
}
