<?php

return [
    [
        'id_admin_empresa' => 1,
        'tipo' => 3,
        'nome_perfil' => 'markt.club',
        'nome_real' => 'Markt Club',
        'email_trabalho' => 'app@marktclub.com.br',
        'telefone_pessoal' => '61900001234',
        'documento_cpf' => '01234567890',
        'salt' => password('Teste@1324'),
        'permissao' => json_encode([
            'usuario_cliente_index', 'usuario_cliente_add', 'usuario_cliente_visualizar', 'usuario_cliente_download', 'usuario_cliente_editar', 'usuario_cliente_deletar',
            'usuario_dependente_index', 'usuario_dependente_add', 'usuario_dependente_deletar',
            'usuario_equipe_index', 'usuario_equipe_add', 'usuario_equipe_editar', 'usuario_equipe_deletar',
            'usuario_lead_index', 'usuario_lead_visualizar', 'usuario_lead_status',
            'usuario_indicacao_index', 'usuario_indicacao_visualizar', 'usuario_indicacao_status',
            'usuario_grupo_index', 'usuario_grupo_add', 'usuario_grupo_editar', 'usuario_grupo_deletar',
            'tabela_usuario_salvar', 'tabela_usuario_bloquear',
            'relatorio_usuario_index', 'relatorio_acesso_index',
            'solicitacao_voucher_index', 'solicitacao_voucher_visualizar',
            'solicitacao_salavip_index', 'solicitacao_salavip_download',
            'api_app_index', 'api_app_add', 'api_app_editar', 'api_app_deletar',
            'api_usuario_index', 'api_usuario_add', 'api_usuario_editar', 'api_usuario_deletar',
            'ponto_cvs_index', 'ponto_cvs_add', 'ponto_cvs_editar', 'ponto_cvs_visualizar'
        ]),
        'primeiro_acesso' => null,
        'mudar_senha' => null,
        'desenvolvedor' => 1,
        'admin' => 1,
        'status' => 1,
    ]
];
