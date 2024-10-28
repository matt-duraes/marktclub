<?php

use Route\Route;
use App\Middlewares\Site\AuthMiddleware;
use App\Middlewares\Site\ClubeMiddleware;

Route
    ::nome('temp')
    ::controller(App\Controllers\Site\TempController::class)
    ::grupo(function () {
        Route
            ::nome('undefined')
            ::view('/undefined');
    });

Route
    ::nome('thema')
    ::controller(App\Controllers\Site\TemaController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/tema');
        Route
            ::nome('salvar')
            ::request(['tema'])
            ::post('/tema');
    });
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
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::controller(App\Controllers\Site\ContatoController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/login/contato');
        Route
            ::nome('contatoLogin')
            ::request(['nome', 'email', 'telefone', 'mensagem'])
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
        // LOGIN
        Route
            ::nome('index')
            ::view('/login');
        Route
            ::nome('login')
            ::view('/login/login');

        // Youhuul
        Route
            ::nome('youhuul')
            ::view('/login/youhuul');

        // UBER/DIGIO
        Route
            ::nome('digio')
            ::view('/login/digio');
        Route
            ::nome('uber')
            ::view('/login/uber');
        Route
            ::nome('digioApi')
            ::request(['client-id'])
            ::get('/digio-login-api');
        Route
            ::nome('uberApi')
            ::request(['client-id'])
            ::get('/uber-login-api');

        // SENHA
        Route
            ::nome('senha')
            ::view('/login/senha');
        Route
            ::nome('senhaBuscar')
            ::request(['cpf'])
            ::post('/login/senha-buscar');
        Route
            ::nome('senhaValidar')
            ::request(['codigo', 'usuario'])
            ::post('/login/senha-validar');
        Route
            ::nome('senhaAlterar')
            ::request(['cpf', 'senha', 'usuario', 'hash'])
            ::post('/login/senha-alterar');
        // ATIVAR
        Route
            ::nome('ativarBuscar')
            ::view('/login/ativar-buscar');
        Route
            ::nome('ativarBuscar')
            ::request(['busca', 'tipo_usuario'])
            ::post('/login/ativar-buscar');
        Route
            ::nome('ativarSalvar')
            ::request(['hash', 'cpf', '!tipo_usuario'])
            ::view('/login/ativar-salvar');
        Route
            ::nome('ativarSalvar')
            ::request([
                'hash', 'nome', 'cpf', 'genero', 'senha', 'termo', 'data_nascimento', 'estado_civil',
                'email_pessoal', 'email_trabalho', 'telefone_pessoal', 'telefone_trabalho', 'endereco_cep',
                'endereco_logradouro', 'endereco_numero','!trabalho_cargo','!trabalho_empresa',
                'endereco_complemento', 'endereco_bairro','endereco_estado', 'endereco_cidade', '!tipo_usuario', '!grupo'
            ])
            ::post('/login/ativar-salvar');
        Route
            ::nome('ativarValidar')
            ::request(['hash'])
            ::post('/login/ativar-validar');
        // APP
        Route
            ::nome('app')
            ::view('/login/app');
        Route
            ::nome('api')
            ::view('/login/api/{hash}');
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
        route
            ::nome('buscar')
            ::post('/home/buscar');
    });
Route
    ::nome('analytics')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\IndexController::class)
    ::controller(App\Controllers\Site\AnalyticsController::class)
    ::grupo(function () {
        Route
            ::nome('pagina')
            ::request(['uri', 'vinculo'])
            ::post('/a/pagina');
        Route
            ::nome('click')
            ::post('/a/acao');
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
    ::nome('samsung')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\SamsungController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/samsung');
    });

Route
    ::nome('componente')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\Componente\ComponenteController::class)
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::request(['id', 'url', 'campo', '!relacionado'])
            ::post('/componente');
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
            ::nome('listar')
            ::request(['!pagina'])
            ::post('/cashback/listar');
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
        Route
            ::nome('resgatarCashback')
            ::request(['tipoResgate','email','pontos','!titular','!cpf','!banco','!agencia','!contaBancaria','!tipoConta'])
            ::post('/cashback/resgatar');
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
            ::nome('hotel')
            ::post('/turismo/hotel');
        Route
            ::nome('promocao')
            ::post('/turismo/promocao');
        Route
            ::nome('redirecionar')
            ::view('/turismo/redirecionar');
        Route
            ::nome('redirecionarCampanha')
            ::view('/turismo/redirecionar-campanha/{id}');
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
    ::nome('easylive')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\EasyliveController::class)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::request(['tipo'])
            ::post('/easylive/listar');
        Route
            ::nome('corrida')
            ::view('/corrida');
        Route
            ::nome('nacional')
            ::view('/show-nacional');
        Route
            ::nome('internacional')
            ::view('/show-internacional');
    });

Route
    ::nome('webview')
    ::controller(App\Controllers\Site\Webview\LoginController::class)
    ::grupo(function() {
        Route
            ::nome('mapa')
            ::view('/webview/login-mapa');
    });

Route
    ::nome('loja')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\LojaController::class)
    ::grupo(function () {
        Route
            ::nome('buscar')
            ::request([
                '!estado', '!categoria', '!subcategoria', '!estabelecimento', '!pesquisa', '!ordem',
                '!latitude', '!longitude', '!acessado', '!favorito', '!cidade', '!webview'
            ])
            ::get('/convenios/buscar');
        Route
            ::nome('index')
            ::view('/convenios');
        Route
            ::nome('listar')
            ::request([
                '!pagina', 'tipo', '!latitude', '!longitude', '!acessado', '!favorito', '!estado', '!categoria',
                '!subcategoria', '!estabelecimento', '!pesquisa', '!ordem', '!cidade'
            ])
            ::post('/convenios/listar');
        Route
            ::nome('relacionado')
            ::request(['id'])
            ::post('/convenios/relacionado');
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
    ::nome('telefone_email')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\TelefoneEmailController::class)
    ::grupo(function () {
        Route
            ::nome('listar')
            ::request([
                'id', 'local', 'tipo', 'pagina'
            ])
            ::post('/contato/lista');
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
            ::view('/saude/cnu-florianopolis');
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
            ::post('/saude-contratacao');

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
            ::view('/automovel/{loja}/{modelo}');

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
        Route
            ::nome('excluir')
            ::view('/excluir-conta');
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
            ::nome('principal')
            ::request(['id', 'local', 'latitude', 'longitude'])
            ::post('/endereco/principal');
        Route
            ::nome('estrutura')
            ::request(['id', 'local', 'pais', 'estado'])
            ::post('/endereco/estrutura');
        Route
            ::nome('lista')
            ::request(['id', 'local', 'pais', 'estado', 'cidade'])
            ::post('/endereco/lista');
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
            ::nome('indicarAmigo')
            ::request([
                'nome', 'email', 'telefone'
            ])
            ::post('/indicar-amigo');
        Route
            ::nome('abrirModalEnquetePopup')
            ::view('/enquete-popup/{id}');
        Route
            ::nome('abrirModalPopupImagem')
            ::view('/enquete-imagem');
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
            ::nome('salvarDados')
            ::request([
                '!nome', '!data_nascimento', '!genero', '!estado_civil', '!email_pessoal', '!email_trabalho',
                '!telefone_trabalho', '!telefone_pessoal', '!endereco_estado', '!endereco_cep', '!endereco_logradouro',
                '!endereco_bairro', '!endereco_numero', '!endereco_complemento', '!endereco_cidade',
            ])
            ::post('/perfil/salvar-dados');
        Route
            ::nome('salvarEmail')
            ::request(['!email_pessoal', '!email_trabalho'])
            ::post('/perfil/salvar-email');
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
            ::nome('reenviarConvite')
            ::request(['id'])
            ::post('/perfil/reenviar-convite');
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
            ::request(['imagem'], 'files')
            ::post('/perfil/vincular-foto');
        Route
            ::nome('carteirinha')
            ::view('/perfil/carteirinha');
        Route
            ::nome('buscarCep')
            ::request(['cep'])
            ::post('/perfil/buscar-cep');
    });

Route
    ::nome('pontoCvs')
    ::middleware(ClubeMiddleware::class, 'buscar')
    ::middleware(AuthMiddleware::class, 'logado')
    ::controller(App\Controllers\Site\PontoCvsController::class)
    ::grupo(function () {
        Route
            ::nome('index')
            ::view('/ponto-cvs');
        Route
            ::nome('realizarSolicitacao')
            ::request(['nome', 'email', 'ponto'])
            ::post('/ponto-cvs');
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
