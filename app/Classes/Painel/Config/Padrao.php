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
                    'scope'  => ['usuario_cliente:buscar', 'site_lotacao:select']
                ],
                'usuario_cliente_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'usuario_cliente:salvar'
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
                    'scope'  => ['usuario_cliente:download', 'mensageria:salvar']
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
                    'scope'  => 'usuario_grupo:salvar'
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
                    'scope'  => 'usuario_dependente:salvar'
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
                    'scope'  => 'usuario_equipe:salvar'
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
                    'scope'  => 'comunicacao_login:salvar'
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
                    'scope'  => 'comunicacao_publicidade:salvar'
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
                    'scope'  => 'comercial_popup:salvar'
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
                    'scope'  => 'construtor_clube:salvar'
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
                    'scope'  => 'publicacao_noticia:salvar'
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
                    'scope'  => 'publicacao_lista:salvar'
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
                    'scope'  => 'publicacao_youtube:salvar'
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
                    'scope'  => 'publicacao_arquivo:salvar'
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
                    'scope'  => 'publicacao_diretoria:salvar'
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
                    'scope'  => 'texto_clube:salvar'
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
                    'scope'  => 'parceiro_relatorio:salvar'
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
                    'scope'  => 'parceiro_loja:salvar'
                ],
                'parceiro_loja_visualizar'         => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => [
                        'parceiro_loja:buscar', 'endereco:listar', 'endereco:salvar',
                        'endereco:atualizar', 'contato:listar', 'contato:salvar',
                        'contato:atualizar', 'data:listar'
                    ]
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
                    'scope'  => 'parceiro_campanha:salvar'
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
                    'scope'  => 'parceiro_externo:salvar'
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
                    'scope'  => 'parceiro_easylive:salvar'
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
                    'scope'  => [
                        'automovel_modelo:listar', 'automovel_modelo:listar',
                        'automovel_modelo:deletar'
                    ]
                ],
                'parceiro_automovel_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => [
                        'automovel_modelo:buscar', 'automovel_versao:buscar',
                        'automovel_versao:salvar', 'automovel_versao:atualizar',
                        'automovel_versao:deletar'
                    ]
                ],
                'parceiro_automovel_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['automovel_modelo:salvar', 'automovel_modelo:buscar']
                ],
                'parceiro_automovel_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['automovel_modelo:atualizar', 'automovel_modelo:buscar']
                ],
                'parceiro_automovel_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'automovel_modelo:deletar'
                ]
            ]
        ],
        'relatorio_acesso'         => [
            'titulo'    => 'Relatório Acesso',
            'permissao' => [
                'relatorio_acesso_index'   => [
                    'titulo' => 'Relatório de acesso',
                    'scope'  => ['relatorio_acesso:listar']
                ],
                'relatorio_acesso_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'relatorio_usuario'        => [
            'titulo'    => 'Relatório de usuário',
            'permissao' => [
                'relatorio_usuario_index'   => [
                    'titulo' => 'Relatório de usuário',
                    'scope'  => ['relatorio_usuario:listar']
                ],
                'relatorio_usuario_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'relatorio_loja_venda'     => [
            'titulo'    => 'Relatório de vendas',
            'permissao' => [
                'relatorio_loja_venda_index'    => [
                    'titulo' => 'Relatório de vendas',
                    'scope'  => ['relatorio_loja_venda:listar']
                ],
                'relatorio_loja_venda_empresa'  => self::TITULO_EMPRESA,
                'relatorio_loja_venda_parceiro' => 'Todos os parceiros'
            ]
        ],
        'tabela_usuario'           => [
            'titulo'    => 'Tabela de Usuário',
            'permissao' => [
                'tabela_usuario_salvar'   => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'tabela_usuario:salvar'
                ],
                'tabela_usuario_bloquear' => [
                    'titulo' => 'Bloquear',
                    'scope'  => 'tabela_usuario:salvar'
                ],
                'tabela_historico_index'  => [
                    'titulo' => 'Histórico',
                    'scope'  => 'tabela_usuario:listar',
                ],
                'tabela_usuario_empresa'  => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_loja'         => [
            'titulo'    => 'Solicitação Loja',
            'permissao' => [
                'solicitacao_loja_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_loja:listar'
                ],
                'solicitacao_loja_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'solicitacao_loja:buscar'
                ],
                'solicitacao_loja_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'solicitacao_loja:salvar'
                ],
                'solicitacao_loja_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'solicitacao_loja:deletar'
                ],
                'solicitacao_loja_download'   => [
                    'titulo' => self::TITULO_DOWNLOAD,
                    'scope'  => 'solicitacao_loja:download'
                ],
                'solicitacao_loja_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => ['solicitacao_loja:atualizar', 'solicitacao_loja:buscar']
                ],
                'solicitacao_loja_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_voucher'      => [
            'titulo'    => 'Solicitação Voucher',
            'permissao' => [
                'solicitacao_voucher_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_voucher:listar'
                ],
                'solicitacao_voucher_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'solicitacao_voucher:buscar'
                ],
                'solicitacao_voucher_download'   => [
                    'titulo' => self::TITULO_DOWNLOAD,
                    'scope'  => 'solicitacao_voucher:download'
                ],
                'solicitacao_voucher_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_premium'      => [
            'titulo'    => 'Solicitacação Voucher Premium',
            'permissao' => [
                'solicitacao_premium_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_premium:listar'
                ],
                'solicitacao_premium_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'solicitacao_premium:buscar'
                ],
                'solicitacao_premium_download'   => [
                    'titulo' => self::TITULO_DOWNLOAD,
                    'scope'  => 'solicitacao_premium:download'
                ],
                'solicitacao_premium_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_salavip'      => [
            'titulo'    => 'Solicitação Salavip',
            'permissao' => [
                'solicitacao_salavip_index'    => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_salavip:listar'
                ],
                'solicitacao_salavip_download' => [
                    'titulo' => self::TITULO_DOWNLOAD,
                    'scope'  => 'solicitacao_salavip:download'
                ],
                'solicitacao_salavip_empresa'  => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_declaracao'   => [
            'titulo'    => 'Solicitação Declaração',
            'permissao' => [
                'solicitacao_declaracao_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_declaracao:listar'
                ],
                'solicitacao_declaracao_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'solicitacao_declaracao:buscar'
                ],
                'solicitacao_declaracao_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => ['solicitacao_declaracao:atualizar', 'solicitacao_declaracao:buscar']
                ],
                'solicitacao_declaracao_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_automovel'    => [
            'titulo'    => 'Solicitação Automóvel',
            'permissao' => [
                'solicitacao_automovel_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_automovel:listar'
                ],
                'solicitacao_automovel_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'solicitacao_automovel:buscar'
                ],
                'solicitacao_automovel_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => ['solicitacao_automovel:atualizar', 'solicitacao_automovel:buscar']
                ],
                'solicitacao_automovel_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_cheque_bonus' => [
            'titulo'    => 'Solicitação Cheque Bônus',
            'permissao' => [
                'solicitacao_cheque_bonus_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_cheque_bonus:listar'
                ],
                'solicitacao_cheque_bonus_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'solicitacao_cheque_bonus:buscar'
                ],
                'solicitacao_cheque_bonus_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => 'solicitacao_cheque_bonus:atualizar'
                ],
                'solicitacao_cheque_bonus_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_credito'      => [
            'titulo'    => 'Solicitação Crédito',
            'permissao' => [
                'solicitacao_credito_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_credito:listar'
                ],
                'solicitacao_credito_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'solicitacao_credito:buscar'
                ],
                'solicitacao_credito_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => 'solicitacao_credito:atualizar'
                ],
                'solicitacao_credito_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'solicitacao_codigo'       => [
            'titulo'    => 'Solicitação Código',
            'permissao' => [
                'solicitacao_codigo_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'solicitacao_codigo:listar'
                ],
                'solicitacao_codigo_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'saude_contratacao'        => [
            'titulo'    => 'Saúde Contratação',
            'permissao' => [
                'saude_contratacao_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'saude_contratacao:listar'
                ],
                'saude_contratacao_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'saude_contratacao:buscar'
                ],
                'saude_contratacao_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => 'saude_contratacao:atualizar'
                ],
                'saude_contratacao_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'comercial_empresa'        => [
            'titulo'    => 'Comercial Empresa',
            'permissao' => [
                'comercial_empresa_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'comercial_empresa:listar'
                ],
                'comercial_empresa_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'comercial_empresa:buscar'
                ],
                'comercial_empresa_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['comercial_empresa:atualizar', 'comercial_empresa:buscar']
                ]
            ]
        ],
        'comercial_subempresa'     => [
            'titulo'    => 'Comercial Subempresa',
            'permissao' => [
                'comercial_subempresa_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'comercial_subempresa:listar'
                ],
                'comercial_subempresa_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'comercial_subempresa:buscar'
                ],
                'comercial_subempresa_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'comercial_subempresa:salvar'
                ],
                'comercial_subempresa_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['comercial_subempresa:atualizar', 'comercial_subempresa:buscar']
                ],
                'comercial_subempresa_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'comercial_subempresa:deletar'
                ],
                'comercial_subempresa_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'comercial_prospeccao'     => [
            'titulo'    => 'Comercial Prospecção',
            'permissao' => [
                'comercial_prospeccao_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'comercial_prospeccao:listar'
                ],
                'comercial_prospeccao_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'comercial_prospeccao:buscar'
                ],
                'comercial_prospeccao_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => ['comercial_prospeccao:salvar', 'comercial_empresa:salvar']
                ],
                'comercial_prospeccao_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['comercial_prospeccao:atualizar', 'comercial_prospeccao:buscar']
                ]
            ]
        ],
        'comercial_perdido'        => [
            'titulo'    => 'Comercial Perdidos',
            'permissao' => [
                'comercial_perdido_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'comercial_perdido:listar'
                ],
                'comercial_perdido_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'comercial_perdido:buscar'
                ],
                'comercial_perdido_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['comercial_perdido:atualizar', 'comercial_perdido:buscar']
                ],
                'comercial_perdido_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => 'comercial_perdido:atualizar'
                ]
            ]
        ],
        'comercial_atendimento'    => [
            'titulo'    => 'Comercial Atendimento',
            'permissao' => [
                'comercial_atendimento_index' => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'comercial_atendimento:listar'
                ]
            ]
        ],
        'comercial_regra'          => [
            'titulo'    => 'Comercial Regra de Negócio',
            'permissao' => [
                'comercial_regra_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'comercial_regra:listar'
                ],
                'comercial_regra_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'comercial_regra:buscar'
                ],
                'comercial_regra_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'comercial_regra:salvar'
                ],
                'comercial_regra_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['comercial_regra:atualizar', 'comercial_regra:buscar']
                ],
                'comercial_regra_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'comercial_regra:deletar'
                ]
            ]
        ],
        'carteirinha'              => [
            'titulo'    => 'Carteirinha',
            'permissao' => [
                'carteirinha_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'carteirinha:listar'
                ],
                'carteirinha_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'carteirinha:salvar'
                ],
                'carteirinha_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['carteirinha:atualizar', 'carteirinha:buscar']
                ],
                'carteirinha_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'carteirinha:deletar'
                ],
                'carteirinha_status'  => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => 'carteirinha:atualizar'
                ],
                'carteirinha_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'demanda_sprint'           => [
            'titulo'    => 'Sprint Backlog',
            'permissao' => [
                'demanda_sprint_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'demanda_sprint:listar'
                ],
                'demanda_sprint_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'demanda_sprint:salvar'
                ],
                'demanda_sprint_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'demanda_sprint:buscar'
                ],
                'demanda_sprint_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['demanda_sprint:atualizar', 'demanda_sprint:buscar']
                ],
                'demanda_sprint_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => 'demanda_sprint:atualizar'
                ]
            ]
        ],
        'demanda_quadro'           => [
            'titulo'    => 'Srpint Quadro',
            'permissao' => [
                'demanda_quadro' => [
                    'titulo' => 'Quadro',
                    'scope'  => [
                        'demanda_dado:listar', 'demanda_dado:salvar',
                        'demanda_dado:atualizar', 'demanda_dado:deletar',
                        'demanda_dado:buscar', 'demanda_sprint:demanda',
                        'comercial_empresa:perfil', 'demanda_sprint:salvar',
                        'demanda_tarefa:listar', 'demanda_tarefa:salvar',
                        'demanda_tarefa:atualizar', 'demanda_tarefa:deletar'
                    ]
                ]
            ]
        ],
        'demanda'                  => [
            'titulo'    => 'Demanda',
            'permissao' => [
                'demanda_tecnologia' => [
                    'titulo' => 'Tecnoogia',
                    'scope'  => [
                        'demanda_dado:listar', 'demanda_dado:salvar',
                        'demanda_dado:atualizar', 'demanda_dado:deletar',
                        'demanda_dado:buscar', 'demanda_sprint:demanda',
                        'comercial_empresa:perfil', 'demanda_sprint:salvar',
                        'demanda_tarefa:listar', 'demanda_tarefa:salvar',
                        'demanda_tarefa:atualizar', 'demanda_tarefa:deletar'
                    ]
                ],
                'demanda_criacao'    => [
                    'titulo' => 'Criação',
                    'scope'  => [
                        'demanda_dado:listar', 'demanda_dado:salvar',
                        'demanda_dado:atualizar', 'demanda_dado:deletar',
                        'demanda_dado:buscar', 'demanda_sprint:demanda',
                        'comercial_empresa:perfil', 'demanda_sprint:salvar',
                        'demanda_tarefa:listar', 'demanda_tarefa:salvar',
                        'demanda_tarefa:atualizar', 'demanda_tarefa:deletar'
                    ]
                ],
                'demanda_convenio'   => [
                    'titulo' => 'Convênios',
                    'scope'  => [
                        'demanda_dado:listar', 'demanda_dado:salvar',
                        'demanda_dado:atualizar', 'demanda_dado:deletar',
                        'demanda_dado:buscar', 'demanda_sprint:demanda',
                        'comercial_empresa:perfil', 'demanda_sprint:salvar',
                        'demanda_tarefa:listar', 'demanda_tarefa:salvar',
                        'demanda_tarefa:atualizar', 'demanda_tarefa:deletar'
                    ]
                ]
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
                'painel_config_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'painel_config:listar'
                ],
                'painel_config_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'painel_config:salvar'
                ],
                'painel_config_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['painel_config:atualizar', 'painel_config:buscar']
                ],
                'painel_config_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'painel_config:deletar'
                ]
            ]
        ],
        'album_dado'               => [
            'titulo'    => 'Album de fotos',
            'permissao' => [
                'album_dado_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'album_dado:listar'
                ],
                'album_dado_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'album_dado:salvar'
                ],
                'album_dado_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['album_dado:atualizar', 'album_dado:buscar']
                ],
                'album_dado_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'album_dado:deletar'
                ],
                'album_dado_foto'    => [
                    'titulo' => 'Gerenciar Foto',
                    'scope'  => 'album_dado:foto'
                ],
            ]
        ],
        'votacao'                  => [
            'titulo'    => 'Votação',
            'permissao' => [
                'votacao_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'votacao:listar'
                ],
                'votacao_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => [
                        'votacao:buscar', 'votacao:atualizar', 'votacao_pergunta:atualizar',
                        'votacao_pergunta:buscar', 'votacao_pergunta:deletar',
                        'votacao_pergunta:listar', 'votacao_pergunta:salvar',
                        'votacao_resposta:buscar'
                    ]
                ],
                'votacao_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'votacao:salvar'
                ],
                'votacao_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['votacao:atualizar', 'votacao:buscar']
                ],
                'votacao_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'votacao:deletar'
                ],
            ]
        ],
        'enquete'                  => [
            'titulo'    => 'Enquete',
            'permissao' => [
                'enquete_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'votacao_dado:listar'
                ],
                'enquete_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'votacao_dado:buscar'
                ],
                'enquete_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'votacao_dado:salvar'
                ],
                'enquete_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['votacao_dado:atualizar', 'votacao_dado:buscar']
                ],
                'enquete_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'votacao_dado:deletar'
                ],
            ]
        ],
        'site_config'              => [
            'titulo'    => 'Configurações do Site',
            'permissao' => [
                'site_config_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'site_config:listar'
                ],
                'site_config_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'site_config:buscar'
                ],
                'site_config_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'site_config:salvar'
                ],
                'site_config_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['site_config:atualizar', 'site_config:buscar']
                ],
                'site_config_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'site_config:deletar'
                ],
                'site_config_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'site_menu'                => [
            'titulo'    => 'Configurações do Menu (Site)',
            'permissao' => [
                'site_menu_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'site_menu:listar',
                ],
                'site_menu_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'site_menu:buscar'
                ],
                'site_menu_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'site_menu:salvar'
                ],
                'site_menu_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['site_menu:atualizar', 'site_menu:buscar']
                ],
                'site_menu_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'site_menu:deletar'
                ],
                'site_menu_empresa'    => self::TITULO_EMPRESA
            ]
        ],
        'site_lotacao'             => [
            'titulo'    => 'Lotação',
            'permissao' => [
                'site_lotacao_index'   => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'site_lotacao:listar'
                ],
                'site_lotacao_add'     => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'site_lotacao:salvar'
                ],
                'site_lotacao_editar'  => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['site_lotacao:atualizar', 'site_lotacao:buscar']
                ],
                'site_lotacao_deletar' => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'site_lotacao:deletar'
                ],
                'site_lotacao_empresa' => self::TITULO_EMPRESA
            ]
        ],
        'view_pagina'              => [
            'titulo'    => 'View Página',
            'permissao' => [
                'view_pagina_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'view_pagina:listar'
                ],
                'view_pagina_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => ['view_pagina:buscar', 'view_html:listar']
                ],
                'view_pagina_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'view_pagina:salvar'
                ],
                'view_pagina_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['view_pagina:atualizar', 'view_pagina:buscar']
                ],
                'view_pagina_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'view_pagina:deletar'
                ]
            ]
        ],
        'silium_comissao'          => [
            'titulo'    => 'Comissões do Silium (Cashback)',
            'permissao' => [
                'silium_comissao_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'silium_comissao:listar',
                ],
                'silium_comissao_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'silium_comissao:buscar',
                ],
                'silium_comissao_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'silium_comissao:salvar'
                ],
                'silium_comissao_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['silium_comissao:atualizar', 'silium_comissao:buscar']
                ],
                'silium_comissao_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => 'silium_comissao:atualizar'
                ],
                'silium_comissao_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'silium_comissao:deletar'
                ],
            ]
        ],
        'silium_deposito'          => [
            'titulo'    => 'Depósitos do Silium (Cashback)',
            'permissao' => [
                'silium_deposito_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'silium_deposito:listar',
                ],
                'silium_deposito_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'silium_deposito:buscar',
                ],
                'silium_deposito_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'silium_deposito:salvar'
                ],
                'silium_deposito_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['silium_deposito:atualizar', 'silium_deposito:buscar']
                ],
                'silium_deposito_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => 'silium_deposito:atualizar'
                ],
                'silium_deposito_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'silium_deposito:deletar'
                ]
            ]
        ],
        'silium_saque'             => [
            'titulo'    => 'Solicitações de Saque (Cashback)',
            'permissao' => [
                'silium_saque_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'silium_saque:listar'
                ],
                'silium_saque_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'silium_saque:buscar'
                ],
                'silium_saque_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'silium_saque:salvar'
                ],
                'silium_saque_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['silium_saque:atualizar', 'silium_saque:buscar']
                ],
                'silium_saque_status'     => [
                    'titulo' => self::TITULO_STATUS,
                    'scope'  => 'silium_deposito:atualizar'
                ],
                'silium_saque_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'silium_saque:deletar'
                ]
            ]
        ],
        'silium_saldo'             => [
            'titulo'    => 'Ranking de Silium (Cashback)',
            'permissao' => [
                'silium_saldo_index' => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'silium_saldo:listar'
                ]
            ]
        ],
        'silium_config'            => [
            'titulo'    => 'Configurações do Silium (Cashback)',
            'permissao' => [
                'silium_config_index'      => [
                    'titulo' => self::TITULO_LISTAR,
                    'scope'  => 'silium_config:listar'
                ],
                'silium_config_visualizar' => [
                    'titulo' => self::TITULO_VISUALIZAR,
                    'scope'  => 'silium_config:buscar'
                ],
                'silium_config_add'        => [
                    'titulo' => self::TITULO_SALVAR,
                    'scope'  => 'silium_config:salvar'
                ],
                'silium_config_editar'     => [
                    'titulo' => self::TITULO_EDITAR,
                    'scope'  => ['silium_config:atualizar', 'silium_config:buscar']
                ],
                'silium_config_deletar'    => [
                    'titulo' => self::TITULO_DELETAR,
                    'scope'  => 'silium_config:deletar'
                ]
            ]
        ]
    ];
    private const SCOPE_FIXO = [];

    public function scope(array $equipe): array
    {
        $permissoes = $this->montarPermissoes();
        $scopes = [];
        foreach ($equipe as $item) {
            if (!array_key_exists($item, $permissoes) || empty($permissoes[$item])) {
                continue;
            }
            $scopes = array_merge($scopes, $permissoes[$item]);
        }
        return array_values(arrayRemoverValorDuplicado($scopes));
    }

    /**
     * @return array
     */
    public function montarPermissoes(): array
    {
        $retorno = [];
        foreach (self::PERMISSOES as $painelApp) {
            foreach ($painelApp['permissao'] as $permissao => $apiScope) {
                $scope = $apiScope['scope'] ?? '';
                if (empty($scope)) {
                    $retorno[$permissao] = [];
                    continue;
                }
                $retorno[$permissao] = is_string($scope) ? [$scope] : $scope;
            }
        }
        return $retorno;
    }
}
