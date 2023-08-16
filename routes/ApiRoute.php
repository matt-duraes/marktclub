<?php

use Route\Route;
use App\Middlewares\Api\TokenMiddleware;
use App\Middlewares\Api\MarktClubMiddleware;
use App\Middlewares\Api\TokenProvMiddleware;

Route
    ::nome('samsung')
    ::controller(App\Controllers\Api\SamsungController::class)
    ::grupo(function () {
        Route
            ::nome('validar')
            ::request(['code'], 'get')
            ::post('/_v/private/user/validate');
    });

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

Route
    ::nome('downloadSistema')
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

Route
    ::nome('publicacao_noticia')
    ::controller(App\Controllers\Api\PublicacaoNoticiaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['publicacao_noticia:listar'])
            ::request([
                'pagina', '!quantidade', '!ordem', '!pesquisa',
                '!data_publicacao_de', '!data_publicacao_ate', '!status'
            ], 'json')
            ::get('/publicacao-noticia');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['publicacao_noticia:buscar'])
            ::get('/publicacao-noticia/{id}');
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['publicacao_noticia:salvar'])
            ::request([
                'titulo_grande', 'titulo_pequeno', 'subtitulo', 'texto_grande', 'texto_pequeno',
                'imagem_grande', 'imagem_pequena', '!imagem_galeria', 'imagem_social', '!arquivo',
                'fonte_noticia', 'fonte_link', 'autor_noticia', 'data_publicacao_inicio',
                'data_publicacao_final', 'data_publicacao_atualizacao', 'permissao_restrita',
                'permissao_site', 'permissao_banner', 'status'
            ])
            ::post('/publicacao-noticia');
        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['publicacao_noticia:atualizar'])
            ::request([
                '!titulo_grande', '!titulo_pequeno', '!subtitulo', '!texto_grande', '!texto_pequeno',
                '!imagem_grande', '!imagem_pequena', '!imagem_galeria', '!imagem_social', '!arquivo',
                '!fonte_noticia', '!fonte_link', '!autor_noticia', '!data_publicacao_inicio',
                '!data_publicacao_final', '!data_publicacao_atualizacao', '!permissao_restrita',
                '!permissao_site', '!permissao_banner', '!status'
            ])
            ::put('/publicacao-noticia/{id}');
        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['publicacao_noticia:deletar'])
            ::delete('/publicacao-noticia/{id}');
    });

Route
    ::nome('usuario_cliente_download')
    ::controller(App\Controllers\Api\UsuarioClienteController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('download')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:download'])
            ::request([
                'campo', 'usuario', '!pesquisa', '!pagamento', '!nome', '!email', '!cpf', '!data_upload',
                '!data_criacao_de', '!data_criacao_ate', '!matricula', '!status', '!ordem', '!dependente',
                '!empresa', '!trabalho_empresa', '!trabalho_cargo', '!tipo', '!endereco_estado', '!federacao'
            ])
            ::post('/usuario-cliente/download');
    });

Route
    ::nome('usuario_cliente')
    ::controller(App\Controllers\Api\UsuarioClienteController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::criptografia(App\Classes\UsuarioCliente\Helper::CRIPTOGRAFAR)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:listar'])
            ::request([
                'pagina', '!quantidade', '!pesquisa', '!pagamento', '!nome', '!email', '!cpf', '!data_upload',
                '!data_criacao_de', '!data_criacao_ate', '!matricula', '!status', '!lead', '!ordem',
                '!origem', '!dependente', '!empresa', '!trabalho_empresa', '!trabalho_cargo', '!tipo',
                '!endereco_estado', '!federacao'
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
                '!trabalho_data_inicio', '!grupo', '!empresa', '!subempresa', '!federacao'
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
                '!trabalho_data_inicio', '!grupo', '!federacao'
            ])
            ::put('/usuario-cliente/{id}');
        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:deletar'])
            ::delete('/usuario-cliente/{id}');
        Route
            ::nome('apple')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_cliente:apple'])
            ::post('/usuario-cliente/apple');
    });

Route
    ::nome('usuario_grupo')
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
            ::request(['!titulo', '!empresa'], 'json')
            ::get('/usuario-grupo/select');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:buscar'])
            ::get('/usuario-grupo/{id}');
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:salvar'])
            ::request(['indice', 'titulo', 'status'])
            ::post('/usuario-grupo');
        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:atualizar'])
            ::request(['indice', 'titulo', 'status'])
            ::put('/usuario-grupo/{id}');
        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_grupo:deletar'])
            ::delete('/usuario-grupo/{id}');
    });

Route
    ::nome('usuario_dependente')
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
            ::request(['nome', 'cpf', 'email', 'usuario'])
            ::post('/usuario-dependente');
        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_dependente:deletar'])
            ::delete('/usuario-dependente/{id}');
    });

Route
    ::nome('usuario_indicacao')
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
            ::request([
                'pagina', '!pesquisa', '!nome', '!email', '!cpf', '!status', '!origem', '!ordem'
            ], 'json')
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
            ::request([
                'pagina', '!quantidade', '!pesquisa', '!nome', '!email', '!cpf',
                '!status', '!ordem', '!empresa'
            ], 'json')
            ::get('/usuario-equipe');
        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:listar'])
            ::request(['!titulo'], 'json')
            ::get('/usuario-equipe/select');
        Route
            ::nome('perfil')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:listar'])
            ::get('/usuario-equipe/perfil');
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
                '!senha', '!status', '!primeiro_acesso', '!mudar_senha', '!empresa'
            ])
            ::post('/usuario-equipe');
        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:atualizar'])
            ::request([
                '!nome', '!cpf', '!genero', '!data_nascimento', '!email_trabalho',
                '!email_pessoal', '!telefone_trabalho', '!telefone_pessoal', '!permissao',
                '!senha', '!status', '!primeiro_acesso', '!mudar_senha', '!imagem_facebook',
                '!imagem_google', '!id_facebook', '!id_google', '!perfil'
            ])
            ::put('/usuario-equipe/{id}');
        Route
            ::nome('empresa')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:atualizar'])
            ::request(['empresa'])
            ::put('/usuario-equipe/empresa');
        Route
            ::nome('imagem')
            ::middleware(TokenMiddleware::class, 'scope', ['usuario_equipe:atualizar'])
            ::request(['id'])
            ::request(['imagem'], 'files')
            ::post('/usuario-equipe/imagem');
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

Route
    ::nome('tabela')
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

Route
    ::nome('relatorio')
    ::controller(App\Controllers\Api\RelatorioController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('lojaVenda')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_loja_venda:listar'])
            ::request(['de', 'ate', '!empresa'], 'json')
            ::get('/relatorio/loja-venda');
        Route
            ::nome('dadoUsuario')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_usuario:listar'])
            ::request(['!empresa'], 'json')
            ::get('/relatorio/dado-usuario');
        Route
            ::nome('acessoDia')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_acesso:listar'])
            ::request(['de', 'ate', '!empresa'], 'json')
            ::get('/relatorio/acesso-dia');
        Route
            ::nome('usuarioMaisAcesso')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_acesso:listar'])
            ::request(['de', 'ate', '!empresa'], 'json')
            ::get('/relatorio/usuario-mais-acesso');
        Route
            ::nome('paginaMaisAcessada')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_acesso:listar'])
            ::request(['de', 'ate', '!empresa'], 'json')
            ::get('/relatorio/pagina-mais-acessada');

        Route
            ::nome('lojaMaisAcessada')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_acesso:listar'])
            ::request(['de', 'ate', '!estabelecimento', '!empresa'], 'json')
            ::get('/relatorio/loja-mais-acessada');

        Route
            ::nome('dispositivo')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_acesso:listar'])
            ::request(['de', 'ate', '!empresa'], 'json')
            ::get('/relatorio/dispositivo');

        Route
            ::nome('os')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_acesso:listar'])
            ::request(['de', 'ate', '!empresa'], 'json')
            ::get('/relatorio/os');
        Route
            ::nome('navegador')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_acesso:listar'])
            ::request(['de', 'ate', '!empresa'], 'json')
            ::get('/relatorio/navegador');

        Route
            ::nome('analytics')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_analytics:listar'])
            ::request(['!pagina', '!quantidade', '!usuario', '!de', '!ate'], 'json')
            ::request(['!de', '!ate'], 'get')
            ::get('/relatorio/analytics');
        Route
            ::nome('analyticsDownload')
            ::middleware(TokenMiddleware::class, 'scope', ['relatorio_analytics:download'])
            ::post('/relatorio/analytics-download');
    });

Route
    ::nome('login')
    ::controller(App\Controllers\Api\LoginController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('loginClube')
            ::middleware(TokenMiddleware::class, 'scope', ['login:clube'])
            ::criptografia(['login', 'senha'])
            ::request(['login', 'senha', 'scope', 'redirect_uri', 'state'])
            ::post('/login/clube');
        Route
            ::nome('loginPainel')
            ::middleware(TokenMiddleware::class, 'scope', ['login:painel'])
            ::criptografia(['login', 'senha', 'google', 'facebook'])
            ::request([
                '!login', '!senha', '!facebook', '!google', 'scope',
                'audience', 'redirect_uri', 'state'
            ])
            ::post('/login/painel');
        Route
            ::nome('loginApi')
            ::middleware(TokenMiddleware::class, 'scope', ['login:api'])
            ::criptografia(App\Classes\UsuarioCliente\Helper::CRIPTOGRAFAR)
            ::request([
                'nome', 'cpf', '!matricula', '!siape', '!genero', '!data_nascimento', '!email_trabalho',
                '!email_pessoal', '!telefone_trabalho', '!telefone_pessoal', '!estado_civil',
                '!endereco_estado', '!endereco_cidade', '!federacao', '!salavip', '!grupo',
                '!crm_numero', '!crm_estado', '!termo_lgpd'
            ])
            ::post('/login/api');
        Route
            ::nome('loginDigio')
            ::middleware(TokenMiddleware::class, 'scope', ['login:digio'])
            ::request(['usuario', '!clube'])
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
    ::nome('loginFenae')
    ::controller(App\Controllers\Api\FenaeLoginController::class)
    ::grupo(function () {
        Route
            ::nome('paginaLogin')
            ::view('/login/fenae');
    });

Route
    ::nome('termoLgpd')
    ::controller(App\Controllers\Api\TermoLgpdController::class)
    ::grupo(function () {
        Route
            ::nome('assinar')
            ::view('/termo-lgpd/assinar/{hash}');
        Route
            ::nome('salvar')
            ::request(['hash', 'termo'])
            ::post('/termo-lgpd');
    });

Route
    ::nome('turismo')
    ::controller(App\Controllers\Api\TurismoController::class)
    ::grupo(function () {
        Route
            ::nome('token')
            ::request(['usuario', 'ip', 'memoria', 'user_agent'])
            ::post('/turismo/token');
        Route
            ::nome('validarUsuario')
            ::get('/turismo/validar-usuario/{usuario}');
        Route
            ::nome('redirecionar')
            ::view('/turismo/redirecionar/{usuario}');
        Route
            ::nome('abrir')
            ::view('/turismo/abrir/{usuario}/{memoria}');
    });
Route
    ::nome('pagina')
    ::middleware(TokenProvMiddleware::class, 'token')
    ::controller(App\Controllers\Api\PaginaController::class)
    ::grupo(function () {
        Route
            ::nome('turismo')
            ::get('/pagina/turismo');
        Route
            ::nome('cinema')
            ::get('/pagina/cinema');
    });

Route
    ::nome('mensageria')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\MensageriaController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::request(['payload', 'tipo'])
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
            ::nome('uploadGrupo')
            ::get('/admin/upload-grupo');
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
            ::request([
                'client_id', '!secret_id', '!audience',
                'grant_type', 'scope', '!refresh_token'
            ])
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
    ::nome('parceiro_cupom')
    ::controller(App\Controllers\Api\ParceiroCupomController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro-cupom:listar'])
            ::request(['!pesquisa'], 'json')
            ::get('/parceiro-cupom');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro-cupom:buscar'])
            ::get('/parceiro-cupom/{id}');
    });

Route
    ::nome('parceiro_cashback')
    ::controller(App\Controllers\Api\ParceiroCashbackController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_cashback:listar'])
            ::request([
                'pagina', '!quantidade', '!ordem', '!empresa', '!status'
            ], 'json')
            ::get('/parceiro-cashback');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_cashback:buscar'])
            ::get('/parceiro-cashback/{id}');
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_cashback:salvar'])
            ::request([
                'titulo', 'texto_descricao', 'texto_restricao', 'texto_outro',
                'comissao_minima', 'comissao_maxima', 'status', 'empresa',
                'link_site', 'imagem'
            ])
            ::post('/parceiro-cashback');
        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_cashback:atualizar'])
            ::request([
                '!titulo', '!texto_descricao', '!texto_restricao', '!texto_outro',
                '!comissao_minima', '!comissao_maxima', '!status', '!empresa',
                '!link_site', '!imagem'
            ])
            ::put('/parceiro-cashback/{id}');
        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_cashback:deletar'])
            ::delete('/parceiro-cashback/{id}');
    });

Route
    ::nome('parceiro_relatorio')
    ::controller(App\Controllers\Api\ParceiroRelatorioController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_relatorio:listar'])
            ::request([
                'pagina', '!ordem', '!data_relatorio_de', '!data_relatorio_ate'
            ], 'json')
            ::get('/parceiro-relatorio');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_relatorio:buscar'])
            ::get('/parceiro-relatorio/{id}');
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_relatorio:salvar'])
            ::request([
                'empresa', 'parceiro', 'numero_transacao', 'valor_venda', 'data_relatorio'
            ])
            ::post('/parceiro-relatorio');
        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_relatorio:atualizar'])
            ::request([
                '!empresa', '!parceiro', '!numero_transacao', '!valor_venda', '!data_relatorio'
            ])
            ::put('/parceiro-relatorio/{id}');
        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_relatorio:deletar'])
            ::delete('/parceiro-relatorio/{id}');
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
    ::nome('parceiro_loja')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\ParceiroLojaController::class)
    ::grupo(function () {
        Route
            ::nome('select')
            ::request(['!tipo', '!titulo'], 'json')
            ::get('/parceiro-loja/select');
        Route
            ::nome('listar')
            ::request([
                'pagina', '!quantidade', '!categoria', '!subcategoria', '!estabelecimento',
                '!pesquisa', '!tipo', '!status', '!ordem', '!favorito', '!mais_acessado',
                '!latitude', '!longitude'
            ], 'json')
            ::get('/parceiro-loja');
        Route
            ::nome('buscar')
            ::get('/parceiro-loja/{id}');
    });
Route
    ::nome('parceiro_easylive')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\ParceiroEasyliveController::class)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_easylive:listar'])
            ::request(['pagina', '!tipo', '!ordem', '!status'], 'json')
            ::get('/parceiro-easylive');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_easylive:buscar'])
            ::get('/parceiro-easylive/{id}');
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_easylive:salvar'])
            ::request(['titulo', 'tipo', 'imagem', 'data_validade', 'empresa', 'status'])
            ::post('/parceiro-easylive');
        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_easylive:atualizar'])
            ::request(['!titulo', '!tipo', '!imagem', '!data_validade', '!empresa', '!status'])
            ::put('/parceiro-easylive/{id}');
        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_easylive:deletar'])
            ::delete('/parceiro-easylive/{id}');
    });

Route
    ::nome('parceiro_favorito')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\ParceiroFavoritoController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_favorito:salvar'])
            ::request(['parceiro'])
            ::post('/parceiro-favorito');
        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_favorito:deletar'])
            ::delete('/parceiro-favorito/{id}');
    });

Route
    ::nome('parceiro_subcategoria')
    ::middleware(TokenMiddleware::class, 'token')
    ::controller(App\Controllers\Api\ParceiroSubcategoriaController::class)
    ::grupo(function () {
        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['parceiro_subcategoria:listar'])
            ::request(['!titulo', '!categoria'], 'json')
            ::get('/parceiro-subcategoria/select');
    });

Route
    ::nome('solicitacao_premium')
    ::controller(App\Controllers\Api\SolicitacaoPremiumController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_premium:listar'])
            ::request([
                'pagina', '!data_de', '!data_ate', '!empresa'
            ], 'json')
            ::get('/solicitacao-premium');

        Route
            ::nome('download')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_premium:download'])
            ::request([
                'campo', 'usuario', '!data_de', '!data_ate', '!empresa'
            ])
            ::post('/solicitacao-premium/download');
    });

Route
    ::nome('solicitacao_voucher')
    ::controller(App\Controllers\Api\SolicitacaoVoucherController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_voucher:salvar'])
            ::request([
                'id', '!usuario', '!tipo'
            ])
            ::post('/solicitacao-voucher');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_voucher:buscar'])
            ::get('/solicitacao-voucher/{id}');
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_voucher:listar'])
            ::request([
                'pagina', '!ordem', '!empresa', '!status', '!data_criacao_de', '!data_criacao_ate',
                '!data_validacao_de', '!data_validacao_ate', '!tipo', '!tipo_usuario'
            ], 'json')
            ::get('/solicitacao-voucher');
        Route
            ::nome('download')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_voucher:download'])
            ::request([
                'campo', 'usuario', '!empresa', '!status', '!data_criacao_de', '!data_criacao_ate',
                '!data_validacao_de', '!data_validacao_ate', '!tipo',
            ])
            ::post('/solicitacao-voucher/download');
    });

Route
    ::nome('solicitacao_salavip')
    ::controller(App\Controllers\Api\SolicitacaoSalavipController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_salavip:listar'])
            ::request([
                'pagina', '!ordem', '!empresa', '!data_de', '!data_ate'
            ], 'json')
            ::get('/solicitacao-salavip');
        Route
            ::nome('download')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_salavip:download'])
            ::request([
                'campo', 'usuario', '!ordem', '!empresa', '!data_de', '!data_ate'
            ])
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
            ::request([
                'pagina', '!quantidade', '!ordem', '!cpf', '!status'
            ], 'json')
            ::get('/ponto-cvs');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['ponto_cvs:buscar'])
            ::get('/ponto-cvs/{id}');
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['ponto_cvs:salvar'])
            ::request([
                'ponto_solicitado', 'cpf', 'email', '!nome'
            ])
            ::post('/ponto-cvs');
        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['ponto_cvs:atualizar'])
            ::request([
                '!voucher', '!mensagem', 'status'
            ])
            ::put('/ponto-cvs/{id}');
    });

Route
    ::nome('api_app')
    ::controller(App\Controllers\Api\ApiAppController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::middleware(MarktClubMiddleware::class, 'validar')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['app_api:listar'])
            ::request([
                'pagina', '!pesquisa', '!nome', '!id_admin_empresa', '!status', '!ordem'
            ], 'json')
            ::get('/api-app');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['app_api:buscar'])
            ::get('/api-app/{id}');
    });

Route
    ::nome('api_app')
    ::controller(App\Controllers\Api\ApiUsuarioController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::middleware(MarktClubMiddleware::class, 'validar')
    ::grupo(function () {
        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['app_usuario:listar'])
            ::get('/api-usuario/select');
    });

Route
    ::nome('comercial_empresa_select')
    ::controller(App\Controllers\Api\ComercialEmpresaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::middleware(MarktClubMiddleware::class, 'validar')
    ::grupo(function () {
        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_empresa:listar'])
            ::request(['!titulo'], 'json')
            ::get('/comercial-empresa/select');
    });

Route
    ::nome('comercial_subempresa_select')
    ::controller(App\Controllers\Api\ComercialSubempresaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::middleware(MarktClubMiddleware::class, 'validar')
    ::grupo(function () {
        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_subempresa:listar'])
            ::request(['!titulo', '!empresa'], 'json')
            ::get('/comercial-subempresa/select');
    });

Route
    ::nome('comercial_empresa')
    ::controller(App\Controllers\Api\ComercialEmpresaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::middleware(MarktClubMiddleware::class, 'validar')
    ::criptografia(App\Classes\ComercialEmpresa\Helper::CRIPTOGRAFAR)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_empresa:listar'])
            ::request([
                'pagina', '!pesquisa', '!titulo', '!cnpj', '!usuario',
                '!prospeccao_status', '!status', '!quantidade'
            ], 'json')
            ::get('/comercial-empresa');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_empresa:buscar'])
            ::get('/comercial-empresa/{id}');
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_empresa:salvar'])
            ::request([
                '!titulo', '!finalidade_principal', '!finalidade_secundaria', '!nome_fantasia', '!razao_social',
                '!site', '!responsavel_nome', '!responsavel_email', '!responsavel_telefone', '!responsavel_cpf',
                '!equipe', '!tipo_pagamento', '!contrato_valor', '!renda_media', '!produto_clube',
                '!produto_ios', '!produto_android', '!produto_site', '!produto_webview', '!produto_api', '!cnpj',
                '!estado_principal', '!status', '!data_eleicao', '!email_dia', '!whatsapp_dia', '!rede_social_dia',
                '!contrato_prazo', '!contrato_renovacao', '!tipo_site', '!cadastro_usuario', '!comunicacao_email',
                '!comunicacao_whatsapp', '!comunicacao_rede_social', '!email_disparo', '!prospeccao_status',
                '!observacao_ti', '!observacao_comunicacao', '!observacao_financeiro', '!restricao_lista',
                '!contrato_data', '!contrato_dia_pagamento', '!contrato_dia_fechamento', '!contrato_valor_minimo',
                '!contrato_usuario_minimo', '!cobrar_aposentado'
            ])
            ::post('/comercial-empresa');
        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_empresa:atualizar'])
            ::request([
                '!titulo', '!finalidade_principal', '!finalidade_secundaria', '!nome_fantasia', '!razao_social',
                '!site', '!responsavel_nome', '!responsavel_email', '!responsavel_telefone', '!responsavel_cpf',
                '!equipe', '!tipo_pagamento', '!contrato_valor', '!renda_media', '!produto_clube',
                '!produto_ios', '!produto_android', '!produto_site', '!produto_webview', '!produto_api', '!cnpj',
                '!estado_principal', '!status', '!data_eleicao', '!email_dia', '!whatsapp_dia', '!rede_social_dia',
                '!contrato_prazo', '!contrato_renovacao', '!tipo_site', '!cadastro_usuario', '!comunicacao_email',
                '!comunicacao_whatsapp', '!comunicacao_rede_social', '!email_disparo', '!prospeccao_status',
                '!observacao_ti', '!observacao_comunicacao', '!observacao_financeiro', '!restricao_lista',
                '!contrato_data', '!contrato_dia_pagamento', '!contrato_dia_fechamento', '!contrato_valor_minimo',
                '!contrato_usuario_minimo', '!cobrar_aposentado'
            ])
            ::put('/comercial-empresa/{id}');
    });

Route
    ::nome('comercial_restricao')
    ::controller(App\Controllers\Api\ComercialRestricaoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('select')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_restricao:listar'])
            ::request(['!titulo'], 'json')
            ::get('/comercial-restricao/select');
    });

Route
    ::nome('comercial_regra')
    ::controller(App\Controllers\Api\ComercialRegraController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_regra:listar'])
            ::request([
                'pagina', '!titulo', '!empresa'
            ], 'json')
            ::get('/comercial-regra');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_regra:buscar'])
            ::get('/comercial-regra/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_regra:salvar'])
            ::request([
                'titulo', 'texto', 'empresa'
            ])
            ::post('/comercial-regra');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_regra:atualizar'])
            ::request([
                'titulo', 'texto', 'empresa'
            ])
            ::put('/comercial-regra/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['comercial_regra:deletar'])
            ::delete('/comercial-regra/{id}');
    });

Route
    ::nome('demandaDado')
    ::controller(App\Controllers\Api\DemandaDadoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_dado:listar'])
            ::request([
                'status', 'area', 'ordem'
            ], 'json')
            ::get('/demanda-dado');
        Route
            ::nome('buscar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_dado:buscar'])
            ::get('/demanda-dado/{id}');
        Route
            ::nome('salvar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_dado:salvar'])
            ::request([
                'empresa', 'titulo', 'tipo', 'area'
            ])
            ::post('/demanda-dado');
        Route
            ::nome('atualizar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_dado:atualizar'])
            ::request([
                '!titulo', '!arquivo', '!id_admin_empresa', '!id_usuario_equipe',
                '!data_entrega', '!com_prazo', '!status', '!ordem'
            ])
            ::put('/demanda-dado/{id}');
        Route
            ::nome('cancelar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_dado:cancelar'])
            ::request(['motivo'])
            ::post('/demanda-dado/cancelar/{id}');
    });

Route
    ::nome('demandaTarefa')
    ::controller(App\Controllers\Api\DemandaTarefaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_tarefa:salvar'])
            ::request([
                'demanda', 'titulo', 'texto', 'tipo', '!minuto_producao_estimada', '!equipe'
            ])
            ::post('/demanda-tarefa');
        Route
            ::nome('buscar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_tarefa:buscar'])
            ::get('/demanda-tarefa/{id}');
        Route
            ::nome('atualizar')
            // ::middleware(TokenMiddleware::class, 'scope', ['demanda_tarefa:atualizar'])
            ::request([
                'titulo', 'texto', 'tipo', 'minuto_producao_estimada'
            ])
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

Route
    ::nome('demandaTrabalho')
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
            ::request(['!data'])
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

Route
    ::nome('carteirinha')
    ::controller(App\Controllers\Api\CarteirinhaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['carteirinha:buscar'])
            ::get('/carteirinha/{id}');
    });

Route
    ::nome('solicitacao_alfa')
    ::controller(App\Controllers\Api\SolicitacaoAlfaController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::criptografia(App\Classes\SolicitacaoAlfa\Helper::CRIPTOGRAFAR)
    ::grupo(function () {
        Route
            ::nome('solicitacao')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_alfa:solicitacao'])
            ::request([
                '!valor_emprestimo', '!prazo', '!valor_parcela_atual', '!quantidade_parcelas_restantes',
                '!taxa', 'nome', 'documento_cpf', 'email', 'telefone_celular', '!telefone_fixo', 'orgao',
                'observacao', '!data_simulacao', '!status', '!tipo'
            ])
            ::post('/alfa/solicitacao');
    });

Route
    ::nome('popup')
    ::controller(App\Controllers\Api\PopupController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['popup:buscar'])
            ::get('/popup/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['popup:salvar'])
            ::request([
                'titulo', 'texto', '!subtitulo', '!formulario',
                '!imagem', '!data_expiracao', '!status'
            ])
            ::post('/popup');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['popup:atualizar'])
            ::request([
                '!titulo', '!texto', '!subtitulo', '!formulario',
                '!imagem', '!data_expiracao', '!status'
            ])
            ::put('/popup/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['popup:deletar'])
            ::delete('/popup/{id}');
    });

Route
    ::nome('solicitacao_declaracao')
    ::controller(App\Controllers\Api\SolicitacaoDeclaracaoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_declaracao:buscar'])
            ::get('/solicitacao-declaracao/{id}');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_declaracao:listar'])
            ::request([
                'pagina', '!ordem', '!tipo', '!status', '!data_criacao_de', '!data_criacao_ate'
            ], 'json')
            ::get('/solicitacao-declaracao');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_declaracao:salvar'])
            ::request([
                'url', 'tipo'
            ])
            ::post('/solicitacao-declaracao');
    });

Route
    ::nome('saude_simulacao')
    ::controller(App\Controllers\Api\SaudeSimulacaoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['saude_simulacao:buscar'])
            ::get('/saude/simulacao/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['saude_simulacao:salvar'])
            ::request([
                '!titular', '!dependentes', '!operadora', '!acomodacao', '!regiao', '!plano',
            ])
            ::post('/saude/simulacao');
    });

Route
    ::nome('saude_contratacao')
    ::controller(App\Controllers\Api\SaudeContratacaoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['saude_contratacao:salvar'])
            ::request([
                'id_simulacao', 'documento_cpf', 'documento_rg', 'orgao_expedidor', 'nome',
                'data_nascimento', 'estado_civil', 'naturalidade', 'genero', 'peso', 'altura',
                'nome_mae', 'responsavel_cpf', 'responsavel_rg', 'responsavel_nome', 'responsavel_orgao_expedidor',
                'email_pessoal', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
                'telefone_comercial_ramal', 'endereco_logradouro', 'endereco_cep', 'endereco_estado',
                'endereco_cidade', 'endereco_bairro', 'endereco_numero', 'endereco_complemento'
            ])
            ::post('/saude/contratacao');
    });

Route
    ::nome('solicitacao_credito')
    ::controller(App\Controllers\Api\SolicitacaoCreditoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('simular')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_credito:simular'])
            ::request([
                'operadora', 'tipo', 'valor', 'parcelas'
            ])
            ::get('/solicitar-credito');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_credito:buscar'])
            ::get('/solicitacao-credito/{id}');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_credito:listar'])
            ::request([
                'pagina', '!tipo', '!operadora', '!status', '!data_criacao_de', '!data_criacao_ate'
            ], 'json')
            ::get('/solicitacao-credito');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_credito:salvar'])
            ::request([
                'operadora', 'tipo', 'valor', 'parcelas', '!valor_parcelas', '!status'
            ])
            ::post('/solicitacao-credito');
    });

Route
    ::nome('enquete_satisfacao')
    ::controller(App\Controllers\Api\EnqueteSatisfacaoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['enquete_satisfacao:buscar'])
            ::get('/enquete-satisfacao/{id}');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['solicitacao_credito:listar'])
            ::request([
                'pagina', '!status', '!data_criacao_de', '!data_criacao_ate'
            ], 'json')
            ::get('/enquete-satisfacao');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['enquete_satisfacao:salvar'])
            ::request([
                'navegar', 'procura', 'suporte', 'atendimento', 'sistemas', '!comentario',
            ])
            ::post('/enquete/satisfacao');
    });

Route
    ::nome('publicidade')
    ::controller(App\Controllers\Api\PublicidadeController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['publicidade:buscar'])
            ::get('/publicidade/{id}');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['publicidade:listar'])
            ::request([
                'pagina', '!quantidade', '!tipo', '!status', '!data_criacao_de', '!data_criacao_ate'
            ], 'json')
            ::get('/publicidade');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['publicidade:salvar'])
            ::request([
                'titulo', 'imagem', 'target', 'link', 'tipo'
            ])
            ::post('/publicidade');
    });

Route
    ::nome('contato')
    ::controller(App\Controllers\Api\ContatoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['contato:salvar'])
            ::request([
                'nome', 'email', 'telefone', 'mensagem', 'url', '!descoberta_site'
            ])
            ::post('/contato');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['contato:listar'])
            ::request([
                'pagina', '!ordem', '!status', '!data_criacao_de', '!data_criacao_ate'
            ], 'json')
            ::get('/contato');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['contato:buscar'])
            ::get('/contato/{id}');
    });

Route
    ::nome('indicacao')
    ::controller(App\Controllers\Api\IndicacaoParceiroController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['mensagem_indicacao_parceiro:salvar'])
            ::request([
                'parceiro', 'telefone', 'email', 'mensagem', 'tipo'
            ])
            ::post('/parceiro/indicacao');

        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['mensagem_indicacao_parceiro:listar'])
            ::request([
                'pagina', '!ordem', '!status', '!data_criacao_de', '!data_criacao_ate'
            ], 'json')
            ::get('/parceiro-indicacao');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['mensagem_indicacao_parceiro:buscar'])
            ::get('/parceiro-indicacao/{id}');
    });

Route
    ::nome('automovel_indicacao')
    ::controller(App\Controllers\Api\IndicacaoAutomovelController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['mensagem_indicacao_automovel:salvar'])
            ::request([
                'produto', 'modelo', 'versao', 'cor', 'cidade', 'mensagem'
            ])
            ::post('/automovel/indicacao');
    });

Route
    ::nome('automovel_modelo')
    ::controller(App\Controllers\Api\AutomovelModeloController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_modelo:listar'])
            ::request(['pagina', '!quantidade', '!parceiro', '!ordem', '!status'], 'json')
            ::get('/automovel-modelo');

        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_modelo:buscar'])
            ::get('/automovel-modelo/{id}');

        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_modelo:salvar'])
            ::request(['titulo', 'parceiro', 'imagem', 'status'])
            ::post('/automovel-modelo');

        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_modelo:atualizar'])
            ::request(['!titulo', '!parceiro', '!imagem', '!status'])
            ::put('/automovel-modelo/{id}');

        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_modelo:deletar'])
            ::delete('/automovel-modelo/{id}');
    });

Route
    ::nome('automovel_versao')
    ::controller(App\Controllers\Api\AutomovelVersaoController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_versao:listar'])
            ::request(['pagina', '!quantidade', '!modelo', '!ordem', '!status'], 'json')
            ::get('/automovel-versao');
        Route
            ::nome('buscar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_versao:buscar'])
            ::get('/automovel-versao/{id}');
        Route
            ::nome('salvar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_versao:salvar'])
            ::request(['modelo', 'titulo', 'cor', 'valor_de', 'valor_por', 'status'])
            ::post('/automovel-versao');
        Route
            ::nome('atualizar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_versao:atualizar'])
            ::request(['!titulo', '!cor', '!valor_de', '!valor_por', '!status'])
            ::put('/automovel-versao/{id}');
        Route
            ::nome('deletar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel_versao:deletar'])
            ::delete('/automovel-versao/{id}');
    });

Route
    ::nome('automovel')
    ::controller(App\Controllers\Api\AutomovelController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('listar')
            ::middleware(TokenMiddleware::class, 'scope', ['automovel:listar'])
            ::request(['pagina', '!url', '!quantidade', '!vinculo', '!ordem', '!status'], 'json')
            ::get('/automovel');
    });

Route
    ::nome('silium')
    ::controller(App\Controllers\Api\SiliumController::class)
    ::middleware(TokenMiddleware::class, 'token')
    ::grupo(function () {
        Route
            ::nome('saldo')
            ::middleware(TokenMiddleware::class, 'scope', ['silium:saldo'])
            ::get('/silium/saldo');

        Route
            ::nome('extrato')
            ::middleware(TokenMiddleware::class, 'scope', ['silium:extrato'])
            ::get('/silium/extrato');

        Route
            ::nome('saque')
            ::middleware(TokenMiddleware::class, 'scope', ['silium:saque'])
            ::request([
                'titular', 'documento_cpf', 'banco', 'agencia', 'conta', 'tipo_conta'
            ])
            ::post('/silium/saque');
    });
