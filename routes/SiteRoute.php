<?php

use Route\Route;
use App\Middlewares\Site\AuthMiddleware;

Route
    ::nome('login')
    ::middleware(AuthMiddleware::class, 'deslogado')
    ::controller(App\Controllers\Site\LoginController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/login');
        Route
            ::nome('comoFunciona')
            ::view('/login/como-funciona-escolha');
        Route
            ::nome('comoFuncionaDependente')
            ::view('/login/como-funciona');
        Route
            ::nome('comoFuncionaCFM')
            ::view('/login/como-funciona-cfm');
        Route
            ::nome('comoFuncionaFuncionario')
            ::view('/login/como-funciona-funcionario');
        Route
            ::nome('faq')
            ::view('/login/faq');
        Route
            ::nome('logar')
            ::request(['login', 'senha'])
            ::post('/login');
        Route
            ::nome('abrirModalContato')
            ::view('/contato');
        Route
            ::nome('contato')
            ::request(['hash', 'validacao',  'nome', 'email', 'telefone', 'mensagem'])
            ::post('/contato');
        Route
            ::nome('ativar')
            ::view('/auth/ativar');
        Route
            ::nome('buscarUsuario')
            ::request(['client_id', 'pesquisa', 'tipo', 'captcha'])
            ::post('/auth/buscar-usuario');
        Route
            ::nome('ativar')
            ::request(['dado', 'client_id', 'scope', 'redirect_uri', 'state'])
            ::post('/auth/ativar');
        Route
            ::nome('ativar')
            ::request(['nome_completo', 'email_pessoal', 'documento_cpf', 'sexo', 'titular', 'empresa', 'client_id'])
            ::post('/auth/salvar-dependente');
        Route
            ::nome('logarUsuario')
            ::view('/auth/login');
        Route
            ::nome('enviarCodigo')
            ::request(['captcha', 'login', 'client_id'])
            ::post('/auth/enviar-codigo');
        Route
            ::nome('validarCodigo')
            ::request(['login', 'client_id', 'codigo'])
            ::post('/auth/validar-codigo');
        Route
            ::nome('novaSenha')
            ::request(['login', 'client_id', 'codigo', 'nova_senha', 'repetir_senha'])
            ::post('/auth/nova-senha');

    });

Route
    ::nome('sair')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\LoginController::class)
    ::grupo(function () {
        Route
            ::nome('sair')
            ::view('/sair');
    });
Route
    ::nome('index')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\IndexController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/');
    });
Route
    ::nome('acessoRapido')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\AcessoRapidoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/acesso-rapido');
        Route
            ::nome('sair')
            ::view('/acesso-rapido/sair');
    });
Route
    ::nome('cupom')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\CupomController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/cupom');
        Route
            ::nome('buscar')
            ::request(['!pesquisa'])
            ::view('/cupom/buscar/{!pesquisa}');
        Route
            ::nome('detalhe')
            ::view('/cupom/{url}');
    });
Route
    ::nome('cashback')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\CashbackController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/cashback');
        Route
            ::nome('buscar')
            ::request(['!pesquisa'])
            ::view('/cashback/buscar/{!pesquisa}');
        Route
            ::nome('detalhe')
            ::view('/cashback/{url}');
        Route
            ::nome('extrato')
            ::view('/cashback/extrato');
        Route
            ::nome('abrirResgateCashback')
            ::view('/cashback/resgatar');
    });

Route
    ::nome('turismo')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\TurismoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/turismo');
        Route
            ::nome('aeroporto')
            ::request(['pesquisa'])
            ::get('/turismo/listar-aeroporto');
        Route
            ::nome('hotel')
            ::request(['pesquisa'])
            ::get('/turismo/listar-hotel');
        Route
            ::nome('solicitaVoo')
            ::request(['origem', 'destino', 'data_ida', 'data_volta', 'adulto', '!crianca', '!bebe', 'tipo'])
            ::post('/turismo/solicitar-voo');
        Route
            ::nome('solicitaHotel')
            ::request(['cidade', 'checkin', 'checkout', 'quantidade_quarto', 'adulto', '!crianca'])
            ::post('/turismo/solicitar-hotel');
    });
Route
    ::nome('cinema')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\CinemaController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/cinema');
    });
Route
    ::nome('loja')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\LojaController::class)
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::request(['!estado', '!categoria', '!subcategoria', '!estabelecimento', '!pesquisa', '!ordem'])
            ::get('/convenios/buscar');
        Route
            ::nome('index')
            ::view('/convenios');
        Route
            ::nome('detalhe')
            ::view('/convenios/{url}');
        Route
            ::nome('voucher')
            ::view('/convenios/voucher/{url}');
        Route
            ::nome('proxima')
            ::view('/convenios/mapa');
        Route
            ::nome('subcategoria')
            ::request(['categoria'])
            ::post('/convenios/subcategoria');
        Route
            ::nome('favorito')
            ::request(['id'])
            ::post('/convenios/favorito');
        Route
            ::nome('favorito')
            ::delete('/convenios/favorito/{id}');
    });

Route
    ::nome('voucher')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\VoucherController::class)
    ::grupo(function () {
        Route
            ::nome('voucher')
            ::view('/voucher/{url}');
    });

Route
    ::nome('salavip')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\SalaVipController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/salavip');
    });
Route
    ::nome('odontologico')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\OdontologicoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/plano-odontologico');
    });
Route
    ::nome('planosaude')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\PlanoSaudeController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/saude');
        Route
            ::nome('detalhe')
            ::view('/saude/detalhe/{nome-do-plano}');
        Route
            ::nome('unimedVitoria')
            ::view('/saude/unimed-vitoria');
        Route
            ::nome('unimedflorianopolis')
            ::view('/saude/unimed-florianopolis');
        Route
            ::nome('tabela')
            ::request(['id'])
            ::view('/saude/abrirtabela');
        Route
            ::nome('centralnacional')
            ::view('/saude/central-nacional-unimed');
        Route
            ::nome('amil')
            ::view('/saude/amil');
        Route
            ::nome('precoAmil')
            ::request(['id', '!local'])
            ::view('/saude/abrir-tabela-preco');
        Route
            ::nome('unimedSeguro')
            ::view('/saude/unimed-seguro');
        Route
            ::nome('simulacao')
            ::view('/saude/simulacao/{url}');
        Route
            ::nome('contratacao')
            ::view('/saude/contratacao/{simulacao}');
    });
Route
    ::nome('farmacia')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\FarmaciaController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/farmacia');
        Route
            ::nome('detalhe')
            ::view('/farmacia/{url}');
        Route
            ::nome('carteirinha')
            ::view('/tem-mais-saude/carteirinha');
    });
Route
    ::nome('sicoob')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\SicoobController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/credito/sicoob');
        Route
            ::nome('consignado')
            ::view('/credito/sicoob-consignado');
        Route
            ::nome('creditoPessoal')
            ::view('/credito/sicoob-credito-pessoal');
        Route
            ::nome('veiculoZero')
            ::view('/credito/sicoob-veiculo-zero');
        Route
            ::nome('veiculoSeminovo')
            ::view('/credito/sicoob-veiculo-seminovo');
        Route
            ::nome('abrirModalRegulamento')
            ::view('/sicoob-regulamento/{url}');
    });

Route
    ::nome('solicitacao_credito')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\SolicitacaoCreditoController::class)
    ::grupo(function () {
        Route
            ::nome('simulacao')
            ::request([
                'tipo', 'valor', 'prazo', 'operadora'
            ])
            ::get('/credito/simulacao');
        Route
            ::nome('salvar')
            ::request([
                'tipo', 'valor', 'prazo', 'operadora'
            ])
            ::post('/credito/salvar');
    });

Route
    ::nome('automovel')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\AutomovelController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/automoveis');
        Route
            ::nome('modelo')
            ::view('/automoveis/{url}');
        Route
            ::nome('versao')
            ::view('/automovel/{url}');

        Route
            ::nome('voucher')
            ::view('/automovel-voucher/{url}');
        Route
            ::nome('declaracao')
            ::view('/automovel-declaracao/{url}');
        Route
            ::nome('indicacao')
            ::request([
                'veiculo', 'modelo', 'versao', 'cor', 'cidade', 'mensagem'
            ])
            ::post('/automovel-indicacao');
    });

Route
    ::nome('termo')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\TermoController::class)
    ::grupo(function () {
        Route
            ::nome('termosite')
            ::view('/termo-de-uso-do-site');
        Route
            ::nome('termocashback')
            ::view('/termo-de-uso-do-cashback');
        Route
            ::nome('app')
            ::view('/termo-de-uso-app');
    });

Route
    ::nome('alfa')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\AlfaController::class)
    ::grupo(function () {
        Route
            ::nome('credito')
            ::view('/credito/alfa');
        Route
            ::nome('veiculo')
            ::view('/credito/alfa-veiculo');
        Route
            ::nome('portabilidade')
            ::view('/credito/alfa-portabilidade');
        Route
            ::nome('consignado')
            ::view('/credito/alfa-consignado');
        Route
            ::nome('corretoraAlfa')
            ::view('/corretora-alfa');
        Route
            ::nome('consultoriaAlfa')
            ::view('/consultoria/alfa');
    });

Route
    ::nome('site')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\SiteController::class)
    ::grupo(function () {
        Route
            ::nome('pesquisa')
            ::get('/pesquisa-de-satisfacao');
        Route
            ::nome('pesquisa')
            ::request([
                'navegar', 'procura', 'suporte', 'comentario', 'atendimento', 'sistema'
            ])
            ::post('/pesquisa-de-satisfacao');
        Route
            ::nome('sosmulher')
            ::view('/sos-mulher');
        Route
            ::nome('indiqueAmigo')
            ::view('/indique-um-amigo');
        Route
            ::nome('abrirModalEnquetePopup')
            ::view('/enquete-popup/{id}');
        Route
            ::nome('abrirModalPopupImagem')
            ::view('/enquete-imagem/{id}');
        Route
            ::nome('ajuda')
            ::get('/ajuda');
        Route
            ::nome('indiqueParceiro')
            ::get('/indique-um-parceiro');
    });

Route
    ::nome('promocao')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\PromocaoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/promocao');
    });

Route
    ::nome('perfil')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\PerfilController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/perfil');
        Route
            ::nome('salvaDados')
            ::request([
                'nome', 'data_nascimento', '!genero', 'estado_civil', 'email_pessoal', 'email_trabalho',
                '!telefone_trabalho', 'telefone_pessoal', 'endereco_estado', 'endereco_cep', 'endereco_logradouro',
                'endereco_bairro', 'endereco_numero', 'endereco_complemento', 'endereco_cidade'
            ])
            ::post('/perfil/salvar-dados');
        Route
            ::nome('senha')
            ::view('/perfil/alterar-senha');
        Route
            ::nome('alteraSenha')
            ::request(['senha_atual', 'senha_nova', 'senha_repetir'])
            ::post('/perfil/alterar-senha');
        Route
            ::nome('dependente')
            ::view('/perfil/adicionar-dependente');
        Route
            ::nome('salvaDependente')
            ::request(['nome', 'email', 'cpf'])
            ::post('/perfil/salvar-dependentes');
        Route
            ::nome('deletaDependente')
            ::request(['id'])
            ::post('/perfil/deletar-dependente');
        Route
            ::nome('social')
            ::request(['id', 'token', 'rede', 'code', 'acao'])
            ::post('/perfil/vincular-google');
        Route
            ::nome('carteira')
            ::view('/perfil/carteira');
        Route
            ::nome('buscarCep')
            ::request(['cep'])
            ::post('/perfil/buscar-cep');
    });

Route
    ::nome('preferencia')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\PreferenciaController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/preferencias');
        Route
            ::nome('boasVindas')
            ::view('/preferencias/boas-vindas');
    });

Route
    ::nome('campanha')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\CampanhaController::class)
    ::grupo(function () {
        Route
            ::nome('tematica')
            ::view('/campanha');
        Route
            ::nome('atualizar_cpf')
            ::view('/campanha/atualizar-cpf');
    });

Route
    ::nome('regulamento')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\RegulamentoController::class)
    ::grupo(function () {
        Route
            ::nome('sorteio')
            ::view('/regulamento-sorteio');
    });

Route
    ::nome('indicacao')
    ::controller(App\Controllers\Site\IndicacaoParceiroController::class)
    ::grupo(function () {
        Route
            ::nome('salvar')
            ::request([
                'parceiro', 'telefone', 'email', 'mensagem'
            ])
            ::post('/indicacao/salvar');
    });
