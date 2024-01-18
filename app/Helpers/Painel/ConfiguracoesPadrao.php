<?php

namespace App\Helpers\Painel;

final class ConfiguracoesPadrao
{
    public const RECURSOS = [
        'perfil', 'agenda', 'google'
    ];
    public const CAMPOS_OBRIGATORIOS = [
        'usuario_cliente' => [
            'cpf', 'email', 'status'
        ]
    ];
    public const PERMISSOES = [
        'usuario_cliente'          => [
            'titulo'    => 'Cliente',
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
            'titulo'    => 'Grupo',
            'permissao' => [
                'usuario_grupo_index'   => 'Listar',
                'usuario_grupo_add'     => 'Salvar',
                'usuario_grupo_editar'  => 'Editar',
                'usuario_grupo_deletar' => 'Deletar'
            ]
        ],
        'usuario_dependente'       => [
            'titulo'    => 'Dependente',
            'permissao' => [
                'usuario_dependente_index'   => 'Listar',
                'usuario_dependente_add'     => 'Salvar',
                'usuario_dependente_editar'  => 'Editar',
                'usuario_dependente_deletar' => 'Deletar'
            ]
        ],
        'usuario_indicacao'        => [
            'titulo'    => 'Indicação',
            'permissao' => [
                'usuario_indicacao_index'      => 'Listar',
                'usuario_indicacao_visualizar' => 'Visualizar',
                'usuario_indicacao_status'     => 'Status',
                'usuario_indicacao_empresa'    => 'Todas as empresas'
            ]
        ],
        'usuario_lead'             => [
            'titulo'    => 'Lead',
            'permissao' => [
                'usuario_lead_index'      => 'Listar',
                'usuario_lead_visualizar' => 'Visualizar',
                'usuario_lead_status'     => 'Status',
                'usuario_lead_empresa'    => 'Todas as empresas'
            ]
        ],
        'usuario_equipe'           => [
            'titulo'    => 'Equipe',
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
            'titulo'    => 'Banner Login',
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
            'permissao' => [
                'comunicacao_publicidade_index'   => 'Listar',
                'comunicacao_publicidade_add'     => 'Salvar',
                'comunicacao_publicidade_editar'  => 'Editar',
                'comunicacao_publicidade_deletar' => 'Deletar',
                'comunicacao_publicidade_empresa' => 'Todas as empresas'
            ]
        ],
        'solicitacao_contato'      => [
            'titulo'    => 'Contato',
            'permissao' => [
                'solicitacao_contato_index'      => 'Listar',
                'solicitacao_contato_visualizar' => 'Visualizar',
                'solicitacao_contato_status'     => 'Status',
                'solicitacao_contato_empresa'    => 'Todas as empresas'
            ]
        ],
        'comercial_popup'          => [
            'titulo'    => 'Popup',
            'permissao' => [
                'comercial_popup_index'   => 'Listar',
                'comercial_popup_add'     => 'Salvar',
                'comercial_popup_editar'  => 'Editar',
                'comercial_popup_deletar' => 'Deletar',
                'comercial_popup_status'  => 'Status',
                'comercial_popup_empresa' => 'Todas as empresas'
            ]
        ],
        'enquete_satisfacao'       => [
            'titulo'    => 'Pesquisa Satisfação',
            'permissao' => [
                'enquete_satisfacao_index'      => 'Listar',
                'enquete_satisfacao_visualizar' => 'Visualizar',
                'enquete_satisfacao_deletar'    => 'Deletar',
                'enquete_satisfacao_status'     => 'Status',
                'enquete_satisfacao_empresa'    => 'Todas as empresas'
            ]
        ],
        'construtor_clube'         => [
            'titulo'    => 'Clube',
            'permissao' => [
                'construtor_clube_index'   => 'Listar',
                'construtor_clube_add'     => 'Salvar',
                'construtor_clube_editar'  => 'Editar',
                'construtor_clube_deletar' => 'Deletar',
                'construtor_clube_empresa' => 'Todas as empresas'
            ]
        ],
        'publicacao_noticia'       => [
            'titulo'    => 'Notícia',
            'permissao' => [
                'publicacao_noticia_index'   => 'Listar',
                'publicacao_noticia_add'     => 'Salvar',
                'publicacao_noticia_editar'  => 'Editar',
                'publicacao_noticia_deletar' => 'Deletar'
            ]
        ],
        'publicacao_pagina'        => [
            'titulo'    => 'Páginas',
            'permissao' => [
                'publicacao_pagina_index'  => 'Listar',
                'publicacao_pagina_editar' => 'Editar',
            ]
        ],
        'publicacao_diretoria'     => [
            'titulo'    => 'Diretoria',
            'permissao' => [
                'publicacao_diretoria_index'   => 'Listar',
                'publicacao_diretoria_add'     => 'Salvar',
                'publicacao_diretoria_editar'  => 'Editar',
                'publicacao_diretoria_deletar' => 'Deletar'
            ]
        ],
        'texto_clube'              => [
            'titulo'    => 'Texto do clube',
            'permissao' => [
                'texto_clube_index'   => 'Listar',
                'texto_clube_add'     => 'Salvar',
                'texto_clube_editar'  => 'Editar',
                'texto_clube_deletar' => 'Deletar'
            ]
        ],
        'parceiro_relatorio'       => [
            'titulo'    => 'Relatório do parceiro',
            'permissao' => [
                'parceiro_relatorio_index'   => 'Listar',
                'parceiro_relatorio_add'     => 'Salvar',
                'parceiro_relatorio_editar'  => 'Editar',
                'parceiro_relatorio_deletar' => 'Deletar'
            ]
        ],
        'parceiro_loja'            => [
            'titulo'    => 'Loja',
            'permissao' => [
                'parceiro_loja_index'   => 'Listar',
                'parceiro_loja_add'     => 'Salvar',
                'parceiro_loja_editar'  => 'Editar',
                'parceiro_loja_deletar' => 'Deletar',
                'parceiro_loja_empresa' => 'Todas as empresas'
            ]
        ],
        'parceiro_cashback'        => [
            'titulo'    => 'Cashback',
            'permissao' => [
                'parceiro_cashback_index'   => 'Listar',
                'parceiro_cashback_add'     => 'Salvar',
                'parceiro_cashback_editar'  => 'Editar',
                'parceiro_cashback_deletar' => 'Deletar',
                'parceiro_cashback_empresa' => 'Todas as empresas'
            ]
        ],
        'parceiro_cupom'           => [
            'titulo'    => 'Cupom',
            'permissao' => [
                'parceiro_cupom_index'  => 'Listar',
                'parceiro_cupom_status' => 'Status'
            ]
        ],
        'parceiro_easylive'        => [
            'titulo'    => 'Easylive',
            'permissao' => [
                'parceiro_easylive_index'   => 'Listar',
                'parceiro_easylive_add'     => 'Salvar',
                'parceiro_easylive_editar'  => 'Editar',
                'parceiro_easylive_deletar' => 'Deletar'
            ]
        ],
        'parceiro_automovel'       => [
            'titulo'    => 'Automóvel',
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
            'permissao' => [
                'solicitacao_loja_index'      => 'Listar',
                'solicitacao_loja_visualizar' => 'Visualizar',
                'solicitacao_loja_add'        => 'Salvar',
                'solicitacao_loja_deletar'    => 'Deletar',
                'solicitacao_loja_status'     => 'Status',
                'solicitacao_loja_empresa'    => 'Todas as empresas',
            ]
        ],
        'solicitacao_voucher'      => [
            'titulo'    => 'Voucher',
            'permissao' => [
                'solicitacao_voucher_index'      => 'Listar',
                'solicitacao_voucher_visualizar' => 'Visualizar',
                'solicitacao_voucher_download'   => 'Download',
                'solicitacao_voucher_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_premium'      => [
            'titulo'    => 'Voucher Premium',
            'permissao' => [
                'solicitacao_premium_index'      => 'Listar',
                'solicitacao_premium_visualizar' => 'Visualizar',
                'solicitacao_premium_download'   => 'Download',
                'solicitacao_premium_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_salavip'      => [
            'titulo'    => 'Salavip',
            'permissao' => [
                'solicitacao_salavip_index'    => 'Listar',
                'solicitacao_salavip_download' => 'Download',
                'solicitacao_salavip_empresa'  => 'Todas as empresas'
            ]
        ],
        'solicitacao_declaracao'   => [
            'titulo'    => 'Declaração',
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
            'titulo'    => 'Cheque Bônus',
            'permissao' => [
                'solicitacao_cheque_bonus_index'      => 'Listar',
                'solicitacao_cheque_bonus_visualizar' => 'Visualizar',
                'solicitacao_cheque_bonus_status'     => 'Status',
                'solicitacao_cheque_bonus_empresa'    => 'Todas as empresas'
            ]
        ],
        'solicitacao_credito'      => [
            'titulo'    => 'Crédito',
            'permissao' => [
                'solicitacao_credito_index'      => 'Listar',
                'solicitacao_credito_visualizar' => 'Visualizar',
                'solicitacao_credito_status'     => 'Status',
                'solicitacao_credito_empresa'    => 'Todas as empresas'
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
                'painel_config_index'  => 'Listar',
                'painel_config_add'    => 'Salvar',
                'painel_config_editar' => 'Editar'
            ]
        ]
    ];
}
