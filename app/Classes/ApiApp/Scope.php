<?php

namespace App\Classes\ApiApp;

final class Scope
{
    public const CLUBE_INTERNO = [
        'perfil_dado:buscar', 'perfil_dado:atualizar', 'perfil_dado:validar_senha',
        'perfil_dado:atualizar_senha', 'perfil_dado:alterar_imagem',
        'usuario_cliente:hash',
        'usuario_dependente:listar', 'usuario_dependente:salvar', 'usuario_dependente:email',
        'usuario_dependente:deletar',
        'usuario_indicacao:salvar',
        'admin:chave_publica', 'admin:chave_privada',
        'relatorio_analytics:salvar',
        'construtor_clube:buscar', 'site_lotacao:select',
        'comunicacao_login:buscar',
        'usuario_cliente:ativar', 'usuario_cliente:senha', 'usuario_indicacao:ativar',
        'login:digio', 'login:clube',
        'texto_clube:listar',
        'parceiro_loja:relacionado', 'parceiro_loja:listar', 'parceiro_loja:buscar',
        'parceiro_easylive:listar',
        'parceiro_cashback:listar', 'parceiro_cashback:buscar',
        'parceiro_cupom:listar', 'parceiro_cupom:buscar',
        'parceiro_favorito:listar',
        'parceiro_subcategoria:select',
        'parceiro_campanha:listar',
        'parceiro_favorito:listar', 'parceiro_favorito:salvar', 'parceiro_favorito:deletar',
        'endereco:listar', 'contato:listar',
        'comunicacao_publicidade:listar',
        'silium_comissao:saldo', 'silium_config:configuracoes',
        'silium_comissao:listar', 'silium_deposito:listar',
        'automovel_modelo:listar', 'automovel_versao:listar',
        'solicitacao_contato:salvar',
        'solicitacao_credito:simular', 'solicitacao_credito:salvar',
        'solicitacao_voucher:salvar',
        'solicitacao_loja:salvar', 'solicitacao_loja:listar', 'solicitacao_automovel:listar',
        'solicitacao_cheque_bonus:salvar',
        'solicitacao_declaracao:salvar',
        'solicitacao_credito:salvar',
        'solicitacao_automovel:salvar',
        'saude_contratacao:salvar', 'saude_simulacao:salvar',
        'saude_convenio:listar', 'saude_convenio:estado', 'saude_convenio:cidade', 'saude_convenio:html',
        'saude_convenio:buscar',
        'saude_simulacao:simular',
        'ponto_cvs:listar', 'ponto_cvs:salvar',
        'comercial_popup:listar',
        'carteirinha:listar',
        'enquete_satisfacao:salvar',
        'view_pagina:buscar', 'view_tabela:buscar',
        'galapagos_lead:salvar',
        'enquete_mercado:salvar', 'enquete_mercado:buscar'
    ];
    public const CLUBE_LOGIN = [
        'admin:chave_publica', 'admin:chave_privada',
        'construtor_clube:buscar', 'site_lotacao:select',
        'comunicacao_login:buscar',
        'usuario_cliente:ativar', 'usuario_cliente:senha', 'usuario_indicacao:ativar',
        'usuario_cliente:hash', 'usuario_cliente:apple',
        'usuario_cliente:atualizar', 'usuario_cliente:buscar',
        'usuario_cliente:salvar',
        'usuario_dependente:salvar',
        'usuario_dependente:buscar',
        'login:digio', 'login:clube',
        'texto_clube:listar', 'texto_clube:buscar',
        'solicitacao_contato:salvar',
    ];
    public const PAINEL_LOGIN = [
        'admin:chave_publica', 'admin:chave_privada', 'login:painel'
    ];
    public const PAINEL_SCOPE_PADRAO = [
        'perfil_dado:buscar', 'perfil_dado:atualizar', 'perfil_dado:validar_senha',
        'perfil_dado:atualizar_senha', 'perfil_dado:alterar_imagem',
        'admin:painel', 'admin:menu', 'admin:chave_publica', 'admin:chave_privada',
        'comercial_empresa:buscar', 'comercial_empresa:listar', 'comercial_empresa:salvar',
        'comercial_empresa:select', 'comercial_empresa:download',
        'comercial_subempresa:select',
        'comercial_restricao:select', 'parceiro_subcategoria:select', 'parceiro_subcategoria:listar',
        'usuario_grupo:select', 'usuario_equipe:select', 'parceiro_loja:select',
        'usuario_equipe:mudar_empresa', 'automovel_modelo:deletar', 'usuario_equipe:perfil',
        'site_lotacao:select', 'enquete_mercado:buscar', 'enquete_mercado:listar',
        'enquete_mercado:download'
    ];
    public const PAINEL_INTERNO = [
        'perfil_dado:buscar', 'perfil_dado:atualizar', 'perfil_dado:validar_senha',
        'perfil_dado:atualizar_senha', 'perfil_dado:alterar_imagem', 'usuario_equipe:perfil',

        'admin:painel', 'admin:menu', 'admin:chave_publica', 'admin:chave_privada', 'login:painel',

        'album_dado:listar', 'album_dado:salvar', 'album_dado:atualizar', 'album_dado:deletar',
        'album_dado:buscar', 'album_dado:foto',

        'automovel_modelo:atualizar', 'automovel_modelo:buscar', 'automovel_modelo:deletar',
        'automovel_modelo:listar', 'automovel_modelo:salvar',

        'automovel_versao:atualizar', 'automovel_versao:buscar', 'automovel_versao:deletar',
        'automovel_versao:listar', 'automovel_versao:salvar',

        'carteirinha:atualizar', 'carteirinha:buscar', 'carteirinha:deletar', 'carteirinha:listar',
        'carteirinha:salvar', 'carteirinha:status',

        'comercial_empresa:atualizar', 'comercial_empresa:buscar', 'comercial_empresa:listar',
        'comercial_empresa:salvar', 'comercial_popup:atualizar', 'comercial_popup:buscar',
        'comercial_empresa:select', 'comercial_empresa:perfil', 'comercial_empresa:download',

        'comercial_perdido:listar', 'comercial_perdido:buscar', 'comercial_perdido:atualizar',
        'comercial_perdido:status',

        'comercial_popup:deletar', 'comercial_popup:expirado', 'comercial_popup:listar',
        'comercial_popup:ordenar', 'comercial_popup:salvar',

        'comercial_prospeccao:listar', 'comercial_prospeccao:buscar', 'comercial_prospeccao:salvar',
        'comercial_prospeccao:atualizar',

        'comercial_regra:atualizar', 'comercial_regra:buscar', 'comercial_regra:deletar',
        'comercial_regra:listar', 'comercial_regra:salvar', 'comercial_subempresa:atualizar',

        'comercial_restricao:listar', 'comercial_restricao:select',

        'comercial_subempresa:buscar', 'comercial_subempresa:deletar', 'comercial_subempresa:listar',
        'comercial_subempresa:salvar', 'comercial_subempresa:select',

        'comunicacao_login:atualizar', 'comunicacao_login:buscar', 'comunicacao_login:deletar',
        'comunicacao_login:listar', 'comunicacao_login:salvar',

        'comunicacao_publicidade:atualizar', 'comunicacao_publicidade:buscar',
        'comunicacao_publicidade:deletar', 'comunicacao_publicidade:listar',
        'comunicacao_publicidade:salvar',

        'comercial_atendimento:listar',

        'construtor_clube:listar', 'construtor_clube:buscar', 'construtor_clube:salvar',
        'construtor_clube:atualizar', 'construtor_clube:deletar',

        'demanda_dado:atualizar', 'demanda_dado:buscar', 'demanda_dado:cancelar',
        'demanda_dado:listar', 'demanda_dado:salvar', 'demanda_dado:deletar',

        'demanda_sprint:atualizar', 'demanda_sprint:buscar', 'demanda_sprint:demanda',
        'demanda_sprint:listar', 'demanda_sprint:salvar', 'demanda_sprint:status',

        'demanda_trabalho:atualizar', 'demanda_trabalho:salvar',

        'demanda_tarefa:atualizar', 'demanda_tarefa:deletar', 'demanda_tarefa:like',
        'demanda_tarefa:salvar', 'demanda_tarefa:buscar', 'demanda_tarefa:listar',

        'enquete:listar', 'enquete:buscar', 'enquete:salvar', 'enquete:atualizar', 'enquete:deletar',

        'enquete_satisfacao:listar', 'enquete_satisfacao:buscar', 'enquete_satisfacao:deletar',
        'enquete_satisfacao:status', 'enquete_satisfacao:atualizar',

        'log_erro:listar', 'log_erro:buscar', 'log_erro:status',

        'painel_config:atualizar', 'painel_config:buscar', 'painel_config:deletar',
        'painel_config:listar', 'painel_config:salvar', 'painel_tradutor:traduzir',

        'parceiro_automovel:listar', 'parceiro_automovel:buscar', 'parceiro_automovel:salvar',
        'parceiro_automovel:atualizar', 'parceiro_automovel:deletar',

        'parceiro_cashback:atualizar', 'parceiro_cashback:buscar', 'parceiro_cashback:deletar',
        'parceiro_cashback:listar', 'parceiro_cashback:salvar',

        'parceiro_campanha:atualizar', 'parceiro_campanha:buscar', 'parceiro_campanha:deletar',
        'parceiro_campanha:listar', 'parceiro_campanha:salvar',

        'parceiro_cupom:listar', 'parceiro_cupom:status', 'parceiro_cupom:atualizar',
        'parceiro_cupom:buscar',

        'parceiro_externo:download', 'parceiro_externo:buscar', 'parceiro_externo:listar',
        'parceiro_externo:salvar',

        'parceiro_equipe:listar', 'parceiro_equipe:buscar',

        'parceiro_loja:atualizar', 'parceiro_loja:buscar', 'parceiro_loja:deletar', 'parceiro_loja:salvar',
        'parceiro_loja:listar', 'parceiro_loja:download', 'parceiro_loja:status', 'parceiro_loja:select',

        'parceiro_relatorio:atualizar', 'parceiro_relatorio:buscar', 'parceiro_relatorio:deletar',
        'parceiro_relatorio:listar', 'parceiro_relatorio:salvar',

        'parceiro_subcategoria:listar', 'parceiro_subcategoria:select',

        'parceiro_easylive:atualizar', 'parceiro_easylive:buscar', 'parceiro_easylive:deletar',
        'parceiro_easylive:listar',
        'parceiro_easylive:salvar',

        'publicacao_arquivo:atualizar', 'publicacao_arquivo:buscar', 'publicacao_arquivo:deletar',
        'publicacao_arquivo:listar', 'publicacao_arquivo:salvar',

        'publicacao_diretoria:atualizar', 'publicacao_diretoria:buscar', 'publicacao_diretoria:deletar',
        'publicacao_diretoria:listar', 'publicacao_diretoria:salvar',

        'publicacao_home:atualizar', 'publicacao_home:buscar',

        'publicacao_live:atualizar', 'publicacao_live:buscar',

        'publicacao_lista:atualizar', 'publicacao_lista:buscar', 'publicacao_lista:deletar',
        'publicacao_lista:listar', 'publicacao_lista:salvar',

        'publicacao_noticia:atualizar', 'publicacao_noticia:buscar', 'publicacao_noticia:deletar',
        'publicacao_noticia:listar', 'publicacao_noticia:salvar',

        'publicacao_pagina:listar', 'publicacao_pagina:buscar', 'publicacao_pagina:salvar',
        'publicacao_pagina:atualizar', 'publicacao_pagina:deletar',

        'publicacao_youtube:atualizar', 'publicacao_youtube:buscar', 'publicacao_youtube:salvar',
        'publicacao_youtube:deletar', 'publicacao_youtube:listar',

        'relatorio_acesso:listar', 'relatorio_analytics:download', 'relatorio_analytics:listar',
        'relatorio_analytics:salvar', 'relatorio_loja_venda:listar', 'relatorio_usuario:listar',

        'saude_contratacao:listar', 'saude_contratacao:buscar', 'saude_contratacao:status',
        'saude_contratacao:atualizar',

        'silium_deposito:atualizar', 'silium_deposito:buscar', 'silium_deposito:deletar',
        'silium_deposito:listar', 'silium_deposito:salvar', 'silium_deposito:status',

        'silium_comissao:atualizar', 'silium_comissao:buscar', 'silium_comissao:deletar',
        'silium_comissao:listar', 'silium_comissao:salvar', 'silium_comissao:status',

        'silium_config:atualizar', 'silium_config:buscar', 'silium_config:deletar',
        'silium_config:listar', 'silium_config:salvar',

        'silium_saque:listar', 'silium_saque:buscar', 'silium_saque:salvar', 'silium_saque:atualizar',
        'silium_saque:status', 'silium_saque:deletar',

        'silium_saldo:listar',

        'site_config:atualizar', 'site_config:buscar', 'site_config:deletar', 'site_config:listar',
        'site_config:salvar',

        'site_menu:listar', 'site_menu:buscar', 'site_menu:salvar', 'site_menu:atualizar', 'site_menu:deletar',

        'site_lotacao:atualizar', 'site_lotacao:buscar', 'site_lotacao:deletar', 'site_lotacao:listar',
        'site_lotacao:salvar', 'site_lotacao:select',

        'solicitacao_automovel:atualizar', 'solicitacao_automovel:buscar', 'solicitacao_automovel:listar',
        'solicitacao_automovel:status',

        'solicitacao_cheque_bonus:atualizar', 'solicitacao_cheque_bonus:buscar', 'solicitacao_cheque_bonus:listar',
        'solicitacao_cheque_bonus:status',

        'solicitacao_codigo:listar',

        'solicitacao_contato:atualizar', 'solicitacao_contato:buscar', 'solicitacao_contato:listar',
        'solicitacao_contato:status',

        'solicitacao_credito:atualizar', 'solicitacao_credito:buscar', 'solicitacao_credito:listar',
        'solicitacao_credito:status',

        'solicitacao_declaracao:atualizar', 'solicitacao_declaracao:buscar', 'solicitacao_declaracao:listar',
        'solicitacao_declaracao:status',

        'solicitacao_loja:atualizar', 'solicitacao_loja:buscar', 'solicitacao_loja:deletar',
        'solicitacao_loja:download', 'solicitacao_loja:listar', 'solicitacao_loja:salvar', 'solicitacao_loja:status',

        'solicitacao_premium:download', 'solicitacao_premium:listar', 'solicitacao_premium:buscar',

        'solicitacao_salavip:download', 'solicitacao_salavip:listar',

        'solicitacao_voucher:listar', 'solicitacao_voucher:buscar', 'solicitacao_voucher:download',

        'tabela_usuario:salvar', 'tabela_usuario:listar',

        'texto_clube:atualizar', 'texto_clube:buscar', 'texto_clube:deletar', 'texto_clube:listar',
        'texto_clube:salvar',

        'usuario_cliente:apple', 'usuario_cliente:atualizar', 'usuario_cliente:buscar',
        'usuario_cliente:deletar', 'usuario_cliente:download', 'usuario_cliente:listar',
        'usuario_cliente:salvar',

        'usuario_dependente:deletar', 'usuario_dependente:listar', 'usuario_dependente:salvar',
        'usuario_dependente:buscar',

        'usuario_grupo:atualizar', 'usuario_grupo:buscar', 'usuario_grupo:deletar',
        'usuario_grupo:listar', 'usuario_grupo:salvar', 'usuario_grupo:select',

        'usuario_lead:atualizar', 'usuario_lead:buscar', 'usuario_lead:deletar', 'usuario_lead:listar',
        'usuario_lead:status',

        'usuario_pagamento:atualizar', 'usuario_pagamento:buscar', 'usuario_pagamento:listar',
        'usuario_pagamento:salvar',

        'usuario_equipe:atualizar', 'usuario_equipe:buscar', 'usuario_equipe:deletar', 'usuario_equipe:select',
        'usuario_equipe:listar', 'usuario_equipe:mudar_empresa', 'usuario_equipe:salvar',

        'usuario_indicacao:ativar', 'usuario_indicacao:atualizar', 'usuario_indicacao:buscar',
        'usuario_indicacao:deletar', 'usuario_indicacao:listar', 'usuario_indicacao:status',

        'votacao_dado:atualizar', 'votacao_dado:buscar', 'votacao_dado:deletar', 'votacao_dado:listar',
        'votacao_dado:resultado', 'votacao_dado:salvar',

        'votacao_pergunta:atualizar', 'votacao_pergunta:buscar', 'votacao_pergunta:deletar',
        'votacao_pergunta:listar', 'votacao_pergunta:salvar',

        'votacao_resposta:atualizar', 'votacao_resposta:buscar', 'votacao_resposta:deletar',
        'votacao_resposta:listar', 'votacao_resposta:salvar',

        'votacao_usuario:validar', 'votacao_voto:salvar',

        'votacao:listar', 'votacao:buscar', 'votacao:salvar', 'votacao:atualizar', 'votacao:deletar',

        'view_html:atualizar', 'view_html:buscar', 'view_html:deletar', 'view_html:listar',
        'view_html:salvar', 'view_html:grupo', 'view_html:clonar',

        'view_pagina:atualizar', 'view_pagina:buscar', 'view_pagina:deletar',
        'view_pagina:listar', 'view_pagina:salvar',

        'mensageria:salvar',

        'endereco:salvar', 'endereco:listar', 'endereco:buscar', 'endereco:atualizar',
        'endereco:deletar',

        'contato:salvar', 'contato:listar', 'contato:buscar', 'contato:atualizar',
        'contato:deletar',

        'data:listar',

        'enquete_mercado:buscar', 'enquete_mercado:listar', 'enquete_mercado:salvar',
        'enquete_mercado:download'
    ];
    public const TUDO = [
        'perfil_dado:buscar', 'perfil_dado:atualizar', 'perfil_dado:validar_senha',
        'perfil_dado:atualizar_senha', 'perfil_dado:alterar_imagem',

        'admin:campo_obrigatorio', 'admin:campo_permitido', 'admin:chave_privada', 'admin:chave_publica',
        'admin:configuracao', 'admin:painel', 'admin:menu', 'admin:permissao', 'admin:upload_grupo',

        'album_dado:atualizar', 'album_dado:buscar', 'album_dado:deletar', 'album_dado:foto',
        'album_dado:listar', 'album_dado:salvar',

        'app_api:buscar', 'app_api:listar', 'app_usuario:listar',

        'automovel_modelo:atualizar', 'automovel_modelo:buscar', 'automovel_modelo:deletar',
        'automovel_modelo:listar', 'automovel_modelo:salvar',

        'automovel_versao:atualizar', 'automovel_versao:buscar', 'automovel_versao:deletar',
        'automovel_versao:listar', 'automovel_versao:salvar',

        'campanha_sorteio:buscar', 'campanha_sorteio:resultado', 'campanha_sorteio:sortear',

        'carteirinha:atualizar', 'carteirinha:buscar', 'carteirinha:deletar', 'carteirinha:listar',
        'carteirinha:salvar',

        'chatbot_perguntas:atualizar', 'chatbot_perguntas:buscar', 'chatbot_perguntas:listar',
        'chatbot_perguntas:perguntar', 'chatbot_perguntas:salvar',

        'comercial_empresa:atualizar', 'comercial_empresa:buscar', 'comercial_empresa:listar',
        'comercial_empresa:salvar', 'comercial_popup:atualizar', 'comercial_popup:buscar',
        'comercial_empresa:perfil', 'comercial_empresa:download',

        'comercial_popup:deletar', 'comercial_popup:expirado', 'comercial_popup:listar',
        'comercial_popup:ordenar', 'comercial_popup:salvar', 'comercial_restricao:listar',

        'comercial_regra:atualizar', 'comercial_regra:buscar', 'comercial_regra:deletar',
        'comercial_regra:listar', 'comercial_regra:salvar', 'comercial_subempresa:atualizar',

        'comercial_subempresa:buscar', 'comercial_subempresa:deletar', 'comercial_subempresa:listar',
        'comercial_subempresa:salvar',

        'comunicacao_login:atualizar', 'comunicacao_login:buscar', 'comunicacao_login:deletar',
        'comunicacao_login:listar', 'comunicacao_login:salvar', 'comunicacao_publicidade:atualizar',
        'comunicacao_publicidade:buscar', 'comunicacao_publicidade:deletar', 'comunicacao_publicidade:listar',
        'comunicacao_publicidade:salvar',

        'construtor_clube:listar', 'construtor_clube:buscar', 'construtor_clube:salvar',
        'construtor_clube:atualizar', 'construtor_clube:deletar',

        'demanda_dado:atualizar', 'demanda_dado:buscar', 'demanda_dado:cancelar',
        'demanda_dado:listar', 'demanda_dado:salvar', 'demanda_sprint:atualizar',
        'demanda_sprint:buscar', 'demanda_sprint:demanda', 'demanda_sprint:listar',
        'demanda_sprint:salvar', 'demanda_trabalho:atualizar', 'demanda_trabalho:salvar',
        'demanda_tarefa:atualizar', 'demanda_tarefa:deletar', 'demanda_tarefa:like',
        'demanda_tarefa:salvar', 'demanda_tarefa:buscar', 'demanda_tarefa:listar',

        'enquete_satisfacao:listar', 'enquete_satisfacao:buscar', 'enquete_satisfacao:atualizar',

        'login:api', 'login:clube', 'login:painel', 'login:digio', 'login:token',
        'login:positivo', 'login:oauth',

        'painel_config:atualizar', 'painel_config:buscar', 'painel_config:deletar',
        'painel_config:listar', 'painel_config:salvar', 'painel_tradutor:traduzir',

        'parceiro_cashback:atualizar', 'parceiro_cashback:buscar', 'parceiro_cashback:deletar',
        'parceiro_cashback:listar', 'parceiro_cashback:salvar',

        'parceiro_campanha:atualizar', 'parceiro_campanha:buscar', 'parceiro_campanha:deletar',
        'parceiro_campanha:listar', 'parceiro_campanha:salvar',

        'parceiro_externo:download', 'parceiro_externo:buscar', 'parceiro_externo:listar',

        'parceiro_favorito:deletar', 'parceiro_favorito:listar', 'parceiro_favorito:salvar',

        'parceiro_loja:atualizar', 'parceiro_loja:buscar', 'parceiro_loja:deletar',
        'parceiro_loja:destaque', 'parceiro_loja:listar', 'parceiro_loja:relacionado',
        'parceiro_loja:download',

        'parceiro_relatorio:atualizar', 'parceiro_relatorio:buscar', 'parceiro_relatorio:deletar',
        'parceiro_relatorio:listar', 'parceiro_relatorio:salvar',

        'parceiro_subcategoria:listar',

        'parceiro_easylive:atualizar', 'parceiro_easylive:buscar', 'parceiro_easylive:deletar',
        'parceiro_easylive:listar',
        'parceiro_easylive:salvar',

        'publicacao_arquivo:atualizar', 'publicacao_arquivo:buscar', 'publicacao_arquivo:deletar',
        'publicacao_arquivo:listar', 'publicacao_arquivo:salvar',

        'publicacao_diretoria:atualizar', 'publicacao_diretoria:buscar', 'publicacao_diretoria:deletar',
        'publicacao_diretoria:listar', 'publicacao_diretoria:salvar',

        'publicacao_home:atualizar', 'publicacao_home:buscar',

        'publicacao_live:atualizar', 'publicacao_live:buscar',

        'publicacao_lista:atualizar', 'publicacao_lista:buscar', 'publicacao_lista:deletar',
        'publicacao_lista:listar', 'publicacao_lista:salvar',

        'publicacao_noticia:atualizar', 'publicacao_noticia:buscar', 'publicacao_noticia:deletar',
        'publicacao_noticia:listar', 'publicacao_noticia:salvar',

        'publicacao_youtube:atualizar', 'publicacao_youtube:buscar', 'publicacao_youtube:deletar',
        'publicacao_youtube:listar',

        'relatorio_acesso:listar', 'relatorio_analytics:download', 'relatorio_analytics:listar',
        'relatorio_analytics:salvar', 'relatorio_loja_venda:listar', 'relatorio_usuario:listar',

        'silium_comissao:atualizar', 'silium_comissao:buscar', 'silium_comissao:deletar',
        'silium_comissao:listar', 'silium_comissao:saldo', 'silium_deposito:atualizar',
        'silium_deposito:buscar', 'silium_deposito:deletar', 'silium_deposito:listar', 'silium_deposito:salvar',
        'silium_config:atualizar', 'silium_config:buscar', 'silium_config:configuracoes', 'silium_config:deletar',
        'silium_config:listar', 'silium_config:salvar',

        'site_config:atualizar', 'site_config:buscar', 'site_config:deletar', 'site_config:listar',
        'site_config:salvar',

        'site_lotacao:atualizar', 'site_lotacao:buscar', 'site_lotacao:deletar', 'site_lotacao:listar',
        'site_lotacao:salvar', 'site_lotacao:select',

        'site_menu:atualizar', 'site_menu:buscar', 'site_menu:deletar', 'site_menu:listar',
        'site_menu:salvar',

        'solicitacao_automovel:atualizar', 'solicitacao_automovel:buscar', 'solicitacao_automovel:listar',
        'solicitacao_automovel:salvar',

        'solicitacao_cheque_bonus:atualizar', 'solicitacao_cheque_bonus:buscar', 'solicitacao_cheque_bonus:listar',
        'solicitacao_cheque_bonus:salvar',

        'solicitacao_contato:atualizar', 'solicitacao_contato:buscar', 'solicitacao_contato:listar',
        'solicitacao_contato:salvar', 'solicitacao_contato:status',

        'solicitacao_credito:atualizar', 'solicitacao_credito:buscar', 'solicitacao_credito:listar',
        'solicitacao_credito:salvar', 'solicitacao_credito:simular',
        'solicitacao_declaracao:atualizar', 'solicitacao_declaracao:buscar', 'solicitacao_declaracao:listar',
        'solicitacao_declaracao:salvar',

        'solicitacao_loja:atualizar', 'solicitacao_loja:buscar', 'solicitacao_loja:deletar', 'solicitacao_loja:salvar',
        'solicitacao_loja:download', 'solicitacao_loja:listar', 'solicitacao_loja:salvar', 'solicitacao_loja:status',

        'solicitacao_premium:download', 'solicitacao_premium:listar', 'solicitacao_salavip:download',
        'solicitacao_salavip:salvar', 'solicitacao_salavip:listar',

        'texto_clube:atualizar', 'texto_clube:buscar', 'texto_clube:deletar', 'texto_clube:listar',
        'texto_clube:salvar',

        'usuario_cliente:apple', 'usuario_cliente:ativar', 'usuario_cliente:atualizar', 'usuario_cliente:buscar',
        'usuario_cliente:deletar', 'usuario_cliente:download', 'usuario_cliente:hash', 'usuario_cliente:listar',
        'usuario_cliente:salvar', 'usuario_cliente:senha', 'usuario_cliente:validar',

        'usuario_dependente:atualizar', 'usuario_dependente:deletar', 'usuario_dependente:email',
        'usuario_dependente:listar', 'usuario_dependente:salvar',

        'usuario_grupo:atualizar', 'usuario_grupo:buscar', 'usuario_grupo:deletar',
        'usuario_grupo:listar', 'usuario_grupo:salvar',

        'usuario_lead:atualizar', 'usuario_lead:buscar', 'usuario_lead:deletar', 'usuario_lead:listar',
        'usuario_lead:salvar', 'usuario_lead:status',

        'usuario_pagamento:atualizar', 'usuario_pagamento:buscar', 'usuario_pagamento:listar',
        'usuario_pagamento:salvar',

        'usuario_equipe:atualizar', 'usuario_equipe:buscar', 'usuario_equipe:deletar', 'usuario_equipe:select',
        'usuario_equipe:listar', 'usuario_equipe:mudar_empresa', 'usuario_equipe:salvar',

        'usuario_indicacao:ativar', 'usuario_indicacao:atualizar', 'usuario_indicacao:buscar',
        'usuario_indicacao:deletar', 'usuario_indicacao:listar', 'usuario_indicacao:salvar',
        'usuario_indicacao:status',

        'votacao_dado:atualizar', 'votacao_dado:buscar', 'votacao_dado:deletar', 'votacao_dado:listar',
        'votacao_dado:resultado', 'votacao_pergunta:atualizar', 'votacao_pergunta:buscar',
        'votacao_pergunta:deletar', 'votacao_pergunta:listar', 'votacao_pergunta:salvar',
        'votacao_resposta:atualizar', 'votacao_resposta:buscar', 'votacao_resposta:deletar',
        'votacao_resposta:listar', 'votacao_resposta:salvar', 'votacao_usuario:validar',
        'votacao_voto:salvar',

        'view_html:atualizar', 'view_html:buscar', 'view_html:deletar', 'view_html:listar',
        'view_html:salvar', 'view_pagina:atualizar', 'view_pagina:buscar', 'view_pagina:deletar',
        'view_pagina:listar', 'view_pagina:salvar',

        'galapagos_lead:salvar',

        'enquete_mercado:buscar', 'enquete_mercado:listar', 'enquete_mercado:salvar',
        'enquete_mercado:download'
    ];

    public function pegarScope(array $lista)
    {
    }
}
