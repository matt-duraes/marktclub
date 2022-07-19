<?php

use Route\Route;
use App\Middlewares\Api\TokenMiddleware;

Route
    ::nome('robo')
    ::controller(App\Controllers\Api\RoboController::class)
    ::grupo(function () {
        Route
            ::nome('emenda')
            ::get('/robo/emenda');
    });

Route
    ::nome('documentacao')
    ::controller(App\Controllers\Api\DocumentacaoController::class)
    ::middleware(App\Middlewares\SistemaMiddleware::class, 'tipo', ['producao'])
    ::grupo(function () {
        Route
            ::nome('login')
            ::view('/documentacao/login');

        Route
            ::nome('login')
            ::request(['login', 'senha', 'hash_validacao_captcha'])
            ::post('/documentacao/login');

        Route
            ::nome('index')
            ::view('/documentacao');

        Route
            ::nome('fluxograma')
            ::view('/documentacao/fluxograma');

        Route
            ::nome('retorno')
            ::view('/documentacao/retorno');

        Route
            ::nome('rota')
            ::view('/documentacao/rotas/{rota}');

        Route
            ::nome('senha')
            ::request(['senha_atual', 'senha_nova', 'senha_repetir', 'hash_validacao'])
            ::post('/documentacao/senha');

        Route
            ::nome('mostrarSecretId')
            ::request(['senha', 'id', 'tipo', 'hash_validacao'])
            ::post('/documentacao/mostrar-secret-id');

        Route
            ::nome('mostrarChavePublica')
            ::request(['senha', 'id', 'tipo', 'hash_validacao'])
            ::post('/documentacao/mostrar-chave-publica');

        Route
            ::nome('resetarSecretId')
            ::request(['senha', 'id', 'hash_validacao'])
            ::post('/documentacao/resetar-secret-id');

        Route
            ::nome('resetarChavePublica')
            ::request(['senha', 'id', 'hash_validacao'])
            ::post('/documentacao/resetar-chave-publica');

        Route
            ::nome('app')
            ::view('/documentacao/app');

        Route
            ::nome('sair')
            ::view('/documentacao/sair');
    });

Route::nome('usuario_cliente')
    ::controller(App\Controllers\Api\UsuarioClienteController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:listar'])
            ::request(['pagina', '!pesquisa', '!pagamento', '!nome', '!email', '!cpf', '!data_upload', '!data_criacao_de', '!data_criacao_ate', '!matricula', '!status', '!ordem'])
            ::get('/usuario-cliente');
        Route
            ::nome('download')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:download'])
            ::request(['campo', '!pesquisa', '!pagamento', '!nome', '!email', '!cpf', '!data_upload', '!data_criacao_de', '!data_criacao_ate', '!matricula', '!status', '!ordem'])
            ::post('/usuario-cliente/download');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:buscar'])
            ::get('/usuario-cliente/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:salvar'])
            ::request([
                '!nome', '!cpf', '!matricula', '!siape', '!genero', '!data_nascimento', '!email_trabalho',
                '!email_trabalho', '!email_pessoal', '!telefone_trabalho', '!telefone_pessoal', '!senha', '!status',
                '!primeiro_acesso', '!mudar_senha', '!estado_civil', '!endereco_estado', '!endereco_cidade',
                '!endereco_cep', '!endereco_logradouro', '!endereco_numero', '!endereco_complemento',
                '!endereco_bairro', '!situacao', '!trabalho_empresa', '!trabalho_cargo', '!tipo_pagamento',
                '!trabalho_data_inicio',
            ])
            ::post('/usuario-cliente');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:atualizar'])
            ::request([
                '!nome', '!cpf', '!matricula', '!siape', '!genero', '!data_nascimento', '!email_trabalho',
                '!email_trabalho', '!email_pessoal', '!telefone_trabalho', '!telefone_pessoal', '!senha', '!status',
                '!primeiro_acesso', '!mudar_senha', '!estado_civil', '!endereco_estado', '!endereco_cidade',
                '!endereco_cep', '!endereco_logradouro', '!endereco_numero', '!endereco_complemento',
                '!endereco_bairro', '!situacao', '!trabalho_empresa', '!trabalho_cargo', '!tipo_pagamento',
                '!trabalho_data_inicio',
            ])
            ::put('/usuario-cliente/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:deletar'])
            ::delete('/usuario-cliente/{id}');
    });

Route::nome('usuario_dependente')
    ::controller(App\Controllers\Api\UsuarioDependenteController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_dependente:listar'])
            ::request(['usuario'])
            ::get('/usuario-dependente');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_dependente:salvar'])
            ::request([
                'nome', 'cpf', 'email', 'usuario'
            ])
            ::post('/usuario-dependente');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_dependente:deletar'])
            ::delete('/usuario-dependente/{id}');
    });

Route::nome('usuario_indicacao')
    ::controller(App\Controllers\Api\UsuarioIndicacaoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_indicacao:salvar'])
            ::request(['usuario', 'nome', 'email', 'telefone'])
            ::post('/usuario-indicacao');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_indicacao:listar'])
            ::request(['pagina', '!pesquisa', '!nome', '!email', '!status', '!ordem'])
            ::get('/usuario-indicacao');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_indicacao:buscar'])
            ::get('/usuario-indicacao/{id}');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_indicacao:atualizar'])
            ::request(['status'])
            ::put('/usuario-indicacao/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_indicacao:deletar'])
            ::delete('/usuario-indicacao/{id}');
    });

Route
    ::nome('usuario_lead')
    ::controller(App\Controllers\Api\UsuarioLeadController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_lead:salvar'])
            ::request([
                '!nome', '!email_trabalho', '!email_pessoal', '!email_funcional', '!telefone_pessoal',
                '!telefone_trabalho', '!cpf', '!rg', '!siape', '!genero', '!data_nascimento', '!trabalho_empresa',
                '!trabalho_cargo', '!trabalho_data_inicio', '!endereco_cep', '!endereco_logradouro',
                '!endereco_numero', '!endereco_complemento', '!endereco_bairro', '!endereco_cidade',
                '!endereco_estado', '!termo_aceitar', '!termo_lgpd', '!lista_dependente'
            ])
            ::post('/usuario-lead');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_lead:listar'])
            ::request(['pagina', '!pesquisa', '!nome', '!email', '!cpf', '!status', '!ordem'])
            ::get('/usuario-lead');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_lead:buscar'])
            ::get('/usuario-lead/{id}');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_lead:atualizar'])
            ::request(['status'])
            ::put('/usuario-lead/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_lead:deletar'])
            ::delete('/usuario-lead/{id}');
    });

Route
    ::nome('usuario_pagamento')
    ::controller(App\Controllers\Api\UsuarioPagamentoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_pagamento:listar'])
            ::request([
                'pagina', '!pesquisa', '!nome', '!cpf', '!data_cobranca_de', '!data_cobranca_ate',
                '!data_pagamento_de', '!data_pagamento_ate', '!status', '!ordem'
            ])
            ::get('/usuario-pagamento');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_pagamento:buscar'])
            ::get('/usuario-pagamento/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_pagamento:salvar'])
            ::request(['data', 'valor', 'usuario'])
            ::post('/usuario-pagamento');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_pagamento:atualizar'])
            ::request(['status'])
            ::put('/usuario-pagamento/{id}');
    });

Route
    ::nome('usuario_equipe')
    ::controller(App\Controllers\Api\UsuarioEquipeController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:listar'])
            ::request(['pagina', '!pesquisa', '!nome', '!email', '!cpf', '!status', '!ordem'])
            ::get('/usuario-equipe');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:buscar'])
            ::get('/usuario-equipe/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:salvar'])
            ::request([
                '!nome', '!cpf', '!genero', '!data_nascimento', '!email_trabalho',
                '!email_pessoal', '!telefone_trabalho', '!telefone_pessoal', '!permissao',
                '!senha', '!status', '!primeiro_acesso', '!mudar_senha'
            ])
            ::post('/usuario-equipe');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:atualizar'])
            ::request([
                '!nome', '!cpf', '!genero', '!data_nascimento', '!email_trabalho',
                '!email_pessoal', '!telefone_trabalho', '!telefone_pessoal', '!permissao',
                '!senha', '!status', '!primeiro_acesso', '!mudar_senha'
            ])
            ::put('/usuario-equipe/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:deletar'])
            ::delete('/usuario-equipe/{id}');

        Route
            ::nome('validarSenha')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:validar_senha'])
            ::request(['senha'])
            ::post('/usuario-equipe/validar-senha');
    });

Route::nome('tabela')
    ::controller(App\Controllers\Api\TabelaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['tabela_usuario:salvar'])
            ::request(['hash'])
            ::post('/tabela/salvar');

        Route
            ::nome('bloquear')
            ::middleware(TokenMiddleware::class, 'scope', ['tabela_usuario:bloquear'])
            ::request(['hash'])
            ::post('/tabela/bloquear');
    });

Route::nome('relatorio')
    ::controller(App\Controllers\Api\RelatorioController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('usuarioStatus')
            ::get('/relatorio/usuario-status');

        Route
            ::nome('usuarioEstado')
            ::get('/relatorio/usuario-estado');

        Route
            ::nome('usuarioAcesso')
            ::request(['de', 'ate'])
            ::get('/relatorio/usuario-acesso');

        Route
            ::nome('usuarioGenero')
            ::get('/relatorio/usuario-genero');

        Route
            ::nome('usuarioSituacao')
            ::get('/relatorio/usuario-situacao');

        Route
            ::nome('usuarioEstadoCivil')
            ::get('/relatorio/usuario-estado-civil');

        Route
            ::nome('usuarioFaixaEtaria')
            ::get('/relatorio/usuario-faixa-etaria');

        Route
            ::nome('usuarioSemDado')
            ::get('/relatorio/usuario-sem-dado');

        Route
            ::nome('usuarioAtualizarDado')
            ::get('/relatorio/usuario-atualizar-dado');

        Route
            ::nome('maisAcessado')
            ::request(['local', 'de', 'ate'])
            ::get('/relatorio/mais-acessado');

        Route
            ::nome('dispositivo')
            ::request(['tipo', 'de', 'ate'])
            ::get('/relatorio/dispositivo');

        Route
            ::nome('analytics')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_analytics:listar'])
            ::request(['!usuario', 'de', 'ate'])
            ::get('/relatorio/analytics');
        Route
            ::nome('analyticsDownload')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_analytics:download'])
            ::post('/relatorio/analytics-download');
    });

Route
    ::nome('log')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\LogController::class)
    ::grupo(function () {
        Route
            ::nome('error')
            ::request(['mensagem', 'codigo', 'arquivo', 'linha', 'trace', 'status'])
            ::post('/log/error');
    });

Route
    ::nome('login')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\LoginController::class)
    ::grupo(function () {
        Route
            ::nome('loginPainel')
            ::middleware(TokenMiddleware::class, 'scope', ['login:painel'])
            ::request([
                '!login', '!senha', '!facebook', '!google', 'scope', 'audience', 'redirect_uri', 'state'
            ])
            ::post('/login/painel');

        Route
            ::nome('loginApi')
            ::middleware(TokenMiddleware::class, 'scope', ['login:api'])
            ::request([
                'nome', 'cpf', '!matricula', '!siape', '!genero', '!data_nascimento', '!email_trabalho',
                '!email_pessoal', '!telefone_trabalho', '!telefone_pessoal', '!estado_civil',
                '!endereco_estado', '!endereco_cidade', 'federacao', 'salavip', 'grupo'
            ])
            ::post('/login/api');
    });

Route
    ::nome('loginOk')
    ::controller(App\Controllers\Api\LoginController::class)
    ::grupo(function () {
        Route
            ::nome('loginApiOk')
            ::middleware(App\Middlewares\SistemaMiddleware::class, 'tipo', ['HOMOLOGACAO'])
            ::view('/login/api-ok/{hash}');
    });

Route
    ::nome('painel')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\PainelController::class)
    ::grupo(function () {
        Route
            ::nome('permissao')
            ::get('/admin/permissao');

        Route
            ::nome('configuracao')
            ::get('/admin/configuracao');

        Route
            ::nome('menu')
            ::get('/admin/menu');

        Route
            ::nome('campoObrigatorio')
            ::request(['!app'])
            ::get('/admin/campo-obrigatorio');

        Route
            ::nome('campoPermitido')
            ::request(['!app'])
            ::get('/admin/campo-permitido');

        Route
            ::nome('trabalhoOrgao')
            ::get('/admin/trabalho-orgao');

        Route
            ::nome('trabalhoCargo')
            ::get('/admin/trabalho-cargo');

        Route
            ::nome('tipoPagamento')
            ::get('/admin/tipo-pagamento');

        Route
            ::nome('usuarioSituacao')
            ::get('/admin/usuario-situacao');

        Route
            ::nome('chavePublica')
            ::middleware(TokenMiddleware::class, 'scope', ['admin:chave_publica'])
            ::get('/admin/chave-publica');

        Route
            ::nome('chavePrivada')
            ::middleware(TokenMiddleware::class, 'scope', ['admin:chave_privada'])
            ::get('/admin/chave-privada');
    });

Route
    ::nome('painel_historico')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\PainelHistoricoController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::request([
                'relacionado', 'app', 'acao', '!dado', '!mensagem'
            ])
            ::post('/painel-historico');

        Route
            ::nome('listar')
            ::request([
                'pagina', 'app', 'relacionado', '!data_de', '!data_ate'
            ])
            ::get('/painel-historico');

        Route
            ::nome('atualizar')
            ::request(['mensagem'])
            ::put('/painel-historico/{id}');

        Route
            ::nome('deletar')
            ::delete('/painel-historico/{id}');
    });

Route
    ::nome('token')
    ::controller(App\Controllers\Api\TokenController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::request(['client_id', 'secret_id', 'audience', 'grant_type', 'scope'])
            ::post('/token');
        Route
            ::nome('id')
            ::get('/token/{id}');
        Route
            ::nome('listar')
            ::request(['pagina', '!ordem'])
            ::get('/token');
        Route
            ::nome('deletar')
            ::delete('/token/{id}');
    });

Route
    ::nome('campanha_sorteio')
    ::controller(App\Controllers\Api\CampanhaSorteioController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['campanha_sorteio:buscar'])
            ::get('/campanha-sorteio/{id}');

        Route
            ::nome('resultado')
            ::middleware(TokenMiddleware::class, 'scope', ['campanha_sorteio:sortear'])
            ::request(['id'])
            ::post('/campanha-sorteio/resultado');

        Route
            ::nome('resultado')
            ::middleware(TokenMiddleware::class, 'scope', ['campanha_sorteio:resultado'])
            ::get('/campanha-sorteio/resultado/{id}/{hash}');
    });

Route
    ::nome('solicitacao_voucher')
    ::controller(App\Controllers\Api\SolicitacaoVoucherController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_voucher:listar'])
            ::request(['pagina', '!ordem', '!status', '!data_criacao_de', '!data_criacao_ate'])
            ::get('/solicitacao-voucher');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_voucher:buscar'])
            ::get('/solicitacao-voucher/{id}');
    });

Route
    ::nome('solicitacao_salavip')
    ::controller(App\Controllers\Api\SolicitacaoSalavipController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_salavip:listar'])
            ::request(['pagina', '!ordem', '!empresa', '!data_de', '!data_ate'])
            ::get('/solicitacao-salavip');
        Route
            ::nome('download')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_salavip:download'])
            ::request(['campo', '!ordem', '!empresa', '!data_de', '!data_ate'])
            ::post('/solicitacao-salavip/download');
    });
