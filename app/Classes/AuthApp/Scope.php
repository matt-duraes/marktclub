<?php

namespace App\Classes\AuthApp;

final class Scope
{
    public const LOGIN = [

    ];
    public const CLUBE = [

    ];
    public const PAINEL = [

    ];
    public const TUDO = [
        'token_credential:salvar',

        'usuario_cliente:salvar', 'usuario_cliente:atualizar', 'usuario_cliente:listar',
        'usuario_cliente:buscar', 'usuario_cliente:deletar', 'usuario_cliente:deletar_cpf',
        'usuario_cliente:download', 'usuario_cliente:apple', 'usuario_cliente:ativar', 'usuario_cliente:senha',
        'usuario_cliente:validar_senha', 'usuario_cliente:alterar_senha', 'usuario_cliente:validar',
        'usuario_cliente:hash',

        'usuario_dependente:listar', 'usuario_dependente:salvar', 'usuario_dependente:atualizar',
        'usuario_dependente:deletar', 'usuario_dependente:email',

        'usuario_equipe:salvar', 'usuario_equipe:atualizar', 'usuario_equipe:listar',
        'usuario_equipe:buscar', 'usuario_equipe:deletar', 'usuario_equipe:validar_senha',

        'usuario_lead:salvar', 'usuario_lead:atualizar', 'usuario_lead:listar', 'usuario_lead:buscar',
        'usuario_lead:deletar',

        'usuario_pagamento:listar', 'usuario_pagamento:buscar', 'usuario_pagamento:salvar',
        'usuario_pagamento:atualizar',

        'usuario_indicacao:salvar', 'usuario_indicacao:atualizar', 'usuario_indicacao:listar',
        'usuario_indicacao:buscar', 'usuario_indicacao:deletar', 'usuario_indicacao:ativar',

        'usuario_grupo:salvar', 'usuario_grupo:atualizar', 'usuario_grupo:listar', 'usuario_grupo:buscar',
        'usuario_grupo:deletar',

        'tabela_usuario:salvar', 'tabela_usuario:listar', 'tabela_usuario:atualizar',

        'voucher:salvar', 'voucher:verificar', 'voucher:validar',

        'relatorio_analytics:listar', 'relatorio_analytics:download', 'relatorio_analytics:salvar',
        'relatorio_acesso:listar', 'relatorio_usuario:listar', 'relatorio_loja_venda:listar',

        'login:painel', 'login:api', 'login:clube', 'login:token', 'login:digio', 'login:naval',

        'admin:chave_publica', 'admin:chave_privada', 'admin:permissao', 'admin:configuracao',
        'admin:upload_grupo', 'admin:menu', 'admin:campo_obrigatorio', 'admin:campo_permitido',

        'endereco:salvar', 'endereco:atualizar', 'endereco:listar', 'endereco:buscar', 'endereco:deletar',
        'contato:buscar', 'contato:listar', 'contato:salvar', 'contato:atualizar', 'contato:deletar',

        'data:listar',

        'parceiro_relatorio:salvar', 'parceiro_relatorio:atualizar', 'parceiro_relatorio:listar',
        'parceiro_relatorio:buscar', 'parceiro_relatorio:deletar',

        'parceiro_campanha:salvar', 'parceiro_campanha:atualizar', 'parceiro_campanha:listar',
        'parceiro_campanha:buscar', 'parceiro_campanha:deletar',

        'parceiro_easylive:salvar', 'parceiro_easylive:atualizar', 'parceiro_easylive:listar',
        'parceiro_easylive:buscar', 'parceiro_easylive:deletar',

        'parceiro_loja:listar', 'parceiro_loja:buscar', 'parceiro_loja:salvar', 'parceiro_loja:atualizar',
        'parceiro_loja:deletar', 'parceiro_loja:relacionado', 'parceiro_loja:download',

        'parceiro_externo:listar', 'parceiro_externo:buscar', 'parceiro_externo:salvar', 'parceiro_externo:download',

        'parceiro_subcategoria:listar',

        'parceiro_favorito:listar', 'parceiro_favorito:salvar', 'parceiro_favorito:deletar',

        'publicacao_noticia:salvar', 'publicacao_noticia:atualizar', 'publicacao_noticia:listar',
        'publicacao_noticia:buscar', 'publicacao_noticia:deletar',

        'publicacao_youtube:salvar', 'publicacao_youtube:atualizar', 'publicacao_youtube:listar',
        'publicacao_youtube:buscar', 'publicacao_youtube:deletar',

        'publicacao_pagina:atualizar', 'publicacao_pagina:listar', 'publicacao_pagina:buscar',

        'publicacao_diretoria:salvar', 'publicacao_diretoria:atualizar', 'publicacao_diretoria:listar',
        'publicacao_diretoria:buscar', 'publicacao_diretoria:deletar',

        'publicacao_arquivo:salvar', 'publicacao_arquivo:atualizar', 'publicacao_arquivo:listar',
        'publicacao_arquivo:buscar', 'publicacao_arquivo:deletar',

        'publicacao_lista:listar', 'publicacao_lista:buscar', 'publicacao_lista:salvar',
        'publicacao_lista:atualizar', 'publicacao_lista:deletar',

        'publicacao_home:atualizar', 'publicacao_home:buscar',
        'publicacao_live:atualizar', 'publicacao_live:buscar',

        'texto_clube:salvar', 'texto_clube:atualizar', 'texto_clube:listar', 'texto_clube:buscar', 'texto_clube:deletar',

        'campanha_sorteio:buscar', 'campanha_sorteio:sortear', 'campanha_sorteio:resultado',

        'comunicacao_publicidade:listar', 'comunicacao_publicidade:buscar', 'comunicacao_publicidade:salvar',
        'comunicacao_publicidade:atualizar', 'comunicacao_publicidade:deletar',

        'app_api:listar', 'app_api:buscar', 'app_api:salvar', 'app_api:atualizar', 'app_api:deletar',

        'app_usuario:listar', 'app_usuario:buscar', 'app_usuario:salvar', 'app_usuario:atualizar',
        'app_usuario:deletar',

        'comercial_restricao:listar', 'comercial_restricao:buscar', 'comercial_restricao:salvar',
        'comercial_restricao:atualizar', 'comercial_restricao:deletar',

        'comercial_empresa:listar', 'comercial_empresa:buscar', 'comercial_empresa:salvar',
        'comercial_empresa:atualizar', 'comercial_empresa:deletar',

        'comercial_subempresa:buscar', 'comercial_subempresa:listar', 'comercial_subempresa:salvar',
        'comercial_subempresa:atualizar', 'comercial_subempresa:deletar',

        'comercial_prespeccao:listar', 'comercial_prespeccao:buscar', 'comercial_prespeccao:salvar',
        'comercial_prespeccao:atualizar',

        'comercial_regra:listar', 'comercial_regra:buscar', 'comercial_regra:salvar', 'comercial_regra:atualizar',
        'comercial_regra:deletar',

        'construtor_clube:buscar', 'construtor_clube:listar', 'construtor_clube:salvar', 'construtor_clube:atualizar',
        'construtor_clube:deletar',

        'demanda_sprint:listar', 'demanda_sprint:buscar', 'demanda_sprint:salvar', 'demanda_sprint:atualizar',
        'demanda_sprint:demanda',

        'demanda_dado:listar', 'demanda_dado:buscar', 'demanda_dado:salvar', 'demanda_dado:atualizar',
        'demanda_dado:cancelar',

        'demanda_tarefa:listar', 'demanda_tarefa:salvar', 'demanda_tarefa:buscar', 'demanda_tarefa:atualizar',
        'demanda_tarefa:deletar', 'demanda_tarefa:like',

        'mensageria:salvar',

        'pagina:turismo', 'pagina:cinema', 'pagina:samsung',

        'log_erro:listar', 'log_erro:buscar', 'log_erro:atualizar',

        'ponto_cvs:listar', 'ponto_cvs:buscar', 'ponto_cvs:salvar', 'ponto_cvs:atualizar',

        'saude_simulacao:buscar', 'saude_simulacao:salvar',

        'saude_contratacao:buscar', 'saude_contratacao:salvar', 'saude_contratacao:atualizar', 'saude_contratacao:listar',

        'solicitacao_declaracao:listar', 'solicitacao_declaracao:buscar', 'solicitacao_declaracao:salvar',
        'solicitacao_declaracao:atualizar',

        'solicitacao_cheque_bonus:listar', 'solicitacao_cheque_bonus:buscar', 'solicitacao_cheque_bonus:salvar',
        'solicitacao_cheque_bonus:atualizar',

        'solicitacao_credito:listar', 'solicitacao_credito:buscar', 'solicitacao_credito:salvar',
        'solicitacao_credito:simular', 'solicitacao_credito:atualizar',

        'solicitacao_premium:listar', 'solicitacao_premium:download',

        'solicitacao_voucher:listar', 'solicitacao_voucher:buscar', 'solicitacao_voucher:download',
        'solicitacao_voucher:salvar',

        'solicitacao_salavip:listar', 'solicitacao_salavip:download', 'solicitacao_salavip:salvar',

        'solicitacao_automovel:listar', 'solicitacao_automovel:buscar', 'solicitacao_automovel:salvar',
        'solicitacao_automovel:atualizar',

        'solicitacao_codigo:listar',

        'carteirinha:buscar', 'carteirinha:listar', 'carteirinha:salvar',
        'carteirinha:atualizar', 'carteirinha:deletar', 'carteirinha:clube',

        'comercial_popup:buscar', 'comercial_popup:listar',
        'comercial_popup:salvar', 'comercial_popup:atualizar', 'comercial_popup:deletar',
        'comercial_popup:ordenar', 'comercial_popup:expirado',

        'enquete_satisfacao:listar', 'enquete_satisfacao:buscar', 'enquete_satisfacao:salvar',
        'enquete_satisfacao:atualizar', 'enquete_satisfacao:deletar',

        'parceiro_loja:listar', 'parceiro_loja:buscar', 'parceiro_loja:destaque', 'parceiro_loja:salvar',
        'parceiro_loja:atualizar', 'parceiro_loja:deletar',

        'parceiro_cupom:buscar', 'parceiro_cupom:listar', 'parceiro_cupom:atualizar',

        'solicitacao_contato:buscar', 'solicitacao_contato:listar', 'solicitacao_contato:salvar',
        'solicitacao_contato:atualizar',

        'mensagem_indicacao_parceiro:salvar', 'mensagem_indicacao_parceiro:listar', 'mensagem_indicacao_parceiro:buscar',

        'automovel_montadora:listar', 'automovel_montadora:salvar', 'automovel_montadora:buscar',

        'automovel_modelo:listar', 'automovel_modelo:salvar', 'automovel_modelo:buscar',
        'automovel_modelo:atualizar', 'automovel_modelo:deletar',

        'automovel_versao:listar', 'automovel_versao:salvar', 'automovel_versao:buscar',
        'automovel_versao:atualizar', 'automovel_versao:deletar', 'automovel:listar',

        'silium_comissao:buscar', 'silium_comissao:listar', 'silium_comissao:salvar',
        'silium_comissao:atualizar', 'silium_comissao:deletar', 'silium_comissao:saldo',

        'silium_deposito:buscar', 'silium_deposito:listar', 'silium_deposito:salvar',
        'silium_deposito:atualizar', 'silium_deposito:deletar',

        'silium_config:buscar', 'silium_config:listar', 'silium_config:salvar',
        'silium_config:atualizar', 'silium_config:deletar', 'silium_config:configuracoes',

        'silium_saldo:listar',

        'solicitacao_loja:listar', 'solicitacao_loja:buscar', 'solicitacao_loja:salvar',
        'solicitacao_loja:atualizar', 'solicitacao_loja:deletar', 'solicitacao_loja:download',

        'chatbot_perguntas:salvar', 'chatbot_perguntas:atualizar', 'chatbot_perguntas:listar', 'chatbot_perguntas:buscar',
        'chatbot_perguntas:perguntar',

        'chatbot_categoria:salvar', 'chatbot_categoria:atualizar', 'chatbot_categoria:listar', 'chatbot_categoria:buscar',

        'drogaria_araujo:buscar',

        'comunicacao_login:listar', 'comunicacao_login:buscar', 'comunicacao_login:salvar', 'comunicacao_login:atualizar',
        'comunicacao_login:deletar',

        'site_config:listar', 'site_config:buscar', 'site_config:salvar',
        'site_config:atualizar', 'site_config:deletar', 'site_config:empresa',

        'site_menu:listar', 'site_menu:buscar', 'site_menu:salvar', 'site_menu:atualizar',
        'site_menu:deletar', 'site_menu:empresa',

        'site_lotacao:select', 'site_lotacao:listar', 'site_lotacao:buscar', 'site_lotacao:salvar',
        'site_lotacao:atualizar', 'site_lotacao:deletar', 'site_lotacao:empresa',

        'painel_tradutor:traduzir',

        'painel_config:buscar', 'painel_config:listar', 'painel_config:salvar',
        'painel_config:atualizar', 'painel_config:deletar',

        'album_dado:buscar', 'album_dado:listar', 'album_dado:salvar', 'album_dado:atualizar',
        'album_dado:deletar', 'album_dado:foto',

        'votacao_dado:buscar', 'votacao_dado:listar', 'votacao_dado:salvar', 'votacao_dado:atualizar',
        'votacao_dado:deletar', 'votacao_dado:resultado',
        'votacao_pergunta:buscar', 'votacao_pergunta:listar', 'votacao_pergunta:salvar', 'votacao_pergunta:atualizar',
        'votacao_pergunta:deletar',
        'votacao_resposta:buscar', 'votacao_resposta:listar', 'votacao_resposta:salvar', 'votacao_resposta:atualizar',
        'votacao_resposta:deletar',
        'votacao_usuario:validar',
        'votacao_voto:salvar',

        'painel_historico:download',

        'view_pagina:listar', 'view_pagina:buscar', 'view_pagina:salvar', 'view_pagina:atualizar', 'view_pagina:deletar',
        'view_html:listar', 'view_html:buscar', 'view_html:salvar', 'view_html:atualizar', 'view_html:deletar'
    ];
}
