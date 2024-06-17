<?php

use App\Helpers\Painel\ConfiguracoesPadrao;

function pegarPermissao($lista)
{
    $retorno = [];
    foreach ($lista as $r) {
        $retorno = array_merge($retorno, array_keys($r['permissao']));
    }
    return $retorno;
}

$permissaoPadrao = pegarPermissao(ConfiguracoesPadrao::PERMISSOES);

return [
    [
        'id_admin_empresa'  => 1,
        'titulo'            => 'Markt Club',
        'permissao'         => $permissaoPadrao,
        'configuracao'      => array_keys(ConfiguracoesPadrao::RECURSOS),
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
                    'nome', 'cpf', 'genero', 'data_nascimento', 'email_trabalho',
                    'senha', 'status', 'primeiro_acesso', 'email_trabalho',
                    'email_pessoal', 'telefone_trabalho', 'telefone_pessoal',
                    'mudar_senha', 'permissao', 'empresa', 'subempresa'
                ]
            ]
        ],
        'campo_obrigatorio' => ConfiguracoesPadrao::CAMPOS_OBRIGATORIOS,
        'upload_grupo'      => ConfiguracoesPadrao::UPLOAD_GRUPO
    ],
    [
        'id_admin_empresa'  => 2,
        'titulo'            => 'Anafe Card',
        'permissao'         => [
            'usuario_equipe_index',
            'usuario_equipe_add',
            'usuario_equipe_editar',
            'usuario_equipe_deletar',
            'usuario_equipe_permissao',
            'usuario_cliente_index',
            'usuario_cliente_visualizar',
            'usuario_cliente_add',
            'usuario_cliente_editar',
            'usuario_cliente_deletar',
            'usuario_cliente_download',
            'usuario_indicacao_index',
            'usuario_indicacao_visualizar',
            'usuario_indicacao_status',
            'usuario_lead_index',
            'usuario_lead_visualizar',
            'usuario_lead_status',
            'relatorio_acesso_index',
            'relatorio_acesso_empresa',
            'relatorio_usuario_index',
            'relatorio_usuario_empresa',
            'tabela_usuario_salvar',
            'tabela_usuario_bloquear',
            'solicitacao_voucher_index',
            'solicitacao_voucher_visualizar',
            'solicitacao_salavip_index',
            'solicitacao_salavip_download',
        ],
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
        'titulo'            => 'APCF Card',
        'permissao'         => [
            'usuario_equipe_index',
            'usuario_equipe_add',
            'usuario_equipe_editar',
            'usuario_equipe_deletar',
            'usuario_equipe_permissao',
            'usuario_cliente_index',
            'usuario_cliente_visualizar',
            'usuario_cliente_add',
            'usuario_cliente_editar',
            'usuario_cliente_deletar',
            'usuario_cliente_download',
            'usuario_indicacao_index',
            'usuario_indicacao_visualizar',
            'usuario_indicacao_status',
            'usuario_lead_index',
            'usuario_lead_visualizar',
            'usuario_lead_status',
            'relatorio_acesso_index',
            'relatorio_acesso_empresa',
            'relatorio_usuario_index',
            'relatorio_usuario_empresa',
            'tabela_usuario_salvar',
            'tabela_usuario_bloquear',
            'solicitacao_voucher_index',
            'solicitacao_voucher_visualizar',
        ],
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
