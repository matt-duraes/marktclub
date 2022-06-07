<?php

$Doc = new DocumentacaoConfig\Fw('VIEW', 'As views são as páginas HTML propriamente dito, é tudo aquilo que o usuário final vê.');

$Doc
    ->paragrafo('Para chamar uma view, você deve usar a função view no controller.')
    ->titulo('view')
    ->blocoParametro(function () use ($Doc) {
        $Doc
            ->parametro('string', '$arquivo', 'Nome do arquivo da view até o diretorio final, não precisa colocar o index.view')
            ->parametro('array', '$var', 'Lista de variáveis que devem ser passadas para view.')
            ->parametro('array', '$header', 'Lista de header no padrao header => valor para ser adicionada a página')
            ->parametro('string', '$css', 'CSS para ser inserido na página')
            ->parametro('string', '$js', 'JS para ser inserido na página');
    })
    ->codigo('view(string $arquivo, array $var = [], array $header = [], ?string $css = null, ?string $js = null): \Http\Response')
    ->retorno('\Http\Response', 'Retonar uma response do tipo view')
    ->paragrafo('Para o exemplo, iremos criar um controller com a view.')
    ->codigo('<?php

namespace App\Controllers\Site;

use Controller\Controller;
use App\Models\Site\Noticia\NoticiaEntity;

final class NoticiaController extends Controller
{
    public function visualizar(string $slug)
    {
        // Por enquanto, ignore essa busca, ela está aqui apenas de forma ilustrativa
        $Noticia = new NoticiaEntity();
        $Noticia->buscar(["slug", $slug]);

        return view("noticia.detalhe", [
            "titulo" => $Noticia->titulo,
            "texto" => $Noticia->texto
        ]);
    }
}
    ')
    ->paragrafo('No exemplo acima, criamos um controller com o método visualizar que chama a view "noticia.detalhe", também passamos o titulo e o texto como variáveis para a view.')
    ->paragrafo('Para ficar mais fácil de entender a view noticia.detalhe fica em views/pages/site/noticia/detalhe/index.view como mostrando na explicação do que é um APP, como visto, a primeira parte que é views/pages/site é colocado automaticamente pelo framework e o final index.view deve ser omitido. Outro ponto é que se usa . (ponto) no lugar de cada / (barra).')
    ->paragrafo('Não recomendo mas você poderia passar o diretório raiz da rota, se por algum motivo você queira pegar uma view no site que está no diretório da API por exemplo, você pode passar explicitamente isso na view:')
    ->codigo('
...
return view("api.noticia.email", [
    "titulo" => $Noticia->titulo,
    "texto" => $Noticia->texto
]);
...
    ')
    ->paragrafo('O código acima entraria em views/pages/api/noticia/email/index.view, não recomendo essa utilização porque teoricamente, cada área deve ser isolada e até bloqueada para a sua URL expecífica pelo arquivo env.')
    ->titulo('Arquivos .view')
    ->paragrafo('Primeiro de tudo é lembrar que arquivos .view só funcionam com o GULP rodando, por isso, execute o GULP antes de fazer qualquer exemplo a seguir. Com isso, vamos criar o arquivo do template:')
    ->codigo('<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site</title>
    @CSS
</head>
<body>
    <header>
        <h1>HEADER</h1>
    </header>
    <main>
        [[VIEW]]
    </main>
    <footer>FOOTER</footer>
    @JS
</body>
</html>')
    ->paragrafo('A uníca mudança do arquivo do template para um arquivo HTML normal é que passamos a string [[VIEW]] que será substituida pelos dados da view informada no controller e o @CSS e @JS que simplesmente colocaram os arquivos CSS e JS automaticamente.')
    ->paragrafo('Agora que temos o template, iremos criar a página da view.')
    ->codigo('@template "site"

<article>
    <header>
        <h1>{{$titulo}}</h1>
        <p>{{$texto}}</p>
    </header>
</article>
')
    ->paragrafo('Aqui vimos algumas mudanças, a primeira é que temos que chamar o template na primeira linha, o segundo é que usamos {{$variavel}} para imprimir a variável passada pelo controller.')
    ->titulo('Funções da view')
    ->paragrafo('Como visto no exemplo acima, a view tem várias funções especiais para não precisar usar PHP na view, aqui vamos listar todas e colocar alguns exemplos:')
    ->tabela(function () use ($Doc) {
        $Doc
            ->trTitulo(['Função', 'Descrição'])
            ->tr(['{{$variavel}}', 'Imprime uma variável escapando todos os caracteres especiais.'])
            ->tr(['{!!$variavel!!}', 'Imprime uma variável sem escapar nada, tome cuidado com o uso dela, deve ser usando por exemplo em link que não podem ter os caracteres escapados.'])
            ->tr(['@if( ... ):', 'If do PHP'])
            ->tr(['@elseif():', 'Elseif do PHP'])
            ->tr(['@endif', 'End do PHP'])
            ->tr(['@ifisset($variavel): ', 'Mesma coisa de um if(isset($variavel) && $variavel):'])
            ->tr(['@ifissetobject($variavel): ', 'Mesma coisa de um if(isset($variavel) && is_object($variavel) && !vazio($object)):'])
            ->tr(['@ifissetarray($variavel): ', 'Mesma coisa de um if(isset($variavel) && is_array($variavel) && !is_empty($object)):'])
            ->tr(['@switch( ... )', 'Switch do PHP'])
            ->tr(['@endswitch( ... )', 'Endswitch do PHP'])
            ->tr(['@while( ... )', 'While do PHP'])
            ->tr(['@endwhile( ... )', 'Endwhile do PHP'])
            ->tr(['@foreach( ... )', 'Foreach do PHP'])
            ->tr(['@endforeach( ... )', 'endforeach do PHP'])
            ->tr(['@ifforeach( ... )', 'Mesma coisa de um <?php if($variavel): foreach($variavel ...): ?>'])
            ->tr(['@endifforeach( ... )', 'Mesma coisa de endforeach; endif;'])
            ->tr(['@elseforeach( ... )', 'Mesma coisa de endforeach; else:'])
            ->tr(['@for( ... )', 'For do PHP'])
            ->tr(['@endfor( ... )', 'Endfor do PHP'])
            ->tr(['@laco(10)', 'Cria um for com x repetições'])
            ->tr(['@endlaco', 'Fecha o for do laço'])
            ->tr(['@continue(condicao)', 'Faz um if(condicao) {continue;} para foreach;'])
            ->tr(['@break(condicao)', 'Faz um if(condicao) {break;} para foreach;'])
            ->tr(['@resource', 'Faz um include em algum arquivo PHP do /resources/php/'])
            ->tr(['@dir', 'Faz um include em algum arquivo .view de dentro do próprio diretório'])
            ->tr(['@require_once|require|include_once|include', 'Faz um include padrão do PHP'])
            ->tr(['@view', 'Faz um include em algum arquivo PHP da /views/pages/'])
            ->tr(['@php', 'Cria uma abertura PHP'])
            ->tr(['@end', 'Cria um fechamento de PHP'])
            ->tr(['@?', 'Mesma coisa de um <?= ?>'])
            ->tr(['@templete', 'Chama um template'])
            ->tr(['@route(post.noticia.salvar)', 'Pega a rota do post noticia salvar [metodo, grupo, action]'])
            ->tr(['@icone[Algo]', 'Coloca um icone padrão do sistema, por exemplo: @iconeEmail(20), irá imprimir um icone de e-mail com 20px de altura'])
            ->tr(['@hash("NOME")', 'Cria um sistema de hash para Formulário']);
    })
    ->paragrafo('Vamos fazer alguns exemplos para ficar mais claro:')
    ->codigo('@template "site"

<form action="@route(post.noticia.buscar)" method="get">
    @hash("BUSCA_NOTICIA")
    @? formInput(name: "pesquisa", label: "Pesquisa" placeholder: "Pesquisa...");
    @iconeBuscar(20)
</form>

<article>
    <header>
        <h1>{{$titulo}}</h1>
    </header>
    <p>{{$texto}}</p>
</article>

<aside>
    @ifforeach($noticiaRelacionadas as $r):
    <article>
        <a href="{!!LINK!!}/noticia/{{$r->slug}}"></a>
        <header>
            <h1>{{$r->titulo}}</h1>
            <p>{{$r->chamada}}</p>
        </header>
    </article>
    @endifforeach;
</aside>

@php
    define("TESTE", true);
@end

<div class="pagina">
    @for($i=0; $i<10; $i++):
    <a href="@LINK/noticia/todas?pagina={{$i}}">{{$i}}</a>
    @endfor;
</div>
');

echo $Doc;
