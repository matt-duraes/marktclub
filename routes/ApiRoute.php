<?php

use Route\Route;
use App\Middlewares\Api\TokenMiddleware;
use App\Middlewares\Api\MarktClubMiddleware;

Route
    ::nome('downloadRestrito')
    ::controller(App\Controllers\Api\DownloadRestritoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/download-restrito/hash/{id}');
        Route
            ::nome('email')
            ::request(['hash_validacao', 'id'])
            ::post('/download-restrito/email');
        Route
            ::nome('validar')
            ::request(['id', 'codigo'])
            ::post('/download-restrito/validar');
        Route
            ::nome('download')
            ::view('/download-restrito/download/{id}/{codigo}');
    });

Route::nome('downloadSistema')
    ::controller(App\Controllers\Api\DownloadSistemaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::get('/download-privado/{id}');
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
            ::nome('mostrarChavePrivada')
            ::request(['senha', 'id', 'tipo', 'hash_validacao'])
            ::post('/documentacao/mostrar-chave-privada');

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

Route::nome('usuario_cliente_download')
    ::controller(App\Controllers\Api\UsuarioClienteController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('download')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:download'])
            ::request([
                'campo', 'usuario', '!pesquisa', '!pagamento', '!nome', '!email', '!cpf', '!data_upload', '!data_criacao_de',
                '!data_criacao_ate', '!matricula', '!status', '!ordem', '!dependente'
            ])
            ::post('/usuario-cliente/download');
    });

Route::nome('usuario_cliente')
    ::controller(App\Controllers\Api\UsuarioClienteController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::criptografia(App\Classes\UsuarioCliente\Helper::CRIPTOGRAFAR)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:listar'])
            ::request([
                'pagina', '!pesquisa', '!pagamento', '!nome', '!email', '!cpf', '!data_upload', '!data_criacao_de',
                '!data_criacao_ate', '!matricula', '!status', '!lead', '!ordem', '!origem', '!dependente'
            ], 'json')
            ::get('/usuario-cliente');

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
                '!trabalho_data_inicio', '!grupo'
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
                '!trabalho_data_inicio', '!grupo'
            ])
            ::put('/usuario-cliente/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:deletar'])
            ::delete('/usuario-cliente/{id}');
    });

Route::nome('usuario_grupo')
    ::controller(App\Controllers\Api\UsuarioGrupoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:listar'])
            ::request(['pagina', '!quantidade', '!pesquisa', '!status'], 'json')
            ::get('/usuario-grupo');

        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:listar'])
            ::get('/usuario-grupo/select');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:buscar'])
            ::get('/usuario-grupo/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:salvar'])
            ::request([
                'indice', 'titulo', 'status'
            ])
            ::post('/usuario-grupo');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:atualizar'])
            ::request([
                'indice', 'titulo', 'status'
            ])
            ::put('/usuario-grupo/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:deletar'])
            ::delete('/usuario-grupo/{id}');
    });

Route::nome('usuario_dependente')
    ::controller(App\Controllers\Api\UsuarioDependenteController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::criptografia(App\Classes\UsuarioCliente\Helper::CRIPTOGRAFAR)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_dependente:listar'])
            ::request(['usuario'], 'json')
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
    ::criptografia(App\Classes\UsuarioIndicacao\Helper::CRIPTOGRAFAR)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_indicacao:salvar'])
            ::request(['usuario', 'nome', 'email', 'telefone'])
            ::post('/usuario-indicacao');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_indicacao:listar'])
            ::request(['pagina', '!pesquisa', '!nome', '!email', '!status', '!ordem'], 'json')
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
    ::criptografia(App\Classes\UsuarioLead\Helper::CRIPTOGRAFAR)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_lead:salvar'])
            ::request([
                '!nome', '!email_trabalho', '!email_pessoal', '!email_funcional', '!telefone_pessoal',
                '!telefone_trabalho', '!cpf', '!rg', '!siape', '!genero', '!data_nascimento', '!trabalho_empresa',
                '!trabalho_cargo', '!trabalho_data_inicio', '!endereco_cep', '!endereco_logradouro',
                '!endereco_numero', '!endereco_complemento', '!endereco_bairro', '!endereco_cidade',
                '!endereco_estado', '!termo_aceitar', '!termo_lgpd', '!lista_dependente', '!origem',
                '!cnpj_trabalho'
            ])
            ::post('/usuario-lead');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_lead:listar'])
            ::request(['pagina', '!pesquisa', '!nome', '!email', '!cpf', '!status', '!origem', '!ordem'], 'json')
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
    ::criptografia(App\Classes\UsuarioPagamento\Helper::CRIPTOGRAFIA)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_pagamento:listar'])
            ::request([
                'pagina', '!pesquisa', '!nome', '!cpf', '!data_cobranca_de', '!data_cobranca_ate',
                '!data_pagamento_de', '!data_pagamento_ate', '!status', '!ordem'
            ], 'json')
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
    ::criptografia(App\Classes\UsuarioEquipe\Helper::CRIPTOGRAFAR)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:listar'])
            ::request(['pagina', '!quantidade', '!pesquisa', '!nome', '!email', '!cpf', '!status', '!ordem'], 'json')
            ::get('/usuario-equipe');

        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:listar'])
            ::request(['!titulo'], 'json')
            ::get('/usuario-equipe/select');

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
                '!senha', '!status', '!primeiro_acesso', '!mudar_senha', '!imagem_facebook',
                '!imagem_google', '!imagem_arquivo', '!id_facebook', '!id_google'
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
            ::nome('dadoUsuario')
            ::get('/relatorio/dado-usuario');

        Route
            ::nome('acessoDia')
            ::request(['de', 'ate'], 'json')
            ::get('/relatorio/acesso-dia');

        Route
            ::nome('usuarioMaisAcesso')
            ::request(['de', 'ate'], 'json')
            ::get('/relatorio/usuario-mais-acesso');
        Route
            ::nome('paginaMaisAcessada')
            ::request(['de', 'ate'], 'json')
            ::get('/relatorio/pagina-mais-acessada');
        Route
            ::nome('lojaMaisAcessada')
            ::request(['de', 'ate'], 'json')
            ::get('/relatorio/loja-mais-acessada');

        Route
            ::nome('dispositivo')
            ::request(['de', 'ate'], 'json')
            ::get('/relatorio/dispositivo');
        Route
            ::nome('os')
            ::request(['de', 'ate'], 'json')
            ::get('/relatorio/os');
        Route
            ::nome('navegador')
            ::request(['de', 'ate'], 'json')
            ::get('/relatorio/navegador');

        Route
            ::nome('analytics')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_analytics:listar'])
            ::request(['!usuario', '!de', '!ate'], 'json')
            ::request(['!de', '!ate'], 'get')
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
    ::controller(App\Controllers\Api\LoginController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('loginPainel')
            ::middleware(TokenMiddleware::class, 'scope', ['login:painel'])
            ::criptografia(['login', 'senha', 'google', 'facebook'])
            ::request([
                '!login', '!senha', '!facebook', '!google', 'scope', 'audience', 'redirect_uri', 'state'
            ])
            ::post('/login/painel');

        Route
            ::nome('loginClube')
            ::middleware(TokenMiddleware::class, 'scope', ['login:clube'])
            ::request([
                '!login', '!senha', '!facebook', '!google', 'scope', 'audience', 'redirect_uri', 'state', 'client_id'
            ])
            ::post('/login/clube');

        Route
            ::nome('loginApi')
            ::middleware(TokenMiddleware::class, 'scope', ['login:api'])
            ::criptografia(App\Classes\UsuarioCliente\Helper::CRIPTOGRAFAR)
            ::request([
                'nome', 'cpf', '!matricula', '!siape', '!genero', '!data_nascimento', '!email_trabalho',
                '!email_pessoal', '!telefone_trabalho', '!telefone_pessoal', '!estado_civil',
                '!endereco_estado', '!endereco_cidade', '!federacao', '!salavip', '!grupo'
            ])
            ::post('/login/api');

        Route
            ::nome('loginDigio')
            ::middleware(TokenMiddleware::class, 'scope', ['login:digio'])
            ::request(['usuario'])
            ::post('/login/digio');

        Route
            ::nome('loginToken')
            ::middleware(TokenMiddleware::class, 'scope', ['login:token'])
            ::request(['clube', 'usuario'])
            ::post('/login/token');
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
    ::nome('mensageria')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\MensageriaController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::request(['payload', 'tipo'])
            ::criptografia(['payload'])
            ::post('/mensageria');
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
            ::request(['!app'], 'json')
            ::get('/admin/campo-obrigatorio');

        Route
            ::nome('campoPermitido')
            ::request(['!app'], 'json')
            ::get('/admin/campo-permitido');

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
            ::request(['pagina', '!ordem'], 'json')
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
            ::request(['pagina', '!ordem', '!status', '!data_criacao_de', '!data_criacao_ate'], 'json')
            ::get('/solicitacao-voucher');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_voucher:buscar'])
            ::get('/solicitacao-voucher/{id}');
    });

Route
    ::nome('construtor')
    ::controller(App\Controllers\Api\ConstrutorController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('clube')
            ::middleware(TokenMiddleware::class, 'scope', ['construtor:clube'])
            ::get('/construtor/clube/{id}');

        Route
            ::nome('pagina')
            ::middleware(TokenMiddleware::class, 'scope', ['construtor:pagina'])
            ::get('/construtor/pagina/{url}');
    });

Route
    ::nome('convenio_parceiro')
    ::controller(App\Controllers\Api\ConvenioParceiroController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('destaque')
            ::middleware(TokenMiddleware::class, 'scope', ['convenio_parceiro:destaque'])
            ::request(['categoria', 'quantidade', 'ordem'], 'json')
            ::get('/convenio-parceiro/destaque');

        Route
            ::nome('buscar')
            ::request(['!email'], 'json')
            ::get('/convenio-parceiro/{url}');
    });

Route
    ::nome('solicitacao_salavip')
    ::controller(App\Controllers\Api\SolicitacaoSalavipController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_salavip:listar'])
            ::request(['pagina', '!ordem', '!empresa', '!data_de', '!data_ate'], 'json')
            ::get('/solicitacao-salavip');
        Route
            ::nome('download')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_salavip:download'])
            ::request(['campo', '!ordem', '!empresa', '!data_de', '!data_ate'])
            ::post('/solicitacao-salavip/download');
    });

Route
    ::nome('ponto_cvs')
    ::controller(App\Controllers\Api\PontoCvsController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::criptografia(App\Classes\PontoCvs\Helper::CRIPTOGRAFAR)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['ponto_cvs:listar'])
            ::request(['pagina', '!quantidade', '!ordem', '!cpf', '!status'], 'json')
            ::get('/ponto-cvs');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['ponto_cvs:buscar'])
            ::get('/ponto-cvs/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['ponto_cvs:salvar'])
            ::request(['ponto_solicitado', 'cpf', 'email', '!nome'])
            ::post('/ponto-cvs');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['ponto_cvs:atualizar'])
            ::request(['!voucher', '!mensagem', 'status'])
            ::put('/ponto-cvs/{id}');
    });

Route::nome('api_app')
    ::controller(App\Controllers\Api\ApiAppController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::middleware(MarktClubMiddleware::class, 'validar')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['app_api:listar'])
            ::request(['pagina', '!pesquisa', '!nome', '!id_admin_empresa', '!status', '!ordem'], 'json')
            ::get('/api-app');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['app_api:buscar'])
            ::get('/api-app/{id}');
    });

Route::nome('api_app')
    ::controller(App\Controllers\Api\ApiUsuarioController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::middleware(MarktClubMiddleware::class, 'validar')
    ::grupo(function () {
        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['app_usuario:listar'])
            ::get('/api-usuario/select');
    });

Route::nome('admin_empresa')
    ::controller(App\Controllers\Api\AdminEmpresaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::middleware(MarktClubMiddleware::class, 'validar')
    ::grupo(function () {
        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['admin_empresa:listar'])
            ::request(['!titulo'], 'json')
            ::get('/admin-empresa/select');
    });

Route::nome('demandaDado')
    ::controller(App\Controllers\Api\DemandaDadoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_dado:listar'])
            ::request(['status', 'ordem'], 'json')
            ::get('/demanda-dado');

        Route
            ::nome('buscar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_dado:buscar'])
            ::get('/demanda-dado/{id}');

        Route
            ::nome('salvar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_dado:salvar'])
            ::request(['empresa', 'titulo', 'tipo'])
            ::post('/demanda-dado');

        Route
            ::nome('atualizar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_dado:atualizar'])
            ::request([
                '!titulo', '!arquivo', '!id_admin_empresa', '!id_usuario_equipe', '!data_entrega',
                '!com_prazo', '!status', '!ordem'
            ])
            ::put('/demanda-dado/{id}');
    });

Route::nome('demandaTarefa')
    ::controller(App\Controllers\Api\DemandaTarefaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_tarefa:salvar'])
            ::request(['demanda', 'titulo', 'texto', 'tipo', '!minuto_producao_estimada', '!equipe'])
            ::post('/demanda-tarefa');
        Route
            ::nome('buscar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_tarefa:buscar'])
            ::get('/demanda-tarefa/{id}');
        Route
            ::nome('atualizar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_tarefa:atualizar'])
            ::request(['titulo', 'texto', 'tipo', 'minuto_producao_estimada'])
            ::put('/demanda-tarefa/{id}');
        Route
            ::nome('deletar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_tarefa:deletar'])
            ::delete('/demanda-tarefa/{id}');
        Route
            ::nome('like')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_tarefa:deletar'])
            ::post('/demanda-tarefa/like/{id}');
        Route
            ::nome('deslike')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_tarefa:deletar'])
            ::request(['motivo'])
            ::post('/demanda-tarefa/deslike/{id}');
    });

Route::nome('demandaTrabalho')
    ::controller(App\Controllers\Api\DemandaTrabalhoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_trabalho:salvar'])
            ::request(['tarefa'])
            ::post('/demanda-trabalho');
        Route
            ::nome('atualizar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_trabalho:atualizar'])
            ::request(['acao'])
            ::put('/demanda-trabalho/{id}');
    });

Route
    ::nome('rotina')
    ::controller(App\Controllers\Api\RotinaController::class)
    ::grupo(function () {
        Route
            ::nome('relatorioAnalytics')
            ::request(['!data'], 'json')
            ::get('/rotina/relatorio-analytics');
        Route
            ::nome('relatorioUsuario')
            ::get('/rotina/relatorio-usuario');
    });

Route
    ::nome('emeilmarketing')
    ::controller(App\Controllers\Api\EmailMarketing::class)
    ::grupo(function () {
        Route
            ::nome('remover')
            ::view('/emailmarketing/remover/{hash}');
    });
