<?php

namespace App\Classes\Painel\Config\Trait;

use App\Classes\Painel\Config\Titulo;
use Closure;

trait PermissaoTrait
{
    private array $permissaoPersonalizada;

    private function setarPropriedadePermissao(): void
    {
        $this->setarPropriedadePermissaoDepreciada(); // Remover quando zerar as permissões setado direto
        $this->montarArrayPermissao(
            titulo: 'Usuário Cliente',
            indice: 'usuario_cliente',
            index: true,
            visualizar: true,
            add: true,
            editar: true,
            deletar: true,
            download: true,
            empresa: true,
            personalizado: function () {
                $this->montarArrayPersonalizado(
                    indice: 'usuario_cliente_analytics',
                    titulo: 'Analytics do usuário',
                    scope: ['relatorio_analytics:listar']
                );
                $this->montarArrayPersonalizado(
                    indice: 'usuario_cliente_apple',
                    titulo: 'Usuário para Apple',
                    scope: ['usuario_cliente:apple']
                );
                $this->montarArrayPersonalizado(
                    indice: 'site_lotacao_select',
                    titulo: 'Lotação',
                    scope: ['site_lotacao:select']
                );
            }
        )->montarArrayPermissao(
            titulo: 'Comercial Atendimento',
            indice: 'comercial_atendimento',
            index: true,
        )->montarArrayPermissao(
            titulo: 'Comercial Indicação',
            indice: 'comercial_indicacao',
            scope: 'comercial_empresa',
            index: true,
            add: true,
            visualizar: true
        )->montarArrayPermissao(
            titulo: 'Grupo do usuário',
            indice: 'usuario_grupo',
            index: true,
            add: true,
            editar: true,
            deletar: true
        );
    }

    private function setarPropriedadePermissaoDepreciada()
    {
        $this->PERMISSAO = [
            'usuario_dependente'       => [
                'titulo'    => 'Usuário Dependente',
                'permissao' => [
                    'usuario_dependente_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'usuario_dependente:listar'
                    ],
                    'usuario_dependente_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'usuario_dependente:salvar'
                    ],
                    'usuario_dependente_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'usuario_dependente:deletar'
                    ]
                ]
            ],
            'usuario_indicacao'        => [
                'titulo'    => 'Usuário Indicação',
                'permissao' => [
                    'usuario_indicacao_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'usuario_indicacao:listar'
                    ],
                    'usuario_indicacao_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'usuario_indicacao:buscar'
                    ],
                    'usuario_indicacao_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => ['usuario_indicacao:atualizar', 'usuario_indicacao:buscar']
                    ],
                    'usuario_indicacao_empresa'    => Titulo::EMPRESA
                ]
            ],
            'usuario_lead'             => [
                'titulo'    => 'Usuário Lead',
                'permissao' => [
                    'usuario_lead_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'usuario_lead:listar'
                    ],
                    'usuario_lead_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'usuario_lead:buscar'
                    ],
                    'usuario_lead_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => ['usuario_lead:atualizar', 'usuario_lead:buscar']
                    ],
                    'usuario_lead_empresa'    => Titulo::EMPRESA
                ]
            ],
            'usuario_equipe'           => [
                'titulo'    => 'Usuário Equipe',
                'permissao' => [
                    'usuario_equipe_index'     => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'usuario_equipe:listar'
                    ],
                    'usuario_equipe_add'       => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'usuario_equipe:salvar'
                    ],
                    'usuario_equipe_editar'    => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['usuario_equipe:atualizar', 'usuario_equipe:buscar']
                    ],
                    'usuario_equipe_deletar'   => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'usuario_equipe:deletar'
                    ],
                    'usuario_equipe_permissao' => Titulo::PERMISSOES,
                    'usuario_equipe_empresa'   => Titulo::EMPRESA
                ]
            ],
            'comunicacao_login'        => [
                'titulo'    => 'Banners de Login',
                'permissao' => [
                    'comunicacao_login_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comunicacao_login:listar'
                    ],
                    'comunicacao_login_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'comunicacao_login:salvar'
                    ],
                    'comunicacao_login_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['comunicacao_login:atualizar', 'comunicacao_login:buscar']
                    ],
                    'comunicacao_login_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'comunicacao_login:deletar'
                    ],
                    'comunicacao_login_empresa' => Titulo::EMPRESA
                ]
            ],
            'comunicacao_publicidade'  => [
                'titulo'    => 'Publicidade',
                'permissao' => [
                    'comunicacao_publicidade_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comunicacao_publicidade:listar'
                    ],
                    'comunicacao_publicidade_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'comunicacao_publicidade:salvar'
                    ],
                    'comunicacao_publicidade_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['comunicacao_publicidade:atualizar', 'comunicacao_publicidade:buscar']
                    ],
                    'comunicacao_publicidade_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'comunicacao_publicidade:deletar'
                    ],
                    'comunicacao_publicidade_empresa' => Titulo::EMPRESA
                ]
            ],
            'solicitacao_contato'      => [
                'titulo'    => 'Solicitação Contato',
                'permissao' => [
                    'solicitacao_contato_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_contato:listar'
                    ],
                    'solicitacao_contato_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'solicitacao_contato:buscar'
                    ],
                    'solicitacao_contato_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => ['solicitacao_contato:atualizar', 'solicitacao_contato:buscar']
                    ],
                    'solicitacao_contato_empresa'    => Titulo::EMPRESA
                ]
            ],
            'comercial_popup'          => [
                'titulo'    => 'Popup',
                'permissao' => [
                    'comercial_popup_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comercial_popup:listar'
                    ],
                    'comercial_popup_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'comercial_popup:salvar'
                    ],
                    'comercial_popup_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['comercial_popup:atualizar', 'comercial_popup:buscar']
                    ],
                    'comercial_popup_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'comercial_popup:deletar'
                    ],
                    'comercial_popup_empresa' => Titulo::EMPRESA
                ]
            ],
            'enquete_satisfacao'       => [
                'titulo'    => 'Pesquisa Satisfação',
                'permissao' => [
                    'enquete_satisfacao_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'enquete_satisfacao:listar'
                    ],
                    'enquete_satisfacao_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'enquete_satisfacao:buscar'
                    ],
                    'enquete_satisfacao_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'enquete_satisfacao:deletar'
                    ],
                    'enquete_satisfacao_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => ['enquete_satisfacao:atualizar', 'enquete_satisfacao:buscar']
                    ],
                    'enquete_satisfacao_empresa'    => Titulo::EMPRESA
                ]
            ],
            'construtor_clube'         => [
                'titulo'    => 'Construtor Clube',
                'permissao' => [
                    'construtor_clube_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'construtor_clube:listar'
                    ],
                    'construtor_clube_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'construtor_clube:salvar'
                    ],
                    'construtor_clube_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['construtor_clube:atualizar', 'construtor_clube:buscar']
                    ],
                    'construtor_clube_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'construtor_clube:deletar'
                    ],
                    'construtor_clube_empresa' => Titulo::EMPRESA
                ]
            ],
            'publicacao_noticia'       => [
                'titulo'    => 'Notícias',
                'permissao' => [
                    'publicacao_noticia_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'publicacao_noticia:listar'
                    ],
                    'publicacao_noticia_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'publicacao_noticia:salvar'
                    ],
                    'publicacao_noticia_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['publicacao_noticia:atualizar', 'publicacao_noticia:buscar']
                    ],
                    'publicacao_noticia_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'publicacao_noticia:deletar'
                    ],
                    'publicacao_noticia_empresa' => Titulo::EMPRESA
                ]
            ],
            'publicacao_lista'         => [
                'titulo'    => 'Lista geral',
                'permissao' => [
                    'publicacao_lista_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'publicacao_lista:listar'
                    ],
                    'publicacao_lista_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'publicacao_lista:salvar'
                    ],
                    'publicacao_lista_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['publicacao_lista:atualizar', 'publicacao_lista:buscar']
                    ],
                    'publicacao_lista_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'publicacao_lista:deletar'
                    ],
                    'publicacao_lista_empresa' => Titulo::EMPRESA
                ]
            ],
            'publicacao_home'          => [
                'titulo'    => 'Notícia da Home',
                'permissao' => [
                    'publicacao_home_editar' => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['publicacao_home:atualizar', 'publicacao_home:buscar']
                    ]
                ]
            ],
            'publicacao_live'          => [
                'titulo'    => 'Sistema de live',
                'permissao' => [
                    'publicacao_live_editar' => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['publicacao_live:atualizar', 'publicacao_live:buscar']
                    ]
                ]
            ],
            'publicacao_pagina'        => [
                'titulo'    => 'Páginas',
                'permissao' => [
                    'publicacao_pagina_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'publicacao_pagina:listar'
                    ],
                    'publicacao_pagina_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['publicacao_pagina:atualizar', 'publicacao_pagina:buscar']
                    ],
                    'publicacao_pagina_empresa' => Titulo::EMPRESA
                ]
            ],
            'publicacao_youtube'       => [
                'titulo'    => 'Youtube',
                'permissao' => [
                    'publicacao_youtube_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'publicacao_youtube:listar'
                    ],
                    'publicacao_youtube_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'publicacao_youtube:salvar'
                    ],
                    'publicacao_youtube_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['publicacao_youtube:atualizar', 'publicacao_youtube:buscar']
                    ],
                    'publicacao_youtube_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'publicacao_youtube:deletar'
                    ],
                    'publicacao_youtube_empresa' => Titulo::EMPRESA
                ]
            ],
            'publicacao_arquivo'       => [
                'titulo'    => 'Arquivo',
                'permissao' => [
                    'publicacao_arquivo_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'publicacao_arquivo:listar'
                    ],
                    'publicacao_arquivo_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'publicacao_arquivo:salvar'
                    ],
                    'publicacao_arquivo_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['publicacao_arquivo:atualizar', 'publicacao_arquivo:buscar']
                    ],
                    'publicacao_arquivo_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'publicacao_arquivo:deletar'
                    ],
                    'publicacao_arquivo_empresa' => Titulo::EMPRESA
                ]
            ],
            'publicacao_diretoria'     => [
                'titulo'    => 'Diretoria',
                'permissao' => [
                    'publicacao_diretoria_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'publicacao_diretoria:listar'
                    ],
                    'publicacao_diretoria_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'publicacao_diretoria:salvar'
                    ],
                    'publicacao_diretoria_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['publicacao_diretoria:atualizar', 'publicacao_diretoria:buscar']
                    ],
                    'publicacao_diretoria_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'publicacao_diretoria:deletar'
                    ],
                    'publicacao_diretoria_empresa' => Titulo::EMPRESA
                ]
            ],
            'texto_clube'              => [
                'titulo'    => 'Texto do clube',
                'permissao' => [
                    'texto_clube_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'texto_clube:listar'
                    ],
                    'texto_clube_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'texto_clube:salvar'
                    ],
                    'texto_clube_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['texto_clube:atualizar', 'texto_clube:buscar']
                    ],
                    'texto_clube_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'texto_clube:deletar'
                    ],
                    'texto_clube_empresa' => Titulo::EMPRESA
                ]
            ],
            'parceiro_relatorio'       => [
                'titulo'    => 'Relatório do parceiro',
                'permissao' => [
                    'parceiro_relatorio_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'parceiro_relatorio:listar'
                    ],
                    'parceiro_relatorio_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'parceiro_relatorio:salvar'
                    ],
                    'parceiro_relatorio_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['parceiro_relatorio:atualizar', 'parceiro_relatorio:buscar']
                    ],
                    'parceiro_relatorio_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'parceiro_relatorio:deletar'
                    ]
                ]
            ],
            'parceiro_loja'            => [
                'titulo'    => 'Loja',
                'permissao' => [
                    'parceiro_loja_index'              => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'parceiro_loja:listar'
                    ],
                    'parceiro_loja_add'                => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'parceiro_loja:salvar'
                    ],
                    'parceiro_loja_visualizar'         => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => [
                            'parceiro_loja:buscar', 'endereco:buscar', 'endereco:listar',
                            'endereco:salvar', 'endereco:atualizar', 'endereco:deletar',
                            'contato:buscar', 'contato:listar', 'contato:salvar',
                            'contato:atualizar', 'contato:deletar', 'data:listar'
                        ]
                    ],
                    'parceiro_loja_editar'             => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['parceiro_loja:atualizar', 'parceiro_loja:buscar']
                    ],
                    'parceiro_loja_deletar'            => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'parceiro_loja:deletar'
                    ],
                    'parceiro_loja_download'           => [
                        'titulo' => Titulo::DOWNLOAD,
                        'scope'  => ['parceiro_loja:download', 'mensageria:salvar']
                    ],
                    'parceiro_loja_status'             => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => ['parceiro_loja:atualizar', 'parceiro_loja:buscar']
                    ],
                    'parceiro_loja_empresa'            => Titulo::EMPRESA,
                    'parceiro_loja_historico_download' => Titulo::DOWNLOAD_HISTORICO
                ]
            ],
            'parceiro_campanha'        => [
                'titulo'    => 'Parceiro campanha',
                'permissao' => [
                    'parceiro_campanha_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'parceiro_campanha:listar'
                    ],
                    'parceiro_campanha_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'parceiro_campanha:salvar'
                    ],
                    'parceiro_campanha_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['parceiro_campanha:atualizar', 'parceiro_campanha:buscar']
                    ],
                    'parceiro_campanha_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'parceiro_campanha:deletar'
                    ]
                ]
            ],
            'parceiro_externo'         => [
                'titulo'    => 'Loja externo',
                'permissao' => [
                    'parceiro_externo_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'parceiro_externo:listar'
                    ],
                    'parceiro_externo_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'parceiro_externo:salvar'
                    ],
                    'parceiro_externo_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'parceiro_externo:buscar'
                    ],
                    'parceiro_externo_download'   => [
                        'titulo' => Titulo::DOWNLOAD,
                        'scope'  => ['parceiro_externo:download', 'mensageria:salvar']
                    ],
                    'parceiro_externo_equipe'     => Titulo::EQUIPE,
                    'parceiro_externo_empresa'    => Titulo::EMPRESA
                ]
            ],
            'parceiro_equipe'          => [
                'titulo'    => 'Sem captador',
                'permissao' => [
                    'parceiro_equipe_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'parceiro_equipe:listar'
                    ],
                    'parceiro_equipe_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'parceiro_equipe:buscar'
                    ]
                ]
            ],
            'parceiro_cupom'           => [
                'titulo'    => 'Cupom',
                'permissao' => [
                    'parceiro_cupom_index'  => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'parceiro_cupom:listar'
                    ],
                    'parceiro_cupom_status' => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => ['parceiro_cupom:atualizar', 'parceiro_cupom:buscar']
                    ]
                ]
            ],
            'parceiro_easylive'        => [
                'titulo'    => 'Easylive',
                'permissao' => [
                    'parceiro_easylive_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'parceiro_easylive:listar'
                    ],
                    'parceiro_easylive_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'parceiro_easylive:salvar'
                    ],
                    'parceiro_easylive_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['parceiro_easylive:atualizar', 'parceiro_easylive:buscar']
                    ],
                    'parceiro_easylive_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'parceiro_easylive:deletar'
                    ]
                ]
            ],
            'parceiro_automovel'       => [
                'titulo'    => 'Automóvel',
                'permissao' => [
                    'parceiro_automovel_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => [
                            'parceiro_automovel:listar', 'automovel_modelo:listar',
                            'parceiro_automovel:deletar'
                        ]
                    ],
                    'parceiro_automovel_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => [
                            'parceiro_automovel:buscar', 'automovel_modelo:buscar',
                            'automovel_versao:buscar', 'automovel_versao:salvar',
                            'automovel_versao:atualizar', 'automovel_versao:deletar'
                        ]
                    ],
                    'parceiro_automovel_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => ['parceiro_automovel:salvar', 'automovel_modelo:buscar']
                    ],
                    'parceiro_automovel_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['automovel_modelo:atualizar', 'automovel_modelo:buscar']
                    ],
                    'parceiro_automovel_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'automovel_modelo:deletar'
                    ]
                ]
            ],
            'relatorio_acesso'         => [
                'titulo'    => 'Relatório Acesso',
                'permissao' => [
                    'relatorio_acesso_index'      => [
                        'titulo' => 'Relatório de acesso',
                        'scope'  => ['relatorio_acesso:listar']
                    ],
                    'relatorio_acesso_empresa'    => Titulo::EMPRESA,
                    'relatorio_acesso_subempresa' => 'Todas as Subempresas'
                ]
            ],
            'relatorio_usuario'        => [
                'titulo'    => 'Relatório de usuário',
                'permissao' => [
                    'relatorio_usuario_index'      => [
                        'titulo' => 'Relatório de usuário',
                        'scope'  => ['relatorio_usuario:listar']
                    ],
                    'relatorio_usuario_empresa'    => Titulo::EMPRESA,
                    'relatorio_usuario_subempresa' => 'Todas as Subempresas'
                ]
            ],
            'relatorio_loja_venda'     => [
                'titulo'    => 'Relatório de vendas',
                'permissao' => [
                    'relatorio_loja_venda_index'      => [
                        'titulo' => 'Relatório de vendas',
                        'scope'  => ['relatorio_loja_venda:listar']
                    ],
                    'relatorio_loja_venda_empresa'    => Titulo::EMPRESA,
                    'relatorio_loja_venda_subempresa' => 'Todas as Subempresas',
                    'relatorio_loja_venda_parceiro'   => 'Todos os parceiros'
                ]
            ],
            'tabela_usuario'           => [
                'titulo'    => 'Tabela de Usuário',
                'permissao' => [
                    'tabela_usuario_salvar'   => [
                        'titulo' => Titulo::SALVAR,
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
                    'tabela_usuario_empresa'  => Titulo::EMPRESA
                ]
            ],
            'solicitacao_loja'         => [
                'titulo'    => 'Solicitação Loja',
                'permissao' => [
                    'solicitacao_loja_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_loja:listar'
                    ],
                    'solicitacao_loja_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'solicitacao_loja:buscar'
                    ],
                    'solicitacao_loja_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'solicitacao_loja:salvar'
                    ],
                    'solicitacao_loja_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => 'solicitacao_loja:atualizar'
                    ],
                    'solicitacao_loja_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'solicitacao_loja:deletar'
                    ],
                    'solicitacao_loja_download'   => [
                        'titulo' => Titulo::DOWNLOAD,
                        'scope'  => ['solicitacao_loja:download', 'mensageria:salvar']
                    ],
                    'solicitacao_loja_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => ['solicitacao_loja:atualizar', 'solicitacao_loja:buscar']
                    ],
                    'solicitacao_loja_empresa'    => Titulo::EMPRESA
                ]
            ],
            'solicitacao_voucher'      => [
                'titulo'    => 'Solicitação Voucher',
                'permissao' => [
                    'solicitacao_voucher_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_voucher:listar'
                    ],
                    'solicitacao_voucher_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'solicitacao_voucher:buscar'
                    ],
                    'solicitacao_voucher_download'   => [
                        'titulo' => Titulo::DOWNLOAD,
                        'scope'  => ['solicitacao_voucher:download', 'mensageria:salvar']
                    ],
                    'solicitacao_voucher_empresa'    => Titulo::EMPRESA
                ]
            ],
            'solicitacao_premium'      => [
                'titulo'    => 'Solicitacação Voucher Premium',
                'permissao' => [
                    'solicitacao_premium_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_premium:listar'
                    ],
                    'solicitacao_premium_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'solicitacao_premium:buscar'
                    ],
                    'solicitacao_premium_download'   => [
                        'titulo' => Titulo::DOWNLOAD,
                        'scope'  => ['solicitacao_premium:download', 'mensageria:salvar']
                    ],
                    'solicitacao_premium_empresa'    => Titulo::EMPRESA
                ]
            ],
            'solicitacao_salavip'      => [
                'titulo'    => 'Solicitação Salavip',
                'permissao' => [
                    'solicitacao_salavip_index'    => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_salavip:listar'
                    ],
                    'solicitacao_salavip_download' => [
                        'titulo' => Titulo::DOWNLOAD,
                        'scope'  => ['solicitacao_salavip:download', 'mensageria:salvar']
                    ],
                    'solicitacao_salavip_empresa'  => Titulo::EMPRESA
                ]
            ],
            'solicitacao_declaracao'   => [
                'titulo'    => 'Solicitação Declaração',
                'permissao' => [
                    'solicitacao_declaracao_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_declaracao:listar'
                    ],
                    'solicitacao_declaracao_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'solicitacao_declaracao:buscar'
                    ],
                    'solicitacao_declaracao_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => ['solicitacao_declaracao:atualizar', 'solicitacao_declaracao:buscar']
                    ],
                    'solicitacao_declaracao_empresa'    => Titulo::EMPRESA
                ]
            ],
            'solicitacao_automovel'    => [
                'titulo'    => 'Solicitação Automóvel',
                'permissao' => [
                    'solicitacao_automovel_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_automovel:listar'
                    ],
                    'solicitacao_automovel_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'solicitacao_automovel:buscar'
                    ],
                    'solicitacao_automovel_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => ['solicitacao_automovel:atualizar', 'solicitacao_automovel:buscar']
                    ],
                    'solicitacao_automovel_empresa'    => Titulo::EMPRESA
                ]
            ],
            'solicitacao_cheque_bonus' => [
                'titulo'    => 'Solicitação Cheque Bônus',
                'permissao' => [
                    'solicitacao_cheque_bonus_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_cheque_bonus:listar'
                    ],
                    'solicitacao_cheque_bonus_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'solicitacao_cheque_bonus:buscar'
                    ],
                    'solicitacao_cheque_bonus_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => 'solicitacao_cheque_bonus:atualizar'
                    ],
                    'solicitacao_cheque_bonus_empresa'    => Titulo::EMPRESA
                ]
            ],
            'solicitacao_credito'      => [
                'titulo'    => 'Solicitação Crédito',
                'permissao' => [
                    'solicitacao_credito_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_credito:listar'
                    ],
                    'solicitacao_credito_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'solicitacao_credito:buscar'
                    ],
                    'solicitacao_credito_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => 'solicitacao_credito:atualizar'
                    ],
                    'solicitacao_credito_empresa'    => Titulo::EMPRESA
                ]
            ],
            'solicitacao_codigo'       => [
                'titulo'    => 'Solicitação Código',
                'permissao' => [
                    'solicitacao_codigo_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'solicitacao_codigo:listar'
                    ],
                    'solicitacao_codigo_empresa' => Titulo::EMPRESA
                ]
            ],
            'saude_contratacao'        => [
                'titulo'    => 'Saúde Contratação',
                'permissao' => [
                    'saude_contratacao_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'saude_contratacao:listar'
                    ],
                    'saude_contratacao_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'saude_contratacao:buscar'
                    ],
                    'saude_contratacao_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => 'saude_contratacao:atualizar'
                    ],
                    'saude_contratacao_empresa'    => Titulo::EMPRESA
                ]
            ],
            'comercial_empresa'        => [
                'titulo'    => 'Comercial Empresa',
                'permissao' => [
                    'comercial_empresa_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comercial_empresa:listar'
                    ],
                    'comercial_empresa_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'comercial_empresa:buscar'
                    ],
                    'comercial_empresa_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['comercial_empresa:atualizar', 'comercial_empresa:buscar']
                    ],
                    'comercial_empresa_download'   => [
                        'titulo' => Titulo::DOWNLOAD,
                        'scope'  => ['comercial_empresa:download', 'mensageria:salvar']
                    ],
                    'comercial_empresa_gerente'    => 'Gerente'
                ]
            ],
            'comercial_subempresa'     => [
                'titulo'    => 'Comercial Subempresa',
                'permissao' => [
                    'comercial_subempresa_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comercial_subempresa:listar'
                    ],
                    'comercial_subempresa_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'comercial_subempresa:buscar'
                    ],
                    'comercial_subempresa_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'comercial_subempresa:salvar'
                    ],
                    'comercial_subempresa_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['comercial_subempresa:atualizar', 'comercial_subempresa:buscar']
                    ],
                    'comercial_subempresa_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'comercial_subempresa:deletar'
                    ],
                    'comercial_subempresa_empresa'    => Titulo::EMPRESA
                ]
            ],
            'comercial_contrato'       => [
                'titulo'    => 'Contratos',
                'permissao' => [
                    'comercial_contrato_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comercial_empresa:listar'
                    ],
                    'comercial_contrato_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'comercial_empresa:buscar'
                    ],
                    'comercial_contrato_download'   => [
                        'titulo' => Titulo::DOWNLOAD,
                        'scope'  => ['comercial_empresa:download', 'mensageria:salvar']
                    ]
                ]
            ],
            'comercial_prospeccao'     => [
                'titulo'    => 'Comercial Prospecção',
                'permissao' => [
                    'comercial_prospeccao_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comercial_empresa:listar'
                    ],
                    'comercial_prospeccao_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'comercial_empresa:buscar'
                    ],
                    'comercial_prospeccao_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'comercial_empresa:salvar'
                    ],
                    'comercial_prospeccao_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['comercial_empresa:atualizar', 'comercial_empresa:buscar']
                    ]
                ]
            ],
            'comercial_responsavel'    => [
                'titulo'    => 'Contrato s/Responsável',
                'permissao' => [
                    'comercial_responsavel_index'  => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comercial_empresa:listar'
                    ],
                    'comercial_responsavel_editar' => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['comercial_empresa:atualizar', 'comercial_empresa:buscar']
                    ]
                ]
            ],
            'comercial_perdido'        => [
                'titulo'    => 'Comercial Perdidos',
                'permissao' => [
                    'comercial_perdido_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comercial_perdido:listar'
                    ],
                    'comercial_perdido_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'comercial_perdido:buscar'
                    ],
                    'comercial_perdido_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['comercial_perdido:atualizar', 'comercial_perdido:buscar']
                    ],
                    'comercial_perdido_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => 'comercial_perdido:atualizar'
                    ]
                ]
            ],
            'comercial_regra'          => [
                'titulo'    => 'Comercial Regra de Negócio',
                'permissao' => [
                    'comercial_regra_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'comercial_regra:listar'
                    ],
                    'comercial_regra_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'comercial_regra:buscar'
                    ],
                    'comercial_regra_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'comercial_regra:salvar'
                    ],
                    'comercial_regra_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['comercial_regra:atualizar', 'comercial_regra:buscar']
                    ],
                    'comercial_regra_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'comercial_regra:deletar'
                    ]
                ]
            ],
            'carteirinha'              => [
                'titulo'    => 'Carteirinha',
                'permissao' => [
                    'carteirinha_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'carteirinha:listar'
                    ],
                    'carteirinha_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'carteirinha:salvar'
                    ],
                    'carteirinha_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['carteirinha:atualizar', 'carteirinha:buscar']
                    ],
                    'carteirinha_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'carteirinha:deletar'
                    ],
                    'carteirinha_status'  => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => 'carteirinha:atualizar'
                    ],
                    'carteirinha_empresa' => Titulo::EMPRESA
                ]
            ],
            'demanda_sprint'           => [
                'titulo'    => 'Sprint Backlog',
                'permissao' => [
                    'demanda_sprint_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'demanda_sprint:listar'
                    ],
                    'demanda_sprint_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'demanda_sprint:salvar'
                    ],
                    'demanda_sprint_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'demanda_sprint:buscar'
                    ],
                    'demanda_sprint_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['demanda_sprint:atualizar', 'demanda_sprint:buscar']
                    ],
                    'demanda_sprint_status'     => [
                        'titulo' => Titulo::STATUS,
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
                            'demanda_dado:buscar', 'demanda_dado:cancelar',
                            'demanda_sprint:demanda', 'comercial_empresa:perfil',
                            'demanda_sprint:salvar', 'demanda_tarefa:listar',
                            'demanda_tarefa:like', 'demanda_tarefa:atualizar',
                            'demanda_tarefa:deletar', 'usuario_equipe:perfil',
                            'demanda_tarefa:salvar',
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
                            'demanda_dado:buscar', 'demanda_dado:cancelar',
                            'demanda_sprint:demanda', 'comercial_empresa:perfil',
                            'demanda_sprint:salvar', 'demanda_tarefa:listar',
                            'demanda_tarefa:like', 'demanda_tarefa:atualizar',
                            'demanda_tarefa:deletar', 'usuario_equipe:perfil',
                            'demanda_tarefa:salvar',
                        ]
                    ],
                    'demanda_criacao'    => [
                        'titulo' => 'Criação',
                        'scope'  => [
                            'demanda_dado:listar', 'demanda_dado:salvar',
                            'demanda_dado:atualizar', 'demanda_dado:deletar',
                            'demanda_dado:buscar', 'demanda_dado:cancelar',
                            'demanda_sprint:demanda', 'comercial_empresa:perfil',
                            'demanda_sprint:salvar', 'demanda_tarefa:listar',
                            'demanda_tarefa:like', 'demanda_tarefa:atualizar',
                            'demanda_tarefa:deletar', 'usuario_equipe:perfil',
                            'demanda_tarefa:salvar',
                        ]
                    ],
                    'demanda_convenio'   => [
                        'titulo' => 'Convênios',
                        'scope'  => [
                            'demanda_dado:listar', 'demanda_dado:salvar',
                            'demanda_dado:atualizar', 'demanda_dado:deletar',
                            'demanda_dado:buscar', 'demanda_dado:cancelar',
                            'demanda_sprint:demanda', 'comercial_empresa:perfil',
                            'demanda_sprint:salvar', 'demanda_tarefa:listar',
                            'demanda_tarefa:like', 'demanda_tarefa:atualizar',
                            'demanda_tarefa:deletar', 'usuario_equipe:perfil',
                            'demanda_tarefa:salvar',
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
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'painel_config:listar'
                    ],
                    'painel_config_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'painel_config:salvar'
                    ],
                    'painel_config_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['painel_config:atualizar', 'painel_config:buscar']
                    ],
                    'painel_config_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'painel_config:deletar'
                    ]
                ]
            ],
            'album_dado'               => [
                'titulo'    => 'Album de fotos',
                'permissao' => [
                    'album_dado_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'album_dado:listar'
                    ],
                    'album_dado_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => ['album_dado:buscar', 'album_dado:foto']
                    ],
                    'album_dado_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'album_dado:salvar'
                    ],
                    'album_dado_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['album_dado:atualizar', 'album_dado:buscar']
                    ],
                    'album_dado_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'album_dado:deletar'
                    ],
                    'album_dado_empresa'    => Titulo::EMPRESA
                ]
            ],
            'votacao'                  => [
                'titulo'    => 'Votação',
                'permissao' => [
                    'votacao_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'votacao:listar'
                    ],
                    'votacao_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => [
                            'votacao:buscar', 'votacao:atualizar', 'votacao_pergunta:atualizar',
                            'votacao_pergunta:atualizar', 'votacao_pergunta:buscar', 'votacao_pergunta:deletar',
                            'votacao_pergunta:listar', 'votacao_pergunta:salvar', 'votacao_resposta:atualizar',
                            'votacao_resposta:buscar', 'votacao_resposta:deletar', 'votacao_resposta:listar',
                            'votacao_resposta:salvar', 'votacao_dado:resultado'
                        ]
                    ],
                    'votacao_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'votacao:salvar'
                    ],
                    'votacao_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['votacao:atualizar', 'votacao:buscar']
                    ],
                    'votacao_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'votacao:deletar'
                    ],
                ]
            ],
            'enquete'                  => [
                'titulo'    => 'Enquete',
                'permissao' => [
                    'enquete_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'votacao_dado:listar'
                    ],
                    'enquete_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => [
                            'votacao:buscar', 'votacao:atualizar', 'votacao_pergunta:atualizar',
                            'votacao_pergunta:atualizar', 'votacao_pergunta:buscar', 'votacao_pergunta:deletar',
                            'votacao_pergunta:listar', 'votacao_pergunta:salvar', 'votacao_resposta:atualizar',
                            'votacao_resposta:buscar', 'votacao_resposta:deletar', 'votacao_resposta:listar',
                            'votacao_resposta:salvar'
                        ]
                    ],
                    'enquete_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'votacao_dado:salvar'
                    ],
                    'enquete_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['votacao_dado:atualizar', 'votacao_dado:buscar']
                    ],
                    'enquete_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'votacao_dado:deletar'
                    ],
                ]
            ],
            'site_config'              => [
                'titulo'    => 'Configurações do Site',
                'permissao' => [
                    'site_config_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'site_config:listar'
                    ],
                    'site_config_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'site_config:buscar'
                    ],
                    'site_config_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'site_config:salvar'
                    ],
                    'site_config_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['site_config:atualizar', 'site_config:buscar']
                    ],
                    'site_config_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'site_config:deletar'
                    ],
                    'site_config_empresa'    => Titulo::EMPRESA
                ]
            ],
            'site_menu'                => [
                'titulo'    => 'Configurações do Menu (Site)',
                'permissao' => [
                    'site_menu_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'site_menu:listar',
                    ],
                    'site_menu_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'site_menu:buscar'
                    ],
                    'site_menu_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'site_menu:salvar'
                    ],
                    'site_menu_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['site_menu:atualizar', 'site_menu:buscar']
                    ],
                    'site_menu_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'site_menu:deletar'
                    ],
                    'site_menu_empresa'    => Titulo::EMPRESA
                ]
            ],
            'site_lotacao'             => [
                'titulo'    => 'Lotação',
                'permissao' => [
                    'site_lotacao_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'site_lotacao:listar'
                    ],
                    'site_lotacao_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'site_lotacao:salvar'
                    ],
                    'site_lotacao_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['site_lotacao:atualizar', 'site_lotacao:buscar']
                    ],
                    'site_lotacao_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'site_lotacao:deletar'
                    ],
                    'site_lotacao_empresa' => Titulo::EMPRESA
                ]
            ],
            'usuario_grupo'            => [
                'titulo'    => 'Grupo',
                'permissao' => [
                    'usuario_grupo_index'   => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'usuario_grupo:listar'
                    ],
                    'usuario_grupo_add'     => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'usuario_grupo:salvar'
                    ],
                    'usuario_grupo_editar'  => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['usuario_grupo:atualizar', 'usuario_grupo:buscar']
                    ],
                    'usuario_grupo_deletar' => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'usuario_grupo:deletar'
                    ],
                    'usuario_grupo_empresa' => Titulo::EMPRESA
                ]
            ],
            'view_pagina'              => [
                'titulo'    => 'View Página',
                'permissao' => [
                    'view_pagina_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'view_pagina:listar'
                    ],
                    'view_pagina_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => ['view_pagina:buscar', 'view_html:listar']
                    ],
                    'view_pagina_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'view_pagina:salvar'
                    ],
                    'view_pagina_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['view_pagina:atualizar', 'view_pagina:buscar']
                    ],
                    'view_pagina_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'view_pagina:deletar'
                    ]
                ]
            ],
            'silium_comissao'          => [
                'titulo'    => 'Comissões do Silium (Cashback)',
                'permissao' => [
                    'silium_comissao_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'silium_comissao:listar',
                    ],
                    'silium_comissao_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'silium_comissao:buscar',
                    ],
                    'silium_comissao_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'silium_comissao:salvar'
                    ],
                    'silium_comissao_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['silium_comissao:atualizar', 'silium_comissao:buscar']
                    ],
                    'silium_comissao_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => 'silium_comissao:atualizar'
                    ],
                    'silium_comissao_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'silium_comissao:deletar'
                    ],
                ]
            ],
            'silium_deposito'          => [
                'titulo'    => 'Depósitos do Silium (Cashback)',
                'permissao' => [
                    'silium_deposito_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'silium_deposito:listar',
                    ],
                    'silium_deposito_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'silium_deposito:buscar',
                    ],
                    'silium_deposito_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'silium_deposito:salvar'
                    ],
                    'silium_deposito_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['silium_deposito:atualizar', 'silium_deposito:buscar']
                    ],
                    'silium_deposito_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => 'silium_deposito:atualizar'
                    ],
                    'silium_deposito_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'silium_deposito:deletar'
                    ]
                ]
            ],
            'silium_saque'             => [
                'titulo'    => 'Solicitações de Saque (Cashback)',
                'permissao' => [
                    'silium_saque_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'silium_saque:listar'
                    ],
                    'silium_saque_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'silium_saque:buscar'
                    ],
                    'silium_saque_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'silium_saque:salvar'
                    ],
                    'silium_saque_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['silium_saque:atualizar', 'silium_saque:buscar']
                    ],
                    'silium_saque_status'     => [
                        'titulo' => Titulo::STATUS,
                        'scope'  => 'silium_deposito:atualizar'
                    ],
                    'silium_saque_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'silium_saque:deletar'
                    ]
                ]
            ],
            'silium_saldo'             => [
                'titulo'    => 'Ranking de Silium (Cashback)',
                'permissao' => [
                    'silium_saldo_index' => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'silium_saldo:listar'
                    ]
                ]
            ],
            'silium_config'            => [
                'titulo'    => 'Configurações do Silium (Cashback)',
                'permissao' => [
                    'silium_config_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'silium_config:listar'
                    ],
                    'silium_config_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'silium_config:buscar'
                    ],
                    'silium_config_add'        => [
                        'titulo' => Titulo::SALVAR,
                        'scope'  => 'silium_config:salvar'
                    ],
                    'silium_config_editar'     => [
                        'titulo' => Titulo::EDITAR,
                        'scope'  => ['silium_config:atualizar', 'silium_config:buscar']
                    ],
                    'silium_config_deletar'    => [
                        'titulo' => Titulo::DELETAR,
                        'scope'  => 'silium_config:deletar'
                    ]
                ]
            ],
            'enquete_mercado'          => [
                'titulo'    => 'Enquete de Mercado',
                'permissao' => [
                    'enquete_mercado_index'      => [
                        'titulo' => Titulo::LISTAR,
                        'scope'  => 'enquete_mercado:listar'
                    ],
                    'enquete_mercado_visualizar' => [
                        'titulo' => Titulo::VISUALIZAR,
                        'scope'  => 'enquete_mercado:buscar'
                    ],
                    'enquete_mercado_empresa'    => Titulo::EMPRESA
                ]
            ]
        ];
    }

    /**
     * Monta o array de permissao
     *
     * @param string       $titulo        Título para a permissão
     * @param string       $indice        Indice da permissao ex.: usuario_cliente
     * @param bool         $index         Se vai ter index no painel
     * @param bool         $visualizar    Se vai ter visualizar no painel
     * @param bool         $add           Se vai ter add no painel
     * @param bool         $editar        Se vai ter editar no painel
     * @param bool         $deletar       Se vai ter deletar no painel
     * @param bool         $download      Se vai ter download no painel
     * @param bool         $empresa       Se vai ter empresa no painel
     * @param string|null  $scope         Scope que vai usar ex.: usuario_cliente, se não passar, usa o $indice
     * @param Closure|null $personalizado Função com montarArrayPersonalizado
     *
     * @return self
     */
    private function montarArrayPermissao(
        string $titulo,
        string $indice,
        bool|array $index = false,
        bool|array $visualizar = false,
        bool|array $add = false,
        bool|array $editar = false,
        bool|array $deletar = false,
        bool|array $download = false,
        bool|array $empresa = false,
        string $scope = null,
        ?Closure $personalizado = null
    ): self {
        $scope = !empty($scope) ? $scope : $indice;
        $acaoLista = ['index', 'visualizar', 'add', 'editar', 'deletar', 'download'];

        $lista = [];
        foreach ($acaoLista as $acao) {
            if (true !== $$acao) {
                continue;
            }
            $lista = array_merge($lista, $this->montarIndicePermissao($acao, $indice, $scope, $$acao));
        }

        if ($empresa) {
            $lista = array_merge($lista, [
                $indice . '_empresa' => [
                    'titulo' => Titulo::EMPRESA,
                    'scope'  => is_array($empresa) ? $empresa : []
                ]
            ]);
        }
        if (!is_null($personalizado)) {
            $this->permissaoPersonalizada = [];
            call_user_func($personalizado);
            $lista = array_merge($lista, $this->permissaoPersonalizada);
            $this->permissaoPersonalizada = [];
        }

        $this->PERMISSAO[$indice] = [
            'titulo'    => $titulo,
            'permissao' => $lista
        ];
        return $this;
    }

    private function montarIndicePermissao(string $acao, string $indice, string $scope, bool|array $scopePadrao)
    {
        $nomePermissao = [
            'index'      => ['listar', Titulo::LISTAR],
            'visualizar' => ['buscar', Titulo::VISUALIZAR],
            'add'        => ['salvar', Titulo::SALVAR],
            'editar'     => ['atualizar', Titulo::EDITAR],
            'deletar'    => ['deletar', Titulo::DELETAR],
            'download'   => ['download', Titulo::DOWNLOAD]
        ];

        $scopeTemp = [$scope . ':' . $nomePermissao[$acao][0]];
        if ($acao == 'editar') {
            $scopeTemp = [$scope . ':' . $nomePermissao[$acao][0], $scope . ':buscar'];
        } elseif ($acao == 'download') {
            $scopeTemp = [$scope . ':' . $nomePermissao[$acao][0], 'mensageria:salvar'];
        }

        return [
            $indice . '_' . $acao => [
                'titulo' => $nomePermissao[$acao][1],
                'scope'  => is_array($scopePadrao) && $scopePadrao ? array_merge($scopePadrao, $scopeTemp) : $scopeTemp
            ]
        ];
    }

    /**
     * Monta uma permissão personalizada
     *
     * @param string $indice Indice completo que deseja usar ex.: usuario_cliente_apple
     * @param string $titulo Título que irá aparecer no painel
     * @param array  $scope  Scopes que essa permissão vai usar
     */
    private function montarArrayPersonalizado(string $indice, string $titulo, array $scope): void
    {
        $this->permissaoPersonalizada[$indice] = [
            'titulo' => $titulo,
            'scope'  => $scope
        ];
    }
}
