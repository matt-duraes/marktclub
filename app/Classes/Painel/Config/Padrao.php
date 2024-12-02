<?php

namespace App\Classes\Painel\Config;

final class Padrao
{
    public const TITULO_LISTAR = 'Listar';
    public const TITULO_VISUALIZAR = 'Visualizar';
    public const TITULO_SALVAR = 'Salvar';
    public const TITULO_EDITAR = 'Editar';
    public const TITULO_DOWNLOAD = 'Download';
    public const TITULO_ANALYTICS = 'Analytics';
    public const TITULO_DELETAR = 'Deletar';
    public const TITULO_STATUS = 'Status';
    public const TITULO_APPLE = 'Apple';
    public const TITULO_EMPRESA = 'Todas as Empresas';
    public const TITULO_EQUIPE = 'Todos da Equipe';
    public const TITULO_PERMISSOES = 'Todas as Permissões';
    public const TITULO_DOWN_HISTORICO = 'Download do Historico';
    public const RECURSOS = [
        'perfil'      => 'Perfil',
        'agenda'      => 'Agenda',
        'google'      => 'Google',
        'assinatura'  => 'Assinatura',
        'atualizacao' => 'Atualizações'
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
                    'endereco_cep'         => 'CEP',
                    'endereco_estado'      => 'Estado',
                    'endereco_cidade'      => 'Cidade',
                    'endereco_bairro'      => 'Bairro',
                    'endereco_complemento' => 'Complemento',
                    'endereco_numero'      => 'Número Residencial/Lote',
                    'endereco_logradouro'  => 'Logradouro',
                    'pagamento'            => 'Pagamento',
                    'tipo_pagamento'       => 'Metódo de Pagamento',
                    'analytics'            => 'Analytics',
                    'salavip'              => 'Sala VIP',
                    'dependente'           => 'Dependente',
                    'imagem'               => 'Imagem',
                    'primeiro_acesso'      => 'Primeiro Acesso',
                    'mudar_senha'          => 'Mudar Senha',
                    'senha'                => 'Senha',
                    'status'               => 'Status',
                    'data_criacao_de'      => 'Data de Criação de',
                    'data_criacao_ate'     => 'Data de Criação Até',
                    'data_criacao'         => 'Data de Criação',
                    'data_upload'          => 'Data de Upload',
                    'grupo'                => 'Grupo',
                    'empresa'              => 'Empresa',
                    'subempresa'           => 'Sub Empresa',
                    'trabalho_cargo'       => 'Trabalho no Cargo',
                    'trabalho_empresa'     => 'Trabalho na Empresa',
                    'trabalho_data_inicio' => 'Data de Início do Trabalho',
                    'federacao'            => 'Federação',
                    'lead'                 => 'Lead',
                    'origem'               => 'Origem',
                    'tipo'                 => 'Tipo'
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
                    'endereco_cep'         => 'CEP',
                    'endereco_estado'      => 'Estado',
                    'endereco_cidade'      => 'Cidade',
                    'endereco_bairro'      => 'Bairro',
                    'endereco_complemento' => 'Complemento',
                    'endereco_numero'      => 'Número Residencial/Lote',
                    'endereco_logradouro'  => 'Logradouro',
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
            'permissao' => [
                'usuario_cliente_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'usuario_cliente:listar'
                ],
                'usuario_cliente_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'usuario_cliente:buscar'
                ],
                'usuario_cliente_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['usuario_cliente:salvar', 'usuario_cliente:buscar']
                ],
                'usuario_cliente_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['usuario_cliente:atualizar', 'usuario_cliente:buscar']
                ],
                'usuario_cliente_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'usuario_cliente:deletar'
                ],
                'usuario_cliente_download'   => [
                    'titulo' => self::TITULO_DOWNLOAD,
                    'scope'  => 'usuario_cliente:download'
                ],
                'usuario_cliente_analytics'  => [
                    'titulo' => self::TITULO_ANALYTICS,
                    'scope'  => 'relatorio_analytics:listar'
                ],
                'usuario_cliente_apple'      => [
                    'titulo' => self::TITULO_APPLE,
                    'scope'  => 'usuario_cliente:apple'
                ],
                'usuario_cliente_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'usuario_grupo'            => [
            'titulo'    => 'Usuário Grupo',
            'permissao' => [
                'usuario_grupo_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'usuario_grupo:listar'
                ],
                'usuario_grupo_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['usuario_grupo:salvar', 'usuario_grupo:buscar']
                ],
                'usuario_grupo_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['usuario_grupo:atualizar', 'usuario_grupo:buscar']
                ],
                'usuario_grupo_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'usuario_grupo:deletar'
                ]
            ]
        ],
        'usuario_dependente'       => [
            'titulo'    => 'Usuário Dependente',
            'permissao' => [
                'usuario_dependente_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'usuario_dependente:listar'
                ],
                'usuario_dependente_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['usuario_dependente:salvar', 'usuario_dependente:buscar']
                ],
                'usuario_dependente_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'usuario_dependente:deletar'
                ]
            ]
        ],
        'usuario_indicacao'        => [
            'titulo'    => 'Usuário Indicação',
            'permissao' => [
                'usuario_indicacao_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'usuario_indicacao:listar'
                ],
                'usuario_indicacao_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'usuario_indicacao:buscar'
                ],
                'usuario_indicacao_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => ['usuario_indicacao:atualizar', 'usuario_indicacao:buscar']
                ],
                'usuario_indicacao_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'usuario_lead'             => [
            'titulo'    => 'Usuário Lead',
            'permissao' => [
                'usuario_lead_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'usuario_lead:listar'
                ],
                'usuario_lead_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'usuario_lead:buscar'
                ],
                'usuario_lead_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => ['usuario_lead:atualizar', 'usuario_lead:buscar']
                ],
                'usuario_lead_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'usuario_equipe'           => [
            'titulo'    => 'Usuário Equipe',
            'permissao' => [
                'usuario_equipe_index'     => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'usuario_equipe:listar'
                ],
                'usuario_equipe_add'       => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['usuario_equipe:salvar', 'usuario_equipe:buscar']
                ],
                'usuario_equipe_editar'    => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['usuario_equipe:atualizar', 'usuario_equipe:buscar']
                ],
                'usuario_equipe_deletar'   => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'usuario_equipe:deletar'
                ],
                'usuario_equipe_permissao' => self::TITULO_PERMISSOES,
                'usuario_equipe_empresa'   => self::TITULO_EMPRESA
            ]
        ],
        'comunicacao_login'        => [
            'titulo'    => 'Banners de Login',
            'permissao' => [
                'comunicacao_login_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'comunicacao_login:listar'
                ],
                'comunicacao_login_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['comunicacao_login:salvar', 'comunicacao_login:buscar']
                ],
                'comunicacao_login_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['comunicacao_login:atualizar', 'comunicacao_login:buscar']
                ],
                'comunicacao_login_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'comunicacao_login:deletar'
                ],
                'comunicacao_login_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'comunicacao_publicidade'  => [
            'titulo'    => 'Publicidade',
            'permissao' => [
                'comunicacao_publicidade_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'comunicacao_publicidade:listar'
                ],
                'comunicacao_publicidade_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['comunicacao_publicidade:salvar', 'comunicacao_publicidade:buscar']
                ],
                'comunicacao_publicidade_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['comunicacao_publicidade:atualizar', 'comunicacao_publicidade:buscar']
                ],
                'comunicacao_publicidade_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'comunicacao_publicidade:deletar'
                ],
                'comunicacao_publicidade_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_contato'      => [
            'titulo'    => 'Solicitação Contato',
            'permissao' => [
                'solicitacao_contato_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_contato:listar'
                ],
                'solicitacao_contato_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'solicitacao_contato:buscar'
                ],
                'solicitacao_contato_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => ['solicitacao_contato:atualizar', 'solicitacao_contato:buscar']
                ],
                'solicitacao_contato_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'comercial_popup'          => [
            'titulo'    => 'Popup',
            'permissao' => [
                'comercial_popup_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'comercial_popup:listar'
                ],
                'comercial_popup_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['comercial_popup:salvar', 'comercial_popup:buscar']
                ],
                'comercial_popup_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['comercial_popup:atualizar', 'comercial_popup:buscar']
                ],
                'comercial_popup_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'comercial_popup:deletar'
                ],
                'comercial_popup_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'enquete_satisfacao'       => [
            'titulo'    => 'Pesquisa Satisfação',
            'permissao' => [
                'enquete_satisfacao_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'enquete_satisfacao:listar'
                ],
                'enquete_satisfacao_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'enquete_satisfacao:buscar'
                ],
                'enquete_satisfacao_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'enquete_satisfacao:deletar'
                ],
                'enquete_satisfacao_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => ['enquete_satisfacao:atualizar', 'enquete_satisfacao:buscar']
                ],
                'enquete_satisfacao_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'construtor_clube'         => [
            'titulo'    => 'Construtor Clube',
            'permissao' => [
                'construtor_clube_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'construtor_clube:listar'
                ],
                'construtor_clube_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['construtor_clube:salvar', 'construtor_clube:buscar']
                ],
                'construtor_clube_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['construtor_clube:atualizar', 'construtor_clube:buscar']
                ],
                'construtor_clube_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'construtor_clube:deletar'
                ],
                'construtor_clube_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'publicacao_noticia'       => [
            'titulo'    => 'Notícias',
            'permissao' => [
                'publicacao_noticia_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'publicacao_noticia:listar'
                ],
                'publicacao_noticia_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['publicacao_noticia:salvar', 'publicacao_noticia:buscar']
                ],
                'publicacao_noticia_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['publicacao_noticia:atualizar', 'publicacao_noticia:buscar']
                ],
                'publicacao_noticia_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'publicacao_noticia:deletar'
                ],
                'publicacao_noticia_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'publicacao_lista'         => [
            'titulo'    => 'Lista geral',
            'permissao' => [
                'publicacao_lista_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'publicacao_lista:listar'
                ],
                'publicacao_lista_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['publicacao_lista:salvar', 'publicacao_lista:buscar']
                ],
                'publicacao_lista_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['publicacao_lista:atualizar', 'publicacao_lista:buscar']
                ],
                'publicacao_lista_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'publicacao_lista:deletar'
                ],
                'publicacao_lista_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'publicacao_home'          => [
            'titulo'    => 'Notícia da Home',
            'permissao' => [
                'publicacao_home_editar' => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['publicacao_home:atualizar', 'publicacao_home:buscar']
                ]
            ]
        ],
        'publicacao_live'          => [
            'titulo'    => 'Sistema de live',
            'permissao' => [
                'publicacao_live_editar' => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['publicacao_live:atualizar', 'publicacao_live:buscar']
                ]
            ]
        ],
        'publicacao_pagina'        => [
            'titulo'    => 'Páginas',
            'permissao' => [
                'publicacao_pagina_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'publicacao_pagina:listar'
                ],
                'publicacao_pagina_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['publicacao_pagina:atualizar', 'publicacao_pagina:buscar']
                ],
                'publicacao_pagina_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'publicacao_youtube'       => [
            'titulo'    => 'Youtube',
            'permissao' => [
                'publicacao_youtube_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'publicacao_youtube:listar'
                ],
                'publicacao_youtube_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['publicacao_youtube:salvar', 'publicacao_youtube:buscar']
                ],
                'publicacao_youtube_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['publicacao_youtube:atualizar', 'publicacao_youtube:buscar']
                ],
                'publicacao_youtube_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'publicacao_youtube:deletar'
                ],
                'publicacao_youtube_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'publicacao_arquivo'       => [
            'titulo'    => 'Arquivo',
            'permissao' => [
                'publicacao_arquivo_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'publicacao_arquivo:listar'
                ],
                'publicacao_arquivo_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['publicacao_arquivo:salvar', 'publicacao_arquivo:buscar']
                ],
                'publicacao_arquivo_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['publicacao_arquivo:atualizar', 'publicacao_arquivo:buscar']
                ],
                'publicacao_arquivo_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'publicacao_arquivo:deletar'
                ],
                'publicacao_arquivo_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'publicacao_diretoria'     => [
            'titulo'    => 'Diretoria',
            'permissao' => [
                'publicacao_diretoria_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'publicacao_diretoria:listar'
                ],
                'publicacao_diretoria_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['publicacao_diretoria:salvar', 'publicacao_diretoria:buscar']
                ],
                'publicacao_diretoria_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['publicacao_diretoria:atualizar', 'publicacao_diretoria:buscar']
                ],
                'publicacao_diretoria_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'publicacao_diretoria:deletar'
                ],
                'publicacao_diretoria_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'texto_clube'              => [
            'titulo'    => 'Texto do clube',
            'permissao' => [
                'texto_clube_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'texto_clube:listar'
                ],
                'texto_clube_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['texto_clube:salvar', 'texto_clube:buscar']
                ],
                'texto_clube_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['texto_clube:atualizar', 'texto_clube:buscar']
                ],
                'texto_clube_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'texto_clube:deletar'
                ],
                'texto_clube_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'parceiro_relatorio'       => [
            'titulo'    => 'Relatório do parceiro',
            'permissao' => [
                'parceiro_relatorio_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'parceiro_relatorio:listar'
                ],
                'parceiro_relatorio_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['parceiro_relatorio:salvar', 'parceiro_relatorio:buscar']
                ],
                'parceiro_relatorio_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['parceiro_relatorio:atualizar', 'parceiro_relatorio:buscar']
                ],
                'parceiro_relatorio_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'parceiro_relatorio:deletar'
                ]
            ]
        ],
        'parceiro_loja'            => [
            'titulo'    => 'Loja',
            'permissao' => [
                'parceiro_loja_index'              => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'parceiro_loja:listar'
                ],
                'parceiro_loja_add'                => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['parceiro_loja:salvar', 'parceiro_loja:buscar']
                ],
                'parceiro_loja_visualizar'         => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'parceiro_loja:buscar'
                ],
                'parceiro_loja_editar'             => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['parceiro_loja:atualizar', 'parceiro_loja:buscar']
                ],
                'parceiro_loja_deletar'            => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'parceiro_loja:deletar'
                ],
                'parceiro_loja_download'           => [
                    'titulo' => self::TITULO_DOWNLOAD,
                    'scope'  => 'parceiro_loja:download'
                ],
                'parceiro_loja_status'             => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => ['parceiro_loja:atualizar', 'parceiro_loja:buscar']
                ],
                'parceiro_loja_empresa'            => self::TITULO_EMPRESA,
                'parceiro_loja_historico_download' => self::TITULO_DOWN_HISTORICO
            ]
        ],
        'parceiro_campanha'        => [
            'titulo'    => 'Parceiro campanha',
            'permissao' => [
                'parceiro_campanha_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'parceiro_campanha:listar'
                ],
                'parceiro_campanha_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['parceiro_campanha:salvar', 'parceiro_campanha:buscar']
                ],
                'parceiro_campanha_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['parceiro_campanha:atualizar', 'parceiro_campanha:buscar']
                ],
                'parceiro_campanha_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'parceiro_campanha:deletar'
                ]
            ]
        ],
        'parceiro_externo'         => [
            'titulo'    => 'Loja externo',
            'permissao' => [
                'parceiro_externo_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'parceiro_externo:listar'
                ],
                'parceiro_externo_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['parceiro_externo:salvar', 'parceiro_externo:buscar']
                ],
                'parceiro_externo_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'parceiro_externo:buscar'
                ],
                'parceiro_externo_download'   => [
                    'titulo' => self::TITULO_DOWNLOAD,
                    'scope'  => 'parceiro_externo:download'
                ],
                'parceiro_externo_equipe'     => self::TITULO_EQUIPE,
                'parceiro_externo_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'parceiro_equipe'          => [
            'titulo'    => 'Sem captador',
            'permissao' => [
                'parceiro_equipe_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'parceiro_equipe:listar'
                ],
                'parceiro_equipe_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'parceiro_equipe:buscar'
                ]
            ]
        ],
        'parceiro_cupom'           => [
            'titulo'    => 'Cupom',
            'permissao' => [
                'parceiro_cupom_index'  => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'parceiro_cupom:listar'
                ],
                'parceiro_cupom_status' => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => ['parceiro_cupom:atualizar', 'parceiro_cupom:buscar']
                ]
            ]
        ],
        'parceiro_easylive'        => [
            'titulo'    => 'Easylive',
            'permissao' => [
                'parceiro_easylive_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'parceiro_easylive:listar'
                ],
                'parceiro_easylive_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['parceiro_easylive:salvar', 'parceiro_easylive:buscar']
                ],
                'parceiro_easylive_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['parceiro_easylive:atualizar', 'parceiro_easylive:buscar']
                ],
                'parceiro_easylive_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'parceiro_easylive:deletar'
                ]
            ]
        ],
        'parceiro_automovel'       => [
            'titulo'    => 'Automóvel',
            'permissao' => [
                'parceiro_automovel_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'parceiro_automovel:listar'
                ],
                'parceiro_automovel_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'parceiro_automovel:buscar'
                ],
                'parceiro_automovel_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['parceiro_automovel:salvar', 'parceiro_automovel:buscar']
                ],
                'parceiro_automovel_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['parceiro_automovel:atualizar', 'parceiro_automovel:buscar']
                ],
                'parceiro_automovel_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'parceiro_automovel:deletar'
                ]
            ]
        ],
        'relatorio_acesso'         => [
            'titulo'    => 'Relatório Acesso',
            'permissao' => [
                'relatorio_acesso_index'    => 'Relatório de acesso',
                'relatorio_acesso_empresa'  => 'Todas as empresas',
                'relatorio_acesso_parceiro' => 'Todos os parceiros'
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
                'relatorio_loja_venda_index'    => 'Relatório de vendas',
                'relatorio_loja_venda_empresa'  => 'Todas as empresas',
                'relatorio_loja_venda_parceiro' => 'Todos os parceiros'
            ]
        ],
        'tabela_usuario'           => [
            'titulo'    => 'Tabela de Usuário',
            'permissao' => [
                'tabela_usuario_salvar'   => [
                    'titulo' => 'Salvar',
                    'scope'  => ['tabela_usuario:salvar']
                ],
                'tabela_usuario_bloquear' => [
                    'titulo' => 'Bloquear',
                    'scope'  => ['tabela_usuario:salvar']
                ],
                'tabela_historico_index'  => [
                    'titulo' => 'Histórico',
                    'scope'  => 'tabela_usuario:listar',
                ],
                'tabela_usuario_empresa'  => [
                    'titulo' => 'Todas as empresas'
                ]
            ]
        ],
        'solicitacao_loja'         => [
            'titulo'    => 'Solicitação Loja',
            'permissao' => [
                'solicitacao_loja_index'      => 'Listar',
                'solicitacao_loja_visualizar' => 'Visualizar',
                'solicitacao_loja_add'        => 'Salvar',
                'solicitacao_loja_deletar'    => 'Deletar',
                'solicitacao_loja_download'   => 'Download',
                'solicitacao_loja_status'     => 'Status',
                'solicitacao_loja_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_voucher'      => [
            'titulo'    => 'Solicitação Voucher',
            'permissao' => [
                'solicitacao_voucher_index'      => 'Listar',
                'solicitacao_voucher_visualizar' => 'Visualizar',
                'solicitacao_voucher_download'   => 'Download',
                'solicitacao_voucher_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_premium'      => [
            'titulo'    => 'Solicitacação Voucher Premium',
            'permissao' => [
                'solicitacao_premium_index'      => 'Listar',
                'solicitacao_premium_visualizar' => 'Visualizar',
                'solicitacao_premium_download'   => 'Download',
                'solicitacao_premium_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_salavip'      => [
            'titulo'    => 'Solicitação Salavip',
            'permissao' => [
                'solicitacao_salavip_index'    => 'Listar',
                'solicitacao_salavip_download' => 'Download',
                'solicitacao_salavip_empresa'  => 'Todas as empresas'
            ]
        ],
        'solicitacao_declaracao'   => [
            'titulo'    => 'Solicitação Declaração',
            'permissao' => [
                'solicitacao_declaracao_index'      => 'Listar',
                'solicitacao_declaracao_visualizar' => 'Visualizar',
                'solicitacao_declaracao_status'     => 'Status',
                'solicitacao_declaracao_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_automovel'    => [
            'titulo'    => 'Solicitação Automóvel',
            'permissao' => [
                'solicitacao_automovel_index'      => 'Listar',
                'solicitacao_automovel_visualizar' => 'Visualizar',
                'solicitacao_automovel_status'     => 'Status',
                'solicitacao_automovel_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_cheque_bonus' => [
            'titulo'    => 'Solicitação Cheque Bônus',
            'permissao' => [
                'solicitacao_cheque_bonus_index'      => 'Listar',
                'solicitacao_cheque_bonus_visualizar' => 'Visualizar',
                'solicitacao_cheque_bonus_status'     => 'Status',
                'solicitacao_cheque_bonus_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_credito'      => [
            'titulo'    => 'Solicitação Crédito',
            'permissao' => [
                'solicitacao_credito_index'      => 'Listar',
                'solicitacao_credito_visualizar' => 'Visualizar',
                'solicitacao_credito_status'     => 'Status',
                'solicitacao_credito_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_codigo'       => [
            'titulo'    => 'Solicitação Código',
            'permissao' => [
                'solicitacao_codigo_index'   => 'Listar',
                'solicitacao_codigo_empresa' => 'Todas as empresas'
            ]
        ],
        'saude_contratacao'        => [
            'titulo'    => 'Saúde Contratação',
            'permissao' => [
                'saude_contratacao_index'      => 'Listar',
                'saude_contratacao_visualizar' => 'Visualizar',
                'saude_contratacao_status'     => 'Status',
                'saude_contratacao_empresa'    => 'Todas as empresas'
            ]
        ],
        'comercial_empresa'        => [
            'titulo'    => 'Comercial Empresa',
            'permissao' => [
                'comercial_empresa_index'      => 'Listar',
                'comercial_empresa_visualizar' => 'Visualizar',
                'comercial_empresa_editar'     => 'Editar'
            ]
        ],
        'comercial_subempresa'     => [
            'titulo'    => 'Comercial Subempresa',
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
            'permissao' => [
                'comercial_prospeccao_index'      => 'Listar',
                'comercial_prospeccao_visualizar' => 'Visualizar',
                'comercial_prospeccao_add'        => 'Salvar',
                'comercial_prospeccao_editar'     => 'Editar'
            ]
        ],
        'comercial_perdido'        => [
            'titulo'    => 'Comercial Perdidos',
            'permissao' => [
                'comercial_perdido_index'      => 'Listar',
                'comercial_perdido_visualizar' => 'Visualizar',
                'comercial_perdido_editar'     => 'Editar',
                'comercial_perdido_status'     => 'Status'
            ]
        ],
        'comercial_atendimento'    => [
            'titulo'    => 'Comercial Atendimento',
            'permissao' => [
                'comercial_atendimento_index' => 'Comercial Atendimento'
            ]
        ],
        'comercial_regra'          => [
            'titulo'    => 'Comercial Regra de Negócio',
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
            'permissao' => [
                'carteirinha_index'   => 'Listar',
                'carteirinha_add'     => 'Salvar',
                'carteirinha_editar'  => 'Editar',
                'carteirinha_deletar' => 'Deletar',
                'carteirinha_status'  => 'Status',
                'carteirinha_empresa' => 'Todas as empresas'
            ]
        ],
        'demanda_sprint'           => [
            'titulo'    => 'Sprint Backlog',
            'permissao' => [
                'demanda_sprint_index'      => 'Listar',
                'demanda_sprint_add'        => 'Salvar',
                'demanda_sprint_visualizar' => 'Visualizar',
                'demanda_sprint_editar'     => 'Editar',
                'demanda_sprint_status'     => 'Status',
            ]
        ],
        'demanda_quadro'           => [
            'titulo'    => 'Srpint Quadro',
            'permissao' => [
                'demanda_quadro' => 'Quadro',
            ]
        ],
        'demanda'                  => [
            'titulo'    => 'Demanda',
            'permissao' => [
                'demanda_tecnologia' => 'Tecnologia',
                'demanda_criacao'    => 'Criação',
                'demanda_convenio'   => 'Convênio'
            ]
        ],
        'log_erro'                 => [
            'titulo'    => 'Log de erro',
            'permissao' => [
                'log_erro_index'      => 'Listar',
                'log_erro_visualizar' => 'Visualizar',
                'log_erro_status'     => 'Status'
            ]
        ],
        'painel_config'            => [
            'titulo'    => 'Painel Configurações',
            'permissao' => [
                'painel_config_index'   => 'Listar',
                'painel_config_add'     => 'Salvar',
                'painel_config_editar'  => 'Editar',
                'painel_config_deletar' => 'Deletar'
            ]
        ],
        'album_dado'               => [
            'titulo'    => 'Album de fotos',
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
            'permissao' => [
                'enquete_index'      => 'Listar',
                'enquete_visualizar' => 'Visualizar',
                'enquete_add'        => 'Salvar',
                'enquete_editar'     => 'Editar',
                'enquete_deletar'    => 'Deletar'
            ]
        ],
        'site_config'              => [
            'titulo'    => 'Configurações do Site',
            'permissao' => [
                'site_config_index'      => 'Listar',
                'site_config_visualizar' => 'Visualizar',
                'site_config_add'        => 'Salvar',
                'site_config_editar'     => 'Editar',
                'site_config_deletar'    => 'Deletar',
                'site_config_empresa'    => 'Todas as Empresas'
            ]
        ],
        'site_menu'                => [
            'titulo'    => 'Configurações do Menu (Site)',
            'permissao' => [
                'site_menu_index'      => 'Listar',
                'site_menu_visualizar' => 'Visualizar',
                'site_menu_add'        => 'Salvar',
                'site_menu_editar'     => 'Editar',
                'site_menu_deletar'    => 'Deletar',
                'site_menu_empresa'    => 'Todas as Empresas'
            ]
        ],
        'site_lotacao'             => [
            'titulo'    => 'Lotação',
            'permissao' => [
                'site_lotacao_index'   => 'Listar',
                'site_lotacao_add'     => 'Salvar',
                'site_lotacao_editar'  => 'Editar',
                'site_lotacao_deletar' => 'Deletar',
                'site_lotacao_empresa' => 'Todas as Empresas'
            ]
        ],
        'view_pagina'              => [
            'titulo'    => 'View Página',
            'permissao' => [
                'view_pagina_index'      => 'Listar',
                'view_pagina_visualizar' => 'Visualizar',
                'view_pagina_add'        => 'Salvar',
                'view_pagina_editar'     => 'Editar',
                'view_pagina_deletar'    => 'Deletar',
            ]
        ],
        'silium_comissao'          => [
            'titulo'    => 'Comissões do Silium (Cashback)',
            'permissao' => [
                'silium_comissao_index'      => 'Listar',
                'silium_comissao_visualizar' => 'Visualizar',
                'silium_comissao_add'        => 'Salvar',
                'silium_comissao_editar'     => 'Editar',
                'silium_comissao_status'     => 'Status',
                'silium_comissao_deletar'    => 'Deletar'
            ]
        ],
        'silium_deposito'          => [
            'titulo'    => 'Depósitos do Silium (Cashback)',
            'permissao' => [
                'silium_deposito_index'      => 'Listar',
                'silium_deposito_visualizar' => 'Visualizar',
                'silium_deposito_add'        => 'Salvar',
                'silium_deposito_editar'     => 'Editar',
                'silium_deposito_status'     => 'Status',
                'silium_deposito_deletar'    => 'Deletar'
            ]
        ],
        'silium_saque'             => [
            'titulo'    => 'Solicitações de Saque (Cashback)',
            'permissao' => [
                'silium_saque_index'      => 'Listar',
                'silium_saque_visualizar' => 'Visualizar',
                'silium_saque_add'        => 'Salvar',
                'silium_saque_editar'     => 'Editar',
                'silium_saque_status'     => 'Status',
                'silium_saque_deletar'    => 'Deletar'
            ]
        ],
        'silium_saldo'             => [
            'titulo'    => 'Ranking de Silium (Cashback)',
            'permissao' => [
                'silium_saldo_index' => 'Listar'
            ]
        ],
        'silium_config'            => [
            'titulo'    => 'Configurações do Silium (Cashback)',
            'permissao' => [
                'silium_config_index'      => 'Listar',
                'silium_config_visualizar' => 'Visualizar',
                'silium_config_add'        => 'Salvar',
                'silium_config_editar'     => 'Editar',
                'silium_config_deletar'    => 'Deletar'
            ]
        ]
    ];
    private const SCOPE_FIXO = [];

    public function permissao()
    {
        $retorno = [];
        foreach (self::PERMISSOES as $item) {
            foreach ($item['permissao'] as $ind => $val) {
                $retorno[] = $ind;
            }
        }
        return $retorno;
    }

    public function scope(array $equipe)
    {
        $lista = [];
        foreach (self::PERMISSOES as $indice => $permissao) {
            $prefixScope = $permissao['scope'] ?? $indice;
            if (empty($prefixScope)) {
                continue;
            }
            foreach ($permissao['permissao'] as $subindice => $acao) {
                if (!in_array($subindice, $equipe) || !is_array($acao)) {
                    continue;
                }
            }
        }

        return array_values(arrayRemoverValorDuplicado($lista));
    }
}
