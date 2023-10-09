<?php

$permissao = json_encode([
    'usuario_cliente_index', 'usuario_cliente_add', 'usuario_cliente_editar',
    'usuario_cliente_apple', 'usuario_cliente_deletar', 'usuario_cliente_download',
    'usuario_cliente_empresa', 'usuario_cliente_analytics', 'usuario_cliente_visualizar',

    'usuario_grupo_index', 'usuario_grupo_add', 'usuario_grupo_editar', 'usuario_grupo_deletar',

    'usuario_dependente_index', 'usuario_dependente_add', 'usuario_dependente_deletar',

    'usuario_indicacao_index', 'usuario_indicacao_visualizar', 'usuario_indicacao_status',

    'usuario_lead_index', 'usuario_lead_visualizar', 'usuario_lead_status',

    'usuario_equipe_index', 'usuario_equipe_add', 'usuario_equipe_editar', 'usuario_equipe_permissao',
    'usuario_equipe_deletar', 'usuario_equipe_empresa',

    'publicacao_noticia_index', 'publicacao_noticia_add', 'publicacao_noticia_editar', 'publicacao_noticia_deletar',

    'publicacao_pagina_index', 'publicacao_pagina_editar',

    'publicacao_diretoria_index', 'publicacao_diretoria_add', 'publicacao_diretoria_editar',
    'publicacao_diretoria_deletar',

    'texto_clube_index', 'texto_clube_add', 'texto_clube_editar', 'texto_clube_deletar',

    'parceiro_relatorio_index', 'parceiro_relatorio_add', 'parceiro_relatorio_editar', 'parceiro_relatorio_deletar',

    'parceiro_cashback_index', 'parceiro_cashback_add', 'parceiro_cashback_editar', 'parceiro_cashback_deletar',

    'parceiro_easylive_index', 'parceiro_easylive_add', 'parceiro_easylive_editar', 'parceiro_easylive_deletar',

    'parceiro_automovel_index', 'parceiro_automovel_add', 'parceiro_automovel_editar', 'parceiro_automovel_deletar',
    'parceiro_automovel_visualizar',

    'comunicacao_publicidade_index', 'comunicacao_publicidade_add', 'comunicacao_publicidade_editar',
    'comunicacao_publicidade_deletar',

    'comunicacao_contato_index', 'comunicacao_contato_visualizar', 'comunicacao_contato_status',

    'comunicacao_popup_index', 'comunicacao_popup_add', 'comunicacao_popup_editar',
    'comunicacao_popup_deletar', 'comunicacao_popup_status', 'comunicacao_popup_empresa',

    'enquete_satisfacao_index', 'enquete_satisfacao_visualizar', 'enquete_satisfacao_deletar',
    'enquete_satisfacao_status',

    'construtor_clube_index', 'construtor_clube_add', 'construtor_clube_editar', 'construtor_clube_deletar',

    'relatorio_acesso_index', 'relatorio_acesso_empresa',

    'relatorio_usuario_index', 'relatorio_usuario_empresa',

    'relatorio_loja_venda_index', 'relatorio_loja_venda_empresa',

    'tabela_usuario_salvar', 'tabela_usuario_bloquear',

    'solicitacao_loja_index', 'solicitacao_loja_add', 'solicitacao_loja_deletar', 'solicitacao_loja_visualizar',
    'solicitacao_loja_status', 'solicitacao_loja_empresa',

    'solicitacao_voucher_index', 'solicitacao_voucher_visualizar', 'solicitacao_voucher_download',
    'solicitacao_voucher_empresa',

    'solicitacao_credito_index', 'solicitacao_credito_visualizar', 'solicitacao_credito_status',
    'solicitacao_credito_empresa',

    'solicitacao_premium_index', 'solicitacao_premium_visualizar', 'solicitacao_premium_index',
    'solicitacao_premium_download', 'solicitacao_premium_empresa',

    'solicitacao_salavip_index', 'solicitacao_salavip_download', 'solicitacao_salavip_empresa',

    'solicitacao_declaracao_index', 'solicitacao_declaracao_visualizar', 'solicitacao_declaracao_status',
    'solicitacao_declaracao_empresa',

    'comercial_empresa_index',

    'solicitacao_automovel_index', 'solicitacao_automovel_visualizar', 'solicitacao_automovel_status',
    'solicitacao_automovel_empresa',

    'solicitacao_cheque_bonus_index', 'solicitacao_cheque_bonus_visualizar', 'solicitacao_cheque_bonus_status',
    'solicitacao_cheque_bonus_empresa',
    'comercial_empresa_visualizar', 'comercial_empresa_editar', 'comercial_prospeccao_index',
    'comercial_prospeccao_add', 'comercial_prospeccao_editar', 'comercial_perdido_index', 'comercial_perdido_add',
    'comercial_perdido_editar', 'comercial_perdido_visualizar', 'comercial_perdido_status',
    'comercial_prospeccao_visualizar',
    'comercial_atendimento_index', 'demanda_tecnologia', 'demanda_criacao', 'log_erro_index',
    'log_erro_visualizar', 'log_erro_status'
]);

return [
    [
        'id'                => '1',
        'uuid'              => '8fd85f9f7cc21d6e33399681d6e5fca7',
        'id_admin_empresa'  => '1',
        'tipo'              => '1',
        'nome_perfil'       => 'andre.rodrigues',
        'nome_real'         => 'Andre Rodrigues',
        'documento_cpf'     => '01495180131',
        'email_pessoal'     => null,
        'email_trabalho'    => 'andre@marktclub.com.br',
        'telefone_pessoal'  => '61981777773',
        'telefone_trabalho' => null,
        'imagem_arquivo'    => null,
        'imagem_facebook'   => 'https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=5551849098231280&amp;height=300&amp;width=300&amp;ext=1667480926&amp;hash=AeToGj-DscG42CxRlWE',
        'imagem_google'     => 'https://lh3.googleusercontent.com/a-/ACNPEu9u23_b1dR1oh-m6aMiCBeQsTPShZX3auQMmTyhhfs=s384-c',
        'imagem_tipo'       => '2',
        'permissao'         => $permissao,
        'salt'              => password('Teste@1324'),
        'marktclub'         => 1,
        'desenvolvedor'     => 1,
        'gerente'           => 1,
        'admin'             => 1,
        'status'            => '1'
    ],
    [
        'id'                => '18',
        'uuid'              => 'f9cff03397e1be63c18772fccd298cdc',
        'id_admin_empresa'  => '1',
        'tipo'              => '1',
        'nome_perfil'       => 'roberto.camilo',
        'nome_real'         => 'Roberto Niwa Camilo',
        'documento_cpf'     => '92606290470',
        'email_pessoal'     => null,
        'email_trabalho'    => 'roberto@markt.club',
        'telefone_pessoal'  => null,
        'telefone_trabalho' => null,
        'imagem_arquivo'    => null,
        'imagem_facebook'   => null,
        'imagem_google'     => null,
        'imagem_tipo'       => null,
        'permissao'         => $permissao,
        'desenvolvedor'     => null,
        'gerente'           => null,
        'admin'             => null,
        'status'            => '1'
    ],
    [
        'id'                => '28',
        'uuid'              => '3df1a38ec0919bd14162beabb73e12b4',
        'id_admin_empresa'  => '1',
        'tipo'              => '1',
        'nome_perfil'       => 'herisson.gomes',
        'nome_real'         => 'HÉRISSON GOMES',
        'documento_cpf'     => '82194545476',
        'email_pessoal'     => null,
        'email_trabalho'    => 'herisson@markt.club',
        'telefone_pessoal'  => null,
        'telefone_trabalho' => null,
        'imagem_arquivo'    => null,
        'imagem_facebook'   => null,
        'imagem_google'     => null,
        'imagem_tipo'       => null,
        'permissao'         => $permissao,
        'desenvolvedor'     => null,
        'gerente'           => null,
        'admin'             => null,
        'status'            => '1'
    ],
    [
        'id'                => '179',
        'uuid'              => '0b077500f13856833d1d62bebceac6eb',
        'id_admin_empresa'  => '1',
        'tipo'              => '1',
        'nome_perfil'       => 'flavia.pinheiro',
        'nome_real'         => 'FLAVIA DANIELA PINHEIRO',
        'documento_cpf'     => '57880598790',
        'email_pessoal'     => null,
        'email_trabalho'    => 'flavia@markt.club',
        'telefone_pessoal'  => null,
        'telefone_trabalho' => null,
        'imagem_arquivo'    => null,
        'imagem_facebook'   => null,
        'imagem_google'     => null,
        'imagem_tipo'       => null,
        'permissao'         => $permissao,
        'desenvolvedor'     => null,
        'gerente'           => null,
        'admin'             => null,
        'status'            => '1'
    ],
    [
        'id'                => '225',
        'uuid'              => '060d9bc1e911b67a6c80d0dbf403ca14',
        'id_admin_empresa'  => '1',
        'tipo'              => '1',
        'nome_perfil'       => 'isabella.queiroz',
        'nome_real'         => 'ISABELLA QUEIROZ',
        'documento_cpf'     => '05174475688',
        'email_pessoal'     => null,
        'email_trabalho'    => 'isabella@markt.club',
        'telefone_pessoal'  => null,
        'telefone_trabalho' => null,
        'imagem_arquivo'    => null,
        'imagem_facebook'   => null,
        'imagem_google'     => null,
        'imagem_tipo'       => null,
        'permissao'         => $permissao,
        'desenvolvedor'     => null,
        'gerente'           => null,
        'admin'             => null,
        'status'            => '1'
    ],
    [
        'id'                => '441',
        'uuid'              => '0f3a5572ba1343afca4c0b538354c59c',
        'id_admin_empresa'  => '1',
        'tipo'              => '1',
        'nome_perfil'       => 'albert.dias',
        'nome_real'         => 'Albert Dias',
        'documento_cpf'     => '62581868589',
        'email_pessoal'     => null,
        'email_trabalho'    => 'albert.dias@markt.club',
        'telefone_pessoal'  => null,
        'telefone_trabalho' => null,
        'imagem_arquivo'    => null,
        'imagem_facebook'   => null,
        'imagem_google'     => null,
        'imagem_tipo'       => null,
        'permissao'         => $permissao,
        'desenvolvedor'     => null,
        'gerente'           => null,
        'admin'             => null,
        'status'            => '1'
    ],
    [
        'id'                => '3060',
        'uuid'              => 'a014996d75cb01f470dfc21c1f60c6d3',
        'id_admin_empresa'  => '1',
        'tipo'              => '1',
        'nome_perfil'       => 'Codestep',
        'nome_real'         => 'Codestep Engine',
        'documento_cpf'     => '12345678900',
        'email_pessoal'     => null,
        'email_trabalho'    => 'lucas.alves@markt.club',
        'telefone_pessoal'  => null,
        'telefone_trabalho' => null,
        'imagem_arquivo'    => null,
        'imagem_facebook'   => null,
        'imagem_google'     => 'https://lh3.googleusercontent.com/a-/ACNPEu-xUX6zA-hZissHFCJPq8k6uFQfX5u1EBEu31vP=s384-c',
        'imagem_tipo'       => '2',
        'permissao'         => $permissao,
        'salt'              => password('Teste@3060'),
        'desenvolvedor'     => '1',
        'gerente'           => '1',
        'admin'             => '1',
        'status'            => '1'
    ],
    [
        'id'               => '1126',
        'uuid'             => '5d608f97-1e64-4aba-9b94-2ba1c6376db5',
        'id_admin_empresa' => 1,
        'tipo'             => 3,
        'nome_perfil'      => 'markt.club',
        'nome_real'        => 'Markt Club',
        'email_trabalho'   => 'app@marktclub.com.br',
        'telefone_pessoal' => '61900001234',
        'documento_cpf'    => '01234567890',
        'salt'             => password('Teste@1324'),
        'permissao'        => $permissao,
        'primeiro_acesso'  => null,
        'mudar_senha'      => null,
        'desenvolvedor'    => 1,
        'admin'            => 1,
        'status'           => 1,
    ],
    [
        'uuid'             => 'b1923bd1-6cf8-405a-886b-38799acbbdf3',
        'id_admin_empresa' => '2',
        'tipo'             => '1',
        'nome_real'        => 'Usuário Empresa 2',
        'documento_cpf'    => cpfAleatorio(),
        'email_pessoal'    => null,
        'email_trabalho'   => emailAleatorio(),
        'telefone_pessoal' => telefoneAleatorio(),
        'permissao'        => $permissao,
        'salt'             => password('Teste@1324'),
        'desenvolvedor'    => null,
        'gerente'          => null,
        'admin'            => null,
        'status'           => '1'
    ],
];
