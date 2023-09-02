<?php

use Route\Route;
use App\Middlewares\Site\AuthMiddleware;
use App\Middlewares\Site\ClubeMiddleware;

Route
    ::nome('faqLogin')
    ::middleware(AuthMiddleware::class, 'deslogado')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::controller(App\Controllers\Site\LoginController::class)
    ::grupo(function () {
        Route
            ::nome('faq')
            ::view('/login/faq');
    });

Route
    ::nome('faq')
    ::middleware(AuthMiddleware::class, 'logado')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::controller(App\Controllers\Site\FaqController::class)
    ::grupo(function () {
        Route
            ::nome('favorito')
            ::view('/faq/favorito');
    });

Route
    ::nome('comoFunciona')
    ::middleware(AuthMiddleware::class, 'deslogado')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::controller(App\Controllers\Site\ComoFuncionaController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/login/como-funciona');
        Route
            ::nome('detalhe')
            ::view('/login/como-funciona-detalhe/{url}');
    });
Route
    ::nome('contato')
    ::middleware(AuthMiddleware::class, 'deslogado')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::controller(App\Controllers\Site\ContatoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/login/contato');
        Route
            ::nome('salvar')
            ::request(['hash_validacao_captcha', 'nome', 'email', 'telefone', 'mensagem'])
            ::post('/login/contato');
    });
Route
    ::nome('loginGeral')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::controller(App\Controllers\Site\LoginController::class)
    ::grupo(function() {
        Route
            ::nome('login')
            ::request(['login', 'senha'])
            ::post('/login/login');
    });
Route
    ::nome('login')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'deslogado')
    ::controller(App\Controllers\Site\LoginController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/login');
        Route
            ::nome('login')
            ::view('/login/login');
        Route
            ::nome('buscarConta')
            ::view('/login/buscar-conta');
        Route
            ::nome('buscarConta')
            ::request(['hash_validacao_captcha', 'usuario'])
            ::post('/login/buscar-conta');
        Route
            ::nome('ativar')
            ::view('/login/ativar');
        Route
            ::nome('ativar')
            ::request(['hash_validacao_captcha', 'hash', 'nome', 'email'])
            ::post('/login/ativar');
        Route
            ::nome('app')
            ::view('/login/app');
    });

Route
    ::nome('sair')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\SairController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/sair');
    });
Route
    ::nome('index')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\IndexController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/');
    });
Route
    ::nome('historico')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\HistoricoController::class)
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::post('/historico');
    });
Route
    ::nome('acessoRapido')
    ::middleware(ClubeMiddleware::class, 'buscar')
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
    ::middleware(ClubeMiddleware::class, 'buscar')
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
    ::middleware(ClubeMiddleware::class, 'buscar')
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
    ::middleware(ClubeMiddleware::class, 'buscar')
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
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\CinemaController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/cinema');
    });

Route
    ::nome('loja')
    ::middleware(ClubeMiddleware::class, 'buscar')
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
            ::nome('confirmar')
            ::get('/convenios/confirmar/{url}');
        Route
            ::nome('voucher')
            ::view('/convenios/voucher/{url}');
        Route
            ::nome('chequeBonus')
            ::view('/convenios/cheque-bonus/{id}');
        Route
            ::nome('chequeBonus')
            ::request([
                'tipo_usuario', 'dependente_nome', 'dependente_email', 'dependente_cpf', 'dependente_rg',
                'dependente_grau_parentesco', 'dependente_data_nascimento', 'estado_civil', 'telefone_celular',
                'data_nascimento', 'endereco_cep', 'endereco_logradouro', 'endereco_numero', 'automovel',
                'endereco_complemento', 'endereco_bairro', 'endereco_estado', 'endereco_cidade', 'rg',
                'atualizar', 'data_termo', 'nome', 'email_pessoal'
            ])
            ::post('/convenios/cheque-bonus');
        Route
            ::nome('declaracao')
            ::request(['parceiro', 'modelo', 'versao'])
            ::post('/convenios/declaracao');
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

        Route
            ::nome('indicar')
            ::request([
                'nome', 'telefone', 'email', 'mensagem'
            ])
            ::post('/convenios/indicar');
    });

Route
    ::nome('voucher')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\VoucherController::class)
    ::grupo(function () {
        Route
            ::nome('voucher')
            ::view('/voucher/{url}');
    });

Route
    ::nome('salavip')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\SalaVipController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/salavip');
    });
Route
    ::nome('odontologico')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\OdontologicoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/plano-odontologico');
    });
Route
    ::nome('planosaude')
    ::middleware(ClubeMiddleware::class, 'buscar')
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
            ::view('/saude/central-nacional-unimed-florianopolis');
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
            ::view('/saude/plano-simulacao/{url}');
        Route
            ::nome('realizarSimulacao')
            ::request(['!operadora','!titular','!regiao','!plano','!acomodacao','!dependentes'])
            ::post('/saude/realizar-simulacao');
        Route
            ::nome('contratacao')
            ::view('/saude/simulacao/{simulacao}');
        Route
            ::nome('realizarContratacao')
            ::request([
                'id_saude_simulacao','nome','naturalidade','cpf','data_nascimento',
                'genero','estado_civil','peso','altura','rg','orgao_expedidor',
                'nome_mae','responsavel_nome','responsavel_cpf','responsavel_rg',
                'responsavel_orgao_expedidor','email_pessoal','telefone_celular',
                'telefone_residencial','telefone_comercial','telefone_comercial_ramal',
                'endereco_cep', 'endereco_bairro', 'endereco_logradouro', 'endereco_numero',
                'endereco_complemento', 'endereco_cidade', 'endereco_estado'
            ])
            ::post('/saude/contratacao');

    });
Route
    ::nome('farmacia')
    ::middleware(ClubeMiddleware::class, 'buscar')
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
    ::middleware(ClubeMiddleware::class, 'buscar')
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
            ::nome('regulamento')
            ::view('/sicoob-regulamento/{tipo}');
    });

Route
    ::nome('solicitacao_credito')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\SolicitacaoCreditoController::class)
    ::grupo(function () {
        Route
            ::nome('realizarSimulacao')
            ::request([
                'tipo', 'valor_total', 'parcela', 'operadora'
            ])
            ::post('/credito/simulacao');
        Route
            ::nome('salvar')
            ::request([
                'tipo', 'valor_total', 'parcela', 'operadora'
            ])
            ::post('/credito/salvar');
    });

Route
    ::nome('automovel')
    ::middleware(ClubeMiddleware::class, 'buscar')
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
            ::view('/automovel/{loja}/{url}');
        Route
            ::nome('solicitacao')
            ::request([
                'endereco_estado', 'endereco_cidade', 'montadora', 'modelo', 'versao', 'cor', 'mensagem'
            ])
            ::post('/automovel/solicitacao');
    });

Route
    ::nome('termo')
    ::middleware(ClubeMiddleware::class, 'buscar')
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
    ::nome('sosmulher')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\SosMulherController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/sos-mulher');
    });

Route
    ::nome('endereco')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\EnderecoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::request(['id', 'tipo'])
            ::post('/endereco');
    });

Route
    ::nome('site')
    ::middleware(ClubeMiddleware::class, 'buscar')
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
    ::nome('perfil')
    ::middleware(ClubeMiddleware::class, 'buscar')
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
                'endereco_bairro', 'endereco_numero', 'endereco_complemento', 'endereco_cidade', '!imagem'
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
            ::view('/perfil/dependente');
        Route
            ::nome('salvaDependente')
            ::request(['nome', 'email', 'cpf'])
            ::post('/perfil/dependente-salvar');
        Route
            ::nome('deletaDependente')
            ::request(['id'])
            ::post('/perfil/dependente-deletar');
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
    ::nome('campanha')
    ::middleware(ClubeMiddleware::class, 'buscar')
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
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\RegulamentoController::class)
    ::grupo(function () {
        Route
            ::nome('sorteio')
            ::view('/regulamento-sorteio');
    });
