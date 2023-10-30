<?php

$scope = [
    'token_credential:salvar',

    'usuario_cliente:salvar', 'usuario_cliente:atualizar', 'usuario_cliente:listar',
    'usuario_cliente:buscar', 'usuario_cliente:deletar', 'usuario_cliente:deletar_cpf',
    'usuario_cliente:download', 'usuario_cliente:apple', 'usuario_cliente:ativar', 'usuario_cliente:senha',

    'usuario_dependente:salvar', 'usuario_dependente:listar', 'usuario_dependente:deletar',

    'usuario_equipe:salvar', 'usuario_equipe:atualizar', 'usuario_equipe:listar',
    'usuario_equipe:buscar', 'usuario_equipe:deletar', 'usuario_equipe:validar_senha',

    'usuario_lead:salvar', 'usuario_lead:atualizar', 'usuario_lead:listar', 'usuario_lead:buscar',
    'usuario_lead:deletar',

    'usuario_pagamento:listar', 'usuario_pagamento:buscar', 'usuario_pagamento:salvar',
    'usuario_pagamento:atualizar',

    'usuario_indicacao:salvar', 'usuario_indicacao:atualizar', 'usuario_indicacao:listar',
    'usuario_indicacao:buscar', 'usuario_indicacao:deletar',

    'usuario_grupo:salvar', 'usuario_grupo:atualizar', 'usuario_grupo:listar', 'usuario_grupo:buscar',
    'usuario_grupo:deletar',

    'tabela_usuario:salvar', 'tabela_usuario:bloquear',

    'voucher:salvar', 'voucher:verificar', 'voucher:validar',

    'relatorio_analytics:listar', 'relatorio_analytics:download', 'relatorio_analytics:salvar',

    'relatorio_acesso:listar',

    'relatorio_usuario:listar',

    'relatorio_loja_venda:listar',

    'login:painel', 'login:api', 'login:clube', 'login:token', 'login:digio',

    'admin:chave_publica', 'admin:chave_privada', 'admin:permissao', 'admin:configuracao',
    'admin:upload_grupo', 'admin:menu', 'admin:campo_obrigatorio', 'admin:campo_permitido',

    'endereco:salvar', 'endereco:atualizar', 'endereco:listar', 'endereco:buscar', 'endereco:deletar',

    'parceiro_relatorio:salvar', 'parceiro_relatorio:atualizar', 'parceiro_relatorio:listar',
    'parceiro_relatorio:buscar', 'parceiro_relatorio:deletar',

    'parceiro_cashback:salvar', 'parceiro_cashback:atualizar', 'parceiro_cashback:listar',
    'parceiro_cashback:buscar', 'parceiro_cashback:deletar',

    'parceiro_easylive:salvar', 'parceiro_easylive:atualizar', 'parceiro_easylive:listar',
    'parceiro_easylive:buscar', 'parceiro_easylive:deletar',

    'parceiro_loja:listar', 'parceiro_loja:buscar', 'parceiro_loja:relacionado',

    'parceiro_subcategoria:listar',

    'parceiro_favorito:salvar', 'parceiro_favorito:deletar',

    'publicacao_noticia:salvar', 'publicacao_noticia:atualizar', 'publicacao_noticia:listar',
    'publicacao_noticia:buscar', 'publicacao_noticia:deletar',

    'publicacao_pagina:atualizar', 'publicacao_pagina:listar', 'publicacao_pagina:buscar',

    'publicacao_diretoria:salvar', 'publicacao_diretoria:atualizar', 'publicacao_diretoria:listar',
    'publicacao_diretoria:buscar', 'publicacao_diretoria:deletar',

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
    'comercial_subempresa:listar',
    'comercial_prespeccao:listar', 'comercial_prespeccao:buscar', 'comercial_prespeccao:salvar',
    'comercial_prespeccao:atualizar',
    'comercial_regra:listar', 'comercial_regra:buscar', 'comercial_regra:salvar', 'comercial_regra:atualizar',
    'comercial_regra:deletar',

    'construtor_clube:buscar', 'construtor_clube:listar', 'construtor_clube:salvar', 'construtor_clube:atualizar',
    'construtor_clube:deletar',

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

    'solicitacao_salavip:listar', 'solicitacao_salavip:download',

    'solicitacao_automovel:listar', 'solicitacao_automovel:buscar', 'solicitacao_automovel:salvar',
    'solicitacao_automovel:atualizar',

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

    'silium:saldo', 'silium:extrato', 'silium:saque',

    'solicitacao_loja:listar', 'solicitacao_loja:buscar', 'solicitacao_loja:salvar',
    'solicitacao_loja:atualizar', 'solicitacao_loja:deletar',

    'chatbot_perguntas:salvar', 'chatbot_perguntas:atualizar', 'chatbot_perguntas:listar', 'chatbot_perguntas:buscar',
    'chatbot_perguntas:perguntar',

    'chatbot_categoria:salvar', 'chatbot_categoria:atualizar', 'chatbot_categoria:listar', 'chatbot_categoria:buscar',
];

return [
    [
        'id'                 => '1',
        'uuid'               => '1e01bddf-6ba5-437c-9dba-003f31988f71',
        'id_admin_empresa'   => '1',
        'nome'               => 'App Painel',
        'descricao'          => 'App para integração',
        'imagem_app'         => null,
        'chave_privada'      => '-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC1sakflQqRcKs8
ssPuJZUlRacksw+VnXhCcEwYa3GN2RmeWOccJEGObrR5umITk3Uy/cExUj/o+AiE
Fqmtq9/IpbCi3w0qgbs8ALilciWoEpQ07e/s8DOaj5Ywt5YbSPqvqq570iUWZ+hi
C1j8usIYoQVcvBgDJW9rJ5goJ66/y7rDHesiFRcUy1+YhPx9DnhuLM8r7W1CDr75
JXqm7TJ07B1azGnXuh77vBJKbe7hhpLimS+m4uSjv35uWNQQr3d93M6/UmbGOV2Q
ySiT0W7ktsDTw6nKkMyV0tWZkJZvuOAT8b3pElBIVh0E1j4fBpzzFP23H9vB+Rvx
Pm5PqogTAgMBAAECggEAO99bd8jJPrv98XaHEgbYavvU5EcNL6RAaMRQjwt40+Fi
i8eIAMIByEm6txIwF3tT94WfD/2micKSK9S7/TrR93CCEGmoEQXHCTLDpeFDf2r/
46E0msNcfeYq+8rDcCJQWWrJLIxHJGcBMK3EmSOBqlQFTFW/I+pGGO1nOnQ5JYIS
AQpVBtOlFVY+5jU3ZEAWp24JPG7MNh65yVoks68FsJiaY68CNqJNmnkHomHg1UDy
moY8a5vmIkmHUl+YvbWAjswsSyfQVaH4C0f2bbu4391wIvrgrDkSjz9upbdqerzl
aWJvrDTrIThzmEMa18TTg0QGDdgRXUdRqtgG6c8wQQKBgQDwgnpQy207ukyqQQmJ
5JKT7c7XSsBxLuNetBQANbg9/z6vbgqJabFtFVa74szU2yFIFQ9SdgpiaSmgV+M9
XHoafcu+PMBX79Q/Hl2X7N6BpNxJjWJhZtEpWMniQZ0Bz4H6n1qwxinLOwoiAFwq
iyAO0oMRKS31iRSwuqq0XCKa2QKBgQDBZW4XyboQ08sj9VRPcV8r3lZ3+AepFe6j
FApvtFn4fpyn9XcgU86DL5NCQogriCcVd0dN4JD8A98C+otEtLQUcy87Ydoi+OL9
MYHgt824t5lpL/a+Inu0ZjdNFrrSMaW0x4XTovjvAHO0pLYp1Euz0m8ClLNFm46n
yRKvo+TuywKBgQDh79D0IYZmO6dVsW9CiOVh7l8HRQPvz7ps9wJrCAwiwaujpd5k
JFQ3Q3qNWoxN0eU8D/yq3JT4yg3+wVGKDVvvkwlZlzyh330mJmKKHE0SwUroFde7
5JjNHMnasQTL9KIITLcnpaEReE8WjfwHQ1dEVWInNuYj6Tj0pQdEE+G2UQKBgQCQ
RZR9lnWBvB3c81Uz9oVi+nhTAurkDoJ5kae/cTF1GS7QdWOq8Bos7z7RvURMBUPy
1YqR5CcEefbSCAoA9TUp1Eu15ueOE1FyRI55D2UemYiOWcOeT6ctCtvSXFR+HZ9a
X0XMfdVqplGqvv1N8xuOSDucF0YtdUBUlsGj2YYdRQKBgQDjsfyFMmyVSiP26k5J
m/ln7Q3lMYjyLgOCZha4hQnYssoic5lRnyRei/b49wTOkn5WijG4W3OkBCuwfcy0
4njHoKowgcU7I/cEOKiACi/JbvEeS+NNoMwPCdrbxFno6zy4MjW/4601XeqHtac5
4PovwtLk7HihV7eDcofrLDqnLw==
-----END PRIVATE KEY-----',
        'chave_privada_fake' => '-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQC1sakflQqRcKs8
ssPuJZUlRacksw+VnXhCcEwYa3GN2RmeWOccJEGObrR5umITk3Uy/cExUj/o+AiE
Fqmtq9/IpbCi3w0qgbs8ALilciWoEpQ07e/s8DOaj5Ywt5YbSPqvqq570iUWZ+hi
C1j8usIYoQVcvBgDJW9rJ5goJ66/y7rDHesiFRcUy1+YhPx9DnhuLM8r7W1CDr75
JXqm7TJ07B1azGnXuh77vBJKbe7hhpLimS+m4uSjv35uWNQQr3d93M6/UmbGOV2Q
ySiT0W7ktsDTw6nKkMyV0tWZkJZvuOAT8b3pElBIVh0E1j4fBpzzFP23H9vB+Rvx
Pm5PqogTAgMBAAECggEAO99bd8jJPrv98XaHEgbYavvU5EcNL6RAaMRQjwt40+Fi
i8eIAMIByEm6txIwF3tT94WfD/2micKSK9S7/TrR93CCEGmoEQXHCTLDpeFDf2r/
46E0msNcfeYq+8rDcCJQWWrJLIxHJGcBMK3EmSOBqlQFTFW/I+pGGO1nOnQ5JYIS
AQpVBtOlFVY+5jU3ZEAWp24JPG7MNh65yVoks68FsJiaY68CNqJNmnkHomHg1UDy
moY8a5vmIkmHUl+YvbWAjswsSyfQVaH4C0f2bbu4391wIvrgrDkSjz9upbdqerzl
aWJvrDTrIThzmEMa18TTg0QGDdgRXUdRqtgG6c8wQQKBgQDwgnpQy207ukyqQQmJ
5JKT7c7XSsBxLuNetBQANbg9/z6vbgqJabFtFVa74szU2yFIFQ9SdgpiaSmgV+M9
XHoafcu+PMBX79Q/Hl2X7N6BpNxJjWJhZtEpWMniQZ0Bz4H6n1qwxinLOwoiAFwq
iyAO0oMRKS31iRSwuqq0XCKa2QKBgQDBZW4XyboQ08sj9VRPcV8r3lZ3+AepFe6j
FApvtFn4fpyn9XcgU86DL5NCQogriCcVd0dN4JD8A98C+otEtLQUcy87Ydoi+OL9
MYHgt824t5lpL/a+Inu0ZjdNFrrSMaW0x4XTovjvAHO0pLYp1Euz0m8ClLNFm46n
yRKvo+TuywKBgQDh79D0IYZmO6dVsW9CiOVh7l8HRQPvz7ps9wJrCAwiwaujpd5k
JFQ3Q3qNWoxN0eU8D/yq3JT4yg3+wVGKDVvvkwlZlzyh330mJmKKHE0SwUroFde7
5JjNHMnasQTL9KIITLcnpaEReE8WjfwHQ1dEVWInNuYj6Tj0pQdEE+G2UQKBgQCQ
RZR9lnWBvB3c81Uz9oVi+nhTAurkDoJ5kae/cTF1GS7QdWOq8Bos7z7RvURMBUPy
1YqR5CcEefbSCAoA9TUp1Eu15ueOE1FyRI55D2UemYiOWcOeT6ctCtvSXFR+HZ9a
X0XMfdVqplGqvv1N8xuOSDucF0YtdUBUlsGj2YYdRQKBgQDjsfyFMmyVSiP26k5J
m/ln7Q3lMYjyLgOCZha4hQnYssoic5lRnyRei/b49wTOkn5WijG4W3OkBCuwfcy0
4njHoKowgcU7I/cEOKiACi/JbvEeS+NNoMwPCdrbxFno6zy4MjW/4601XeqHtac5
4PovwtLk7HihV7eDcofrLDqnLw==
-----END PRIVATE KEY-----',
        'chave_publica'      => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtbGpH5UKkXCrPLLD7iWV
JUWnJLMPlZ14QnBMGGtxjdkZnljnHCRBjm60ebpiE5N1Mv3BMVI/6PgIhBapravf
yKWwot8NKoG7PAC4pXIlqBKUNO3v7PAzmo+WMLeWG0j6r6que9IlFmfoYgtY/LrC
GKEFXLwYAyVvayeYKCeuv8u6wx3rIhUXFMtfmIT8fQ54bizPK+1tQg6++SV6pu0y
dOwdWsxp17oe+7wSSm3u4YaS4pkvpuLko79+bljUEK93fdzOv1JmxjldkMkok9Fu
5LbA08OpypDMldLVmZCWb7jgE/G96RJQSFYdBNY+Hwac8xT9tx/bwfkb8T5uT6qI
EwIDAQAB
-----END PUBLIC KEY-----',
        'chave_publica_fake' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtbGpH5UKkXCrPLLD7iWV
JUWnJLMPlZ14QnBMGGtxjdkZnljnHCRBjm60ebpiE5N1Mv3BMVI/6PgIhBapravf
yKWwot8NKoG7PAC4pXIlqBKUNO3v7PAzmo+WMLeWG0j6r6que9IlFmfoYgtY/LrC
GKEFXLwYAyVvayeYKCeuv8u6wx3rIhUXFMtfmIT8fQ54bizPK+1tQg6++SV6pu0y
dOwdWsxp17oe+7wSSm3u4YaS4pkvpuLko79+bljUEK93fdzOv1JmxjldkMkok9Fu
5LbA08OpypDMldLVmZCWb7jgE/G96RJQSFYdBNY+Hwac8xT9tx/bwfkb8T5uT6qI
EwIDAQAB
-----END PUBLIC KEY-----',
        'secret_id'          => '76631-L78AuvFlyaQef6keC9%!iIt3Mk*NRSRbFrVeG$H2XwrH5!vboob%hILjidXC4v12tyj!jO4MWR',
        'secret_id_fake'     => '76631-L78AuvFlyaQef6keC9%!iIt3Mk*NRSRbFrVeG$H2XwrH5!vboob%hILjidXC4v12tyj!jO4MWR',
        'client_id'          => '6391793193-szIaoukiIh$62SH#mIPPYkrnn$ylHL2Li3*T4SMQyhv1UUeiRxA*%8yOwdSt1BkBEPTI#%ODLU.localhost.com',
        'client_id_fake'     => '6391793193-szIaoukiIh$62SH#mIPPYkrnn$ylHL2Li3*T4SMQyhv1UUeiRxA*%8yOwdSt1BkBEPTI#%ODLU.localhost.com',
        'audience'           => 'web',
        'authorization_code' => '1',
        'client_credentials' => '1',
        'refresh_token'      => '1',
        'redirect_uri'       => '["localhost.com:4000","localhost.com:8000","127.0.0.1"]',
        'scope_permitido'    => $scope,
        'campo_permitido'    => '{"usuario:salvar":["nome","cpf","email_trabalho","senha","telefone_celular"],"usuario:atualizar":["nome","cpf","email_trabalho","senha","telefone_celular"],"usuario:buscar":["uuid","nome","cpf"],"usuario:listar":["uuid","nome","cpf"]}',
        'tempo_vida'         => '50000',
        'data_criacao'       => '2023-07-04 17:22:05',
        'data_atualizacao'   => '2023-07-04 17:22:05',
        'status'             => '1'
    ],

    [
        'id'                 => '2',
        'uuid'               => '5add7e1c-3da1-4c0f-90b4-da17d4f05eca',
        'id_admin_empresa'   => '1',
        'nome'               => 'App Clube',
        'descricao'          => 'App para integração',
        'imagem_app'         => null,
        'chave_privada'      => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDBmtmEZ3fCBpww
s7bbPuXkrjX3uTGR9BcjhVsRIPxda+Qz+0ybznZIkHj1uXH0b+OdeNQgwNO+ZEvQ
OOlEMXZy1rJ7+qbOYkDIhS0T+MgB8uDg3FjguI0pX8CnH4PYS5fXK09R5lLRsWQQ
By2SLVxedIjbPKm67Vv6hiy/kNmvpZVuPKfaDB6X4bwyXHUWaT0J038F/av8Csp9
/rRaSe8IYz+50pPUY045+/z9RrAl0p3HJxnf38wuJv7rSkPcZiKkjjtWQXLQ8S1O
8Y85R4Uhtw3drLbzO1896xJvGFu+6WCO3CVgJmUcOAXinwd/dzCJNb8MVXn2xkZd
1KIVX7EVAgMBAAECggEASeE+mlElmSwOlGMwJ1BESFTWkVbhfLHp1otAKjQtObU/
WzrNjDNoXd6L2jFPNBkygdgnEuyioOgDKk1dbsF1UvNtHvSSjPVfhWWUwydPo7a8
a/KKFA85Bw3cJapYFMUcB2cmmLlM52pLJfanRD39HqpXw1nN52BGlj53ew7akzTw
X0z9vRb6Tj/tFIob4h8fdwAOOlUUpwgVnapgP9H/l6obiNGFCPfsYxrbA7EV6DXP
UKB+KDwDN7po0uyGEa09KCuZ8psIcaq6r1JV6Ee+Y6KOEfLNnSTXxjypLgB6B8aW
Tki3IZjkfSMrs+VQADkO57WhV2uG5ua8fhXanw0ebQKBgQD/gZkCngoiNVwBMXZ8
p531Dm2lYUt57VF8Fwd8zKJeY5OdOvszBUoy2Al9bce0P+EspA9n3Xw3kVXMNWzh
9pV0PASyFGk/sDUbmucsp+0BnSUDxQY5KhoJAC11eEl9I8K9mFAsmWL2G2qnW4Dj
ghRPRSyY/gDkuAz8chsqSDtSfwKBgQDB+qDpdWfah/Gop7+R15KrGVgPPJmOj9uP
gfq6m1vCyOMNDmezkyeWdBqfmDdik16jOAm8N70qA6UQTYCCJnmNLVU3lvrruO/H
h8nss1d6GLiiJDAfgn62oCCvqLgAVQad1gP6Y/uUM5vJQtyhFRKN5LMPi7oC5vtW
9BWBdITKawKBgDyiw+40FVGS+jeqRmVE3h6nAuxpj6Dq6hiZ2oQKiEoANartsWml
SruQO4hRwkALnOOcN6+9h1okmojw8NsbStKf37lnUKb1qVTYyR326C8m3P7tBhIQ
5MbYDPHAzyfska2u9O/wouSnEwOOmqLjXvCFCRVxykJ9T0+lwL6jD7j/AoGAVIGi
bnKWfu6wieAiph+7Js0QvvqQjzn+1gMzPu6FyFMKD1sSNHpMSdBk9Ng0cL5xATxd
iWDjCRWzaGnLLWgjliyEroYY5G6aD1wJzRPIbUmtzf1j9aY9G8Sg2MXw+zwPeN2O
uBuss0DUgpadVZO/lI9orsIZlEAaiSQm5lHjs+cCgYEA7qLYzSwUAq4aXhr3SF2k
u9kreU+6JZ99dOABaduJ9Rg7cqjqiJXMiAlwPnuVg7mKk4hCxXjvlOj0KMmmmkoZ
LMWHjhP/IMgxyi0mq6p0vPOq52ON4sCY0C8Cz4GClX9WyyBJx8+V6Y0tlywVY96L
d+D449R4JnigoYwXdnGjPss=
-----END PRIVATE KEY-----',
        'chave_privada_fake' => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDBmtmEZ3fCBpww
s7bbPuXkrjX3uTGR9BcjhVsRIPxda+Qz+0ybznZIkHj1uXH0b+OdeNQgwNO+ZEvQ
OOlEMXZy1rJ7+qbOYkDIhS0T+MgB8uDg3FjguI0pX8CnH4PYS5fXK09R5lLRsWQQ
By2SLVxedIjbPKm67Vv6hiy/kNmvpZVuPKfaDB6X4bwyXHUWaT0J038F/av8Csp9
/rRaSe8IYz+50pPUY045+/z9RrAl0p3HJxnf38wuJv7rSkPcZiKkjjtWQXLQ8S1O
8Y85R4Uhtw3drLbzO1896xJvGFu+6WCO3CVgJmUcOAXinwd/dzCJNb8MVXn2xkZd
1KIVX7EVAgMBAAECggEASeE+mlElmSwOlGMwJ1BESFTWkVbhfLHp1otAKjQtObU/
WzrNjDNoXd6L2jFPNBkygdgnEuyioOgDKk1dbsF1UvNtHvSSjPVfhWWUwydPo7a8
a/KKFA85Bw3cJapYFMUcB2cmmLlM52pLJfanRD39HqpXw1nN52BGlj53ew7akzTw
X0z9vRb6Tj/tFIob4h8fdwAOOlUUpwgVnapgP9H/l6obiNGFCPfsYxrbA7EV6DXP
UKB+KDwDN7po0uyGEa09KCuZ8psIcaq6r1JV6Ee+Y6KOEfLNnSTXxjypLgB6B8aW
Tki3IZjkfSMrs+VQADkO57WhV2uG5ua8fhXanw0ebQKBgQD/gZkCngoiNVwBMXZ8
p531Dm2lYUt57VF8Fwd8zKJeY5OdOvszBUoy2Al9bce0P+EspA9n3Xw3kVXMNWzh
9pV0PASyFGk/sDUbmucsp+0BnSUDxQY5KhoJAC11eEl9I8K9mFAsmWL2G2qnW4Dj
ghRPRSyY/gDkuAz8chsqSDtSfwKBgQDB+qDpdWfah/Gop7+R15KrGVgPPJmOj9uP
gfq6m1vCyOMNDmezkyeWdBqfmDdik16jOAm8N70qA6UQTYCCJnmNLVU3lvrruO/H
h8nss1d6GLiiJDAfgn62oCCvqLgAVQad1gP6Y/uUM5vJQtyhFRKN5LMPi7oC5vtW
9BWBdITKawKBgDyiw+40FVGS+jeqRmVE3h6nAuxpj6Dq6hiZ2oQKiEoANartsWml
SruQO4hRwkALnOOcN6+9h1okmojw8NsbStKf37lnUKb1qVTYyR326C8m3P7tBhIQ
5MbYDPHAzyfska2u9O/wouSnEwOOmqLjXvCFCRVxykJ9T0+lwL6jD7j/AoGAVIGi
bnKWfu6wieAiph+7Js0QvvqQjzn+1gMzPu6FyFMKD1sSNHpMSdBk9Ng0cL5xATxd
iWDjCRWzaGnLLWgjliyEroYY5G6aD1wJzRPIbUmtzf1j9aY9G8Sg2MXw+zwPeN2O
uBuss0DUgpadVZO/lI9orsIZlEAaiSQm5lHjs+cCgYEA7qLYzSwUAq4aXhr3SF2k
u9kreU+6JZ99dOABaduJ9Rg7cqjqiJXMiAlwPnuVg7mKk4hCxXjvlOj0KMmmmkoZ
LMWHjhP/IMgxyi0mq6p0vPOq52ON4sCY0C8Cz4GClX9WyyBJx8+V6Y0tlywVY96L
d+D449R4JnigoYwXdnGjPss=
-----END PRIVATE KEY-----',
        'chave_publica'      => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwZrZhGd3wgacMLO22z7l
5K4197kxkfQXI4VbESD8XWvkM/tMm852SJB49blx9G/jnXjUIMDTvmRL0DjpRDF2
ctaye/qmzmJAyIUtE/jIAfLg4NxY4LiNKV/Apx+D2EuX1ytPUeZS0bFkEActki1c
XnSI2zypuu1b+oYsv5DZr6WVbjyn2gwel+G8Mlx1Fmk9CdN/Bf2r/ArKff60Wknv
CGM/udKT1GNOOfv8/UawJdKdxycZ39/MLib+60pD3GYipI47VkFy0PEtTvGPOUeF
IbcN3ay28ztfPesSbxhbvulgjtwlYCZlHDgF4p8Hf3cwiTW/DFV59sZGXdSiFV+x
FQIDAQAB
-----END PUBLIC KEY-----',
        'chave_publica_fake' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwZrZhGd3wgacMLO22z7l
5K4197kxkfQXI4VbESD8XWvkM/tMm852SJB49blx9G/jnXjUIMDTvmRL0DjpRDF2
ctaye/qmzmJAyIUtE/jIAfLg4NxY4LiNKV/Apx+D2EuX1ytPUeZS0bFkEActki1c
XnSI2zypuu1b+oYsv5DZr6WVbjyn2gwel+G8Mlx1Fmk9CdN/Bf2r/ArKff60Wknv
CGM/udKT1GNOOfv8/UawJdKdxycZ39/MLib+60pD3GYipI47VkFy0PEtTvGPOUeF
IbcN3ay28ztfPesSbxhbvulgjtwlYCZlHDgF4p8Hf3cwiTW/DFV59sZGXdSiFV+x
FQIDAQAB
-----END PUBLIC KEY-----',
        'secret_id'          => '17098-XGKcq9sQPVFwWkFlU2VuDQ#Ex#JuEnG8qQsP1B8ITNmLc6FD0cGEjmH3RvrmNgOGHitQLUY%MS',
        'secret_id_fake'     => '17098-XGKcq9sQPVFwWkFlU2VuDQ#Ex#JuEnG8qQsP1B8ITNmLc6FD0cGEjmH3RvrmNgOGHitQLUY%MS',
        'client_id'          => '5537870833-uudRDzy0PsFqZ$Hw9#5amskM4ukDM#AGn7omv4jUAmN6Q0ib9O2awBkyOo0ywp6Qcd4NlcqZ*l.localhost.com',
        'client_id_fake'     => '5537870833-uudRDzy0PsFqZ$Hw9#5amskM4ukDM#AGn7omv4jUAmN6Q0ib9O2awBkyOo0ywp6Qcd4NlcqZ*l.localhost.com',
        'audience'           => 'clube',
        'authorization_code' => '2',
        'client_credentials' => '2',
        'refresh_token'      => '1',
        'redirect_uri'       => '["clube.markt.club"]',
        'scope_permitido'    => $scope,
        'campo_permitido'    => '{"usuario:salvar":["nome","cpf","email_trabalho","senha","telefone_celular"],"usuario:atualizar":["nome","cpf","email_trabalho","senha","telefone_celular"],"usuario:buscar":["uuid","nome","cpf"],"usuario:listar":["uuid","nome","cpf"]}',
        'tempo_vida'         => '50000',
        'data_criacao'       => '2023-07-05 17:22:05',
        'data_atualizacao'   => '2023-07-05 16:08:51',
        'status'             => '1'
    ]
];
