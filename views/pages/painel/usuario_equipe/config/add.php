<?php

use App\Classes\UsuarioEquipe\Helper;
use App\Classes\UsuarioEquipe\Status;
use Helpers\ApiHelper;
use Modules\Senha;
use PainelConfig\Add;

$Painel = new Add('usuario_equipe', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados pessoais', callback: function () use ($Painel) {
        $Painel->input(name: 'nome', label: 'Nome completo', obrigatorio: 1);
        $Painel->cpf(name: 'cpf', label: 'CPF', obrigatorio: 1);
        $Painel->select(name: 'genero', lista: 'genero', label: 'Gênero');
    });
    $Painel->fieldset('Contato', callback: function () use ($Painel) {
        $Painel->email(name: 'email_trabalho', label: 'E-mail de trabalho', obrigatorio: 1);
        $Painel->email(name: 'email_pessoal', label: 'E-mail pessoal');
        $Painel->telefone(name: 'telefone_trabalho', label: 'Telefone de trabalho');
        $Painel->telefone(name: 'telefone_pessoal', label: 'Telefone pessoal');
    });
    $Painel->fieldset('Dados de acesso', callback: function () use ($Painel) {
        $subempresaLista = ['' => 'Escolha uma empresa'];
        if (sessao('EMPRESA.slug') == 'marktclub') {
            $Painel
                ->select(
                    name: 'empresa->id',
                    lista: 'empresa',
                    label: 'Empresa',
                    acao: 'add',
                    permissao: Helper::PERMISSAO_EMPRESA
                )
                ->hidden(name: 'empresa->id', acao: 'editar', permissao: Helper::PERMISSAO_EMPRESA);
        } else {
            $subempresaLista = (new ApiHelper(token: true))
                ->json([
                    'titulo'  => 'Escolha uma subempresa',
                    'empresa' => sessao('USUARIO.empresa')
                ])
                ->get('/comercial-subempresa/select')
                ->array()['dado'] ?? [];
            if (empty(sessao('USUARIO.subempresa'))) {
                $Painel
                    ->select(
                        name: 'subempresa',
                        lista: $subempresaLista,
                        label: 'Subempresa',
                    );
            }
        }
        $Painel
            ->senha(
                name: 'senha',
                label: 'Senha de acesso',
                ajuda: Senha::MENSAGEM_FORCA_4
            )
            ->switch(name: 'primeiro_acesso', label: 'Primeiro acesso?')
            ->switch(name: 'mudar_senha', label: 'Mudar senha ao logar?')
            ->select(name: 'status', lista: (new Status())->select('Escolha um status'), label: 'Status');
    });
});

if (
    strCpf(sessao('USUARIO.cpf')) == '014.951.801-31' ||
    (sessao('USUARIO.empresa')->id != '14afa776394ada4be23be6acf7e3259e' && sessao('USUARIO.marktclub') == 'nao')
) {
    $permissoes = sessao('PAINEL.permissao.montar');
    $usuario = [];
    $comunicacao = [];
    $publicacao = [];
    $parceiro = [];
    $relatorio = [];
    $solicitacao = [];
    $comercial = [];
    $demanda = [];
    $painel = [];
    if (!empty($permissoes['usuario_cliente'])) {
        $usuario['usuario_cliente'] = $permissoes['usuario_cliente'];
    }
    if (!empty($permissoes['usuario_grupo'])) {
        $usuario['usuario_grupo'] = $permissoes['usuario_grupo'];
    }
    if (!empty($permissoes['usuario_dependente'])) {
        $usuario['usuario_dependente'] = $permissoes['usuario_dependente'];
    }
    if (!empty($permissoes['usuario_indicacao'])) {
        $usuario['usuario_indicacao'] = $permissoes['usuario_indicacao'];
    }
    if (!empty($permissoes['usuario_lead'])) {
        $usuario['usuario_lead'] = $permissoes['usuario_lead'];
    }
    if (!empty($permissoes['usuario_equipe'])) {
        $usuario['usuario_equipe'] = $permissoes['usuario_equipe'];
    }
    if (!empty($permissoes['comunicacao_login'])) {
        $comunicacao['comunicacao_login'] = $permissoes['comunicacao_login'];
    }
    if (!empty($permissoes['comunicacao_publicidade'])) {
        $comunicacao['comunicacao_publicidade'] = $permissoes['comunicacao_publicidade'];
    }
    if (!empty($permissoes['enquete_satisfacao'])) {
        $comunicacao['enquete_satisfacao'] = $permissoes['enquete_satisfacao'];
    }
    if (!empty($permissoes['construtor_clube'])) {
        $comunicacao['construtor_clube'] = $permissoes['construtor_clube'];
    }
    if (!empty($permissoes['texto_clube'])) {
        $comunicacao['texto_clube'] = $permissoes['texto_clube'];
    }
    if (!empty($permissoes['publicacao_noticia'])) {
        $publicacao['publicacao_noticia'] = $permissoes['publicacao_noticia'];
    }
    if (!empty($permissoes['publicacao_home'])) {
        $publicacao['publicacao_home'] = $permissoes['publicacao_home'];
    }
    if (!empty($permissoes['publicacao_pagina'])) {
        $publicacao['publicacao_pagina'] = $permissoes['publicacao_pagina'];
    }
    if (!empty($permissoes['publicacao_youtube'])) {
        $publicacao['publicacao_youtube'] = $permissoes['publicacao_youtube'];
    }
    if (!empty($permissoes['publicacao_arquivo'])) {
        $publicacao['publicacao_arquivo'] = $permissoes['publicacao_arquivo'];
    }
    if (!empty($permissoes['publicacao_diretoria'])) {
        $publicacao['publicacao_diretoria'] = $permissoes['publicacao_diretoria'];
    }
    if (!empty($permissoes['album_dado'])) {
        $publicacao['album_dado'] = $permissoes['album_dado'];
    }
    if (!empty($permissoes['parceiro_relatorio'])) {
        $parceiro['parceiro_relatorio'] = $permissoes['parceiro_relatorio'];
    }
    if (!empty($permissoes['parceiro_loja'])) {
        $parceiro['parceiro_loja'] = $permissoes['parceiro_loja'];
    }
    if (!empty($permissoes['parceiro_cashback'])) {
        $parceiro['parceiro_cashback'] = $permissoes['parceiro_cashback'];
    }
    if (!empty($permissoes['parceiro_cupom'])) {
        $parceiro['parceiro_cupom'] = $permissoes['parceiro_cupom'];
    }
    if (!empty($permissoes['parceiro_easylive'])) {
        $parceiro['parceiro_easylive'] = $permissoes['parceiro_easylive'];
    }
    if (!empty($permissoes['parceiro_automovel'])) {
        $parceiro['parceiro_automovel'] = $permissoes['parceiro_automovel'];
    }
    if (!empty($permissoes['relatorio_acesso'])) {
        $relatorio['relatorio_acesso'] = $permissoes['relatorio_acesso'];
    }
    if (!empty($permissoes['relatorio_usuario'])) {
        $relatorio['relatorio_usuario'] = $permissoes['relatorio_usuario'];
    }
    if (!empty($permissoes['relatorio_loja_venda'])) {
        $relatorio['relatorio_loja_venda'] = $permissoes['relatorio_loja_venda'];
    }
    if (!empty($permissoes['solicitacao_loja'])) {
        $solicitacao['solicitacao_loja'] = $permissoes['solicitacao_loja'];
    }
    if (!empty($permissoes['solicitacao_voucher'])) {
        $solicitacao['solicitacao_voucher'] = $permissoes['solicitacao_voucher'];
    }
    if (!empty($permissoes['solicitacao_premium'])) {
        $solicitacao['solicitacao_premium'] = $permissoes['solicitacao_premium'];
    }
    if (!empty($permissoes['solicitacao_salavip'])) {
        $solicitacao['solicitacao_salavip'] = $permissoes['solicitacao_salavip'];
    }
    if (!empty($permissoes['solicitacao_declaracao'])) {
        $solicitacao['solicitacao_declaracao'] = $permissoes['solicitacao_declaracao'];
    }
    if (!empty($permissoes['solicitacao_automovel'])) {
        $solicitacao['solicitacao_automovel'] = $permissoes['solicitacao_automovel'];
    }
    if (!empty($permissoes['solicitacao_cheque_bonus'])) {
        $solicitacao['solicitacao_cheque_bonus'] = $permissoes['solicitacao_cheque_bonus'];
    }
    if (!empty($permissoes['solicitacao_credito'])) {
        $solicitacao['solicitacao_credito'] = $permissoes['solicitacao_credito'];
    }
    if (!empty($permissoes['saude_contratacao'])) {
        $solicitacao['saude_contratacao'] = $permissoes['saude_contratacao'];
    }
    if (!empty($permissoes['solicitacao_contato'])) {
        $solicitacao['solicitacao_contato'] = $permissoes['solicitacao_contato'];
    }
    if (!empty($permissoes['comercial_empresa'])) {
        $comercial['comercial_empresa'] = $permissoes['comercial_empresa'];
    }
    if (!empty($permissoes['comercial_subempresa'])) {
        $comercial['comercial_subempresa'] = $permissoes['comercial_subempresa'];
    }
    if (!empty($permissoes['comercial_prospeccao'])) {
        $comercial['comercial_prospeccao'] = $permissoes['comercial_prospeccao'];
    }
    if (!empty($permissoes['comercial_perdido'])) {
        $comercial['comercial_perdido'] = $permissoes['comercial_perdido'];
    }
    if (!empty($permissoes['comercial_atendimento'])) {
        $comercial['comercial_atendimento'] = $permissoes['comercial_atendimento'];
    }
    if (!empty($permissoes['comercial_regra'])) {
        $comercial['comercial_regra'] = $permissoes['comercial_regra'];
    }
    if (!empty($permissoes['carteirinha'])) {
        $comercial['carteirinha'] = $permissoes['carteirinha'];
    }
    if (!empty($permissoes['comercial_popup'])) {
        $comercial['comercial_popup'] = $permissoes['comercial_popup'];
    }
    if (!empty($permissoes['demanda'])) {
        $demanda['demanda'] = $permissoes['demanda'];
    }
    if (!empty($permissoes['log_erro'])) {
        $demanda['log_erro'] = $permissoes['log_erro'];
    }
    if (!empty($permissoes['painel_config'])) {
        $painel['painel_config'] = $permissoes['painel_config'];
    }

    $Painel->coluna(callback: function () use ($Painel, $usuario) {
        $Painel->fieldsetCheckbox(
            titulo: 'Usuário',
            callback: function () use ($Painel, $usuario) {
                foreach ($usuario as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'parceiro'   => 'Todos os Parceiros',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                'foto'       => 'Gerenciar Foto',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });

    $Painel->coluna(callback: function () use ($Painel, $comunicacao) {
        $Painel->fieldsetCheckbox(
            titulo: 'Comunicações',
            callback: function () use ($Painel, $comunicacao) {
                foreach ($comunicacao as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'parceiro'   => 'Todos os Parceiros',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                'foto'       => 'Gerenciar Foto',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });

    $Painel->coluna(callback: function () use ($Painel, $publicacao) {
        $Painel->fieldsetCheckbox(
            titulo: 'Publicações',
            callback: function () use ($Painel, $publicacao) {
                foreach ($publicacao as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'parceiro'   => 'Todos os Parceiros',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                'foto'       => 'Gerenciar Foto',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });

    $Painel->coluna(callback: function () use ($Painel, $parceiro) {
        $Painel->fieldsetCheckbox(
            titulo: 'Parceiros',
            callback: function () use ($Painel, $parceiro) {
                foreach ($parceiro as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'parceiro'   => 'Todos os Parceiros',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                'foto'       => 'Gerenciar Foto',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });

    $Painel->coluna(callback: function () use ($Painel, $relatorio) {
        $Painel->fieldsetCheckbox(
            titulo: 'Relatorios',
            callback: function () use ($Painel, $relatorio) {
                foreach ($relatorio as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'parceiro'   => 'Todos os Parceiros',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                'foto'       => 'Gerenciar Foto',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });

    $Painel->coluna(callback: function () use ($Painel, $solicitacao) {
        $Painel->fieldsetCheckbox(
            titulo: 'Solicitações',
            callback: function () use ($Painel, $solicitacao) {
                foreach ($solicitacao as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'parceiro'   => 'Todos os Parceiros',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                'foto'       => 'Gerenciar Foto',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });

    $Painel->coluna(callback: function () use ($Painel, $comercial) {
        $Painel->fieldsetCheckbox(
            titulo: 'Comercial',
            callback: function () use ($Painel, $comercial) {
                foreach ($comercial as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'parceiro'   => 'Todos os Parceiros',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                'foto'       => 'Gerenciar Foto',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });

    $Painel->coluna(callback: function () use ($Painel, $demanda) {
        $Painel->fieldsetCheckbox(
            titulo: 'Demanda & Logs',
            callback: function () use ($Painel, $demanda) {
                foreach ($demanda as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'parceiro'   => 'Todos os Parceiros',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                'foto'       => 'Gerenciar Foto',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });

    $Painel->coluna(callback: function () use ($Painel, $painel) {
        $Painel->fieldsetCheckbox(
            titulo: 'Configurações do Painel',
            callback: function () use ($Painel, $painel) {
                foreach ($painel as $nomeApp => $configuracoes) {
                    $titulo = $configuracoes['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h4>' . $titulo . '</h4>');
                    }
                    if (array_key_exists('acao', $configuracoes) && !empty($configuracoes['acao'])) {
                        foreach ($configuracoes['acao'] as $permissao) {
                            $label = match ($permissao) {
                                'index'      => 'Listar',
                                'add'        => 'Salvar',
                                'editar'     => 'Editar',
                                'deletar'    => 'Deletar',
                                'status'     => 'Status',
                                'parceiro'   => 'Todos os Parceiros',
                                'empresa'    => 'Todas as Empresas',
                                'visualizar' => 'Visualizar',
                                'download'   => 'Download',
                                'tecnologia' => 'Tecnologia',
                                'criacao'    => 'Criação',
                                'convenio'   => 'Convênio',
                                'permissao'  => 'Todas as permissões',
                                'analytics'  => 'Analytics',
                                'apple'      => 'Apple',
                                'salvar'     => 'Cadastrar usuário',
                                'bloquear'   => 'Bloquear usuário',
                                'foto'       => 'Gerenciar Foto',
                                default      => ''
                            };
                            $Painel->checkbox(name: 'permissao[]', label: $label, value: $nomeApp . '_' . $permissao);
                        }
                    } elseif (array_key_exists('permissao', $configuracoes)) {
                        foreach ($configuracoes['permissao'] as $permissao => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissao);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });
}

$Painel->js('painel_usuario_equipe_add');

return $Painel;
