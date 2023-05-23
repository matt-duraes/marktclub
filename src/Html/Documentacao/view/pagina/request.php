<?php

$Doc = new DocumentacaoConfig\Fw('REQUEST', 'Requests são tudo aquilo que é enviado para o navegador como header, POSTS, FILES e etc, eles devem ser usados apenas nos controllers');

$Doc
    ->paragrafo('Os requests sempre são enviados para o primeiro parâmetro dos métodos dos controllers, por exemplo, em uma rota GET /noticias/buscar, ficaria assim:')
    ->codigo('
...
use Http\Request;

...
public function getBuscar(Request $request)
{

}
...
    ')
    ->paragrafo('Mesmo que a rota tenha uma URI dinâmica, o request continua sendo o primeiro parâmetro, como por exemplo em uma rota VIEW /noticia/slug-da-noticia')
    ->codigo('
use Http\Request;

...
public function getBuscar(Request $request, string $slug)
{

}
    ')
    ->paragrafo('Agora que sabemos como chamar as rotas, vamos aos seus métodos:')
    ->margin(30)
    ->funcao(ROOT . '/src/Http/Request.php')
    ->paragrafo('Caso queira, você ainda pode chamar as propriedades diretamente na request.')
    ->codigo('
use Http\Request;

...
public function getBuscar(Request $request, string $slug)
{
    $nome = $request->nome;
}
    ')
    ->paragrafo('Outro ponto é usando os métodos de lista, por padrão, todos os dados serão purificados e removido todo caracter HTML existe nos parâmetros, em alguns casos isso pode ser ruim, como no texto de uma notícia que tenho HTML, quando isso ocorrer, você terá que chamar algum método que gerêncie esses poderes, por exemplo, na rota POST /noticia podemos usar:')
    ->codigo('
use Http\Request;

...
public function postSalvar(Request $request)
{
    $texto = $request->getPost("texto", html: false);
}
    ')
    ->paragrafo('No exemplo acima você pode notar que peguei diretamente o valor do parâmetro texto pelo _POST e falei para ele não limpar o html. Tome muito cuidado ao fazer isso, principalmente se desabilitar o purifier que é algo que não recomendo, sempre que isso ocorre, você está abrindo espaço para brechas de segurança.');

echo $Doc;
