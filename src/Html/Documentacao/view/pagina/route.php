<?php

$Doc = new DocumentacaoConfig\Fw('ROTAS', 'As rotas basicamente servem para filtrar as entradas das requisições e mandá-las para os controllers, aqui iremos descrever o que se da para fazer com elas.');

$Doc
    ->paragrafo('Para começar a falar de rotas, devos entender que cada arquivo de rota é como se fosse um site, com isso, as boas práticas falam para cada arquivo de rota ter seu próprio env e seu próprio diretório nos app, views, templates e etc. Por exemplo, na rota Site, vamos ter a seguinte estrutura:')
    ->tabela(function () use ($Doc) {
        $Doc
            ->trTitulo(['Diretório', 'Descrição'])
            ->tr(['app/Controllers/Site', 'Controllers da rota site'])
            ->tr(['app/Middlewares/Site', 'Middlewares da rota site a não ser que o middleware seja geral'])
            ->tr(['app/Models/Site', 'Models da rota site'])
            ->tr(['resources/css/site', 'Arquivos CSS padrões da rota site'])
            ->tr(['resources/js/site', 'Arquivos JS padrões da rota site'])
            ->tr(['resources/php/site', 'Arquivos PHP padrões da rota site'])
            ->tr(['views/pages/site', 'Páginas HTML da rota site'])
            ->tr(['views/templates/site', 'Template para as páginas HTML da rota site']);
    })
    ->paragrafo('Outro ponto é que todo arquivo route deve terminar com Route, por exemplo, routes/SiteRoute.php')
    ->margin(40)

    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Grupo')
            ->paragrafo('O primeiro conceito que devemos aprender são os grupos, eles servem para envolver as rotas.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('Closure', '$callback', 'Função que envolve as rotas')
                    ->parametro('bool', '$encandear', 'Se o grupo vai ser encandeado ou não, false por padrão');
            })
            ->codigo('\Route\Route::grupo(Closure $callback, bool $encandear = false): void|self')
            ->retorno('void|self', 'Retorna a própria classe caso o $encandear seja true ou void')
            ->paragrafo('Exemplo de rota vazia:')
            ->codigo('
<?php

use Route\Route;

Route::grupo(function(){

});
            ');
    })
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Controller')
            ->paragrafo('Toda rota tem que ter um controller.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', '$nome', 'Nome do controller que deve ser usado');
            })
            ->codigo('\Route\Route::controller(App\Controllers\ExemploController::class)')
            ->retorno('self', 'Retorna a própria classe')
            ->paragrafo('Exemplo:')
            ->codigo('
<?php

use Route\Route;

Route
    ::controller(App\Controllers\ExemploController::class)
    ::grupo(function(){

    });
            ');
    })
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Métodos')
            ->paragrafo('As requisições podem ser de 5 tipos: view, get, post, put e delete.')
            ->paragrafo('Cada tipo serve para um método HTTP ou para chamar uma view, no caso na view, o método HTTP será um GET.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string|array', '$uri', 'URI da requisição ou um array com listas de requisições');
            })
            ->codigo('
\Route\Route::view(string|array $uri): void
\Route\Route::get(string|array $uri): void
\Route\Route::post(string|array $uri): void
\Route\Route::put(string|array $uri): void
\Route\Route::delete(string|array $uri): void
            ')
            ->paragrafo('Exemplo:')
            ->codigo('
<?php

use Route\Route;

Route
    ::controller(App\Controllers\ExemploController::class)
    ::grupo(function(){
        Route::view("/uri");
        Route::get("/uri");
        Route::post("/uri");
        Route::put("/uri");
        Route::delete("/uri");
    });
            ');
    })
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Actions')
            ->paragrafo('Se você tentar acessar as rotas nesse momento, não irá funcionar porque não foi setado as actions que as uri devem acessar.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', '$nome', 'Nome da action que deve ser acessada pela requisição');
            })
            ->codigo('\Route\Route::action(string $nome): self')
            ->retorno('self', 'Retorna a própria classe')
            ->paragrafo('Exemplo:')
            ->codigo('
<?php

use Route\Route;

Route
    ::controller(App\Controllers\ExemploController::class)
    ::grupo(function(){
        Route
            ::action("acao")
            ::view("/uri");
    });
            ');
    })
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Nomes')
            ->paragrafo('Outra forma de setar as action são os nomes, fora isso, eles servem para pegar a URL da rota de forma dinamica. Os nomes podem ser setados tanto para os grupos como para as requisições.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', '$nome', 'Nome do grupo ou requisição');
            })
            ->codigo('\Route\Route::nome(string $nome): self')
            ->retorno('self', 'Retorna a própria classe')
            ->paragrafo('Exemplo:')
            ->codigo('
<?php

    use Route\Route;

    Route
        ::controller(App\Controllers\ExemploController::class)
        ::nome("nomeGrupo")
        ::grupo(function(){
            Route
                ::nome("nomeRequisicao")
                ::view("/uri");
        });
            ');
    })
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Middlewares')
            ->paragrafo('O conceito de middleware pode ser um pouco confuso, mas basicamente ele vai executar uma class/action antes ou depois do controller, como os nomes, eles podem ser passado no grupo ou nas requisições.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', '$classe', 'Nome da classe que desse ser chamada')
                    ->parametro('string', '$action', 'O método que deve ser chamado')
                    ->parametro('null|array', '$parametro', 'Array com a lista de parametros da classe')
                    ->parametro('null|array', '$construtor', 'Array para o construtor da classe')
                    ->parametro('bool', '$pos', 'True para o middleware ser executado após o controller');
            })
            ->codigo('\Route\Route::middleware(string $classe, string $action, ?array $parametro = null, ?array $construtor = null, bool $pos = false): self')
            ->retorno('self', 'Retorna a própria classe')
            ->paragrafo('Exemplo:')
            ->codigo('
<?php

use Route\Route;

Route
    ::middleware(App\Middlewares\TesteMiddleware::class, "metodoDoGrupo")
    ::controller(App\Controllers\ExemploController::class)
    ::nome("nomeGrupo")
    ::grupo(function(){
        Route
            ::middleware(App\Middlewares\TesteMiddleware::class, "metodoDaRequisicao", pos: true)
            ::nome("nomeRequisicao")
            ::view("/uri");
    });
            ')
            ->paragrafo('Como visto no exemplo acima, existe o middleware do grupo que será executado em todas as requisições do grupo, já o middleware da requisição só irá ser executado naquela exata requisição.');
    })
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Requests')
            ->paragrafo('Requests são os parametros que podem ser enviado via GET, POST ou PUT.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string|array', '$request', 'Array com os indices aceitos na request, caso queira deixar livre, usar o coringa "*"')
                    ->parametro('null|string', '$tipo', 'O tipo da request podendo ser get, post, put ou files, caso passe null, pega o método padrão da requisição');
            })
            ->codigo('\Route\Route::request(string|array $request, ?string $tipo = null): self')
            ->retorno('self', 'Retorna a própria classe')
            ->paragrafo('Exemplo:')
            ->codigo('
<?php

use Route\Route;

Route
    ::controller(App\Controllers\ExemploController::class)
    ::nome("nomeGrupo")
    ::grupo(function(){
        Route
            ::nome("nomeRequisicao")
            ::request(["campo_1", "campo_2", "campo_3"])
            ::view("/uri");
    });
            ')
            ->paragrafo('Caso queira deixar a requisição livre, basta usar o coringa "*"')
            ->codigo('
<?php

use Route\Route;

Route
    ::controller(App\Controllers\ExemploController::class)
    ::nome("nomeGrupo")
    ::grupo(function(){
        Route
            ::nome("nomeRequisicao")
            ::request("*")
            ::view("/uri");
    });
            ')
            ->paragrafo('Caso queira deixar o campo 1 e 2 opcionais e o 3 obrigatório, basta usar o coringa "!" antes do nome do campo')
            ->codigo('
<?php

use Route\Route;

Route
    ::controller(App\Controllers\ExemploController::class)
    ::nome("nomeGrupo")
    ::grupo(function(){
        Route
            ::nome("nomeRequisicao")
            ::request(["!campo_1", "!campo_2", "campo_3"])
            ::view("/uri");
    });
            ');
    })
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('URI dinâmicas')
            ->paragrafo('Em alguns casos, você pode usar uma URI dinâmica, como por exemplo, /noticia/nome-da-noticia-aqui, o /noticia é fixo, já a /nome-da-noticia-aqui é o slug da matéria dinâmica.')
            ->codigo('
<?php

use Route\Route;

Route
    ::controller(App\Controllers\ExemploController::class)
    ::nome("nomeGrupo")
    ::grupo(function(){
        Route
            ::nome("nomeRequisicao")
            ::request(["campo_1", "campo_2", "campo_3"])
            ::view("/uri/{url}");
    });
            ')
            ->paragrafo('Como visto no exemplo acima, toda URL dinâmica deve ter o padrão /^\{[a-zA-Z]{1,}[a-zA-Z0-9\_]*\}$/, isso se da porque no controller, algo que veremos a frente, ela será chamada como variável com o mesmo nome indicado aqui.')
            ->paragrafo('Nada impede a quantidade de partes dinâmicas da URI, como por exemplo: /noticia/principal/{slug} ou /noticia/secudaria/{slug}/{uf} e assim por diante.');
    });

echo $Doc;
