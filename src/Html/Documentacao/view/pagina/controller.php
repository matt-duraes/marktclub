<?php

$Doc = new DocumentacaoConfig\Fw('CONTROLLER', 'Os controllers são responsáveis por receber as requisições e verificar o que fazer com elas, as vezes chamar uma view, outros passar para um model e retornar um json, tudo vai depender da situação');

$Doc
    ->paragrafo('O primeiro que deve saber sobre controllers e que sempre devem se exetendido ao \Controller\Controller ou ser uma \Controller\ControllerInterface.')
    ->paragrafo('Outro ponto é que todo método do controller deve retornar uma ResponseInterface.')
    ->paragrafo('Outro ponto é que todo método menos as views devem começar com o método HTML da requisição, por exemplo, um post /noticia com a action salvar deve ser inscrito como postSalvar, para ficar mais claro, vou fazer alguns exemplos.')
    ->paragrafo('Primeiro vamos criar o arquivo de rotas:')
    ->codigo('<?php

use Route\Route;

Route
    ::nome("noticia")
    ::controller(App\Controllers\Site\NoticiaController::class)
    ::grupo(function(){
        Route
            ::action("visualizar")
            ::view("/noticia");
        Route
            ::action("buscar")
            ::request(["pesquisa"])
            ::get("/noticia/buscar");
        Route
            ::action("salvar")
            ::request(["titulo","texto"])
            ::post("/noticia");
        Route
            ::action("atualizar")
            ::request(["titulo", "texto"])
            ::put("/noticia/{id}");
        Route
            ::action("deletar")
            ::delete("/noticia/{id}");
});')

    ->paragrafo('Agora que temos as rotas criadas, temos que fazer o controller')
    ->codigo('<?php

namespace App\Controllers\Site;

use Http\Request;
use Controller\Controller;

final class NoticiaController extends Controller
{
    public function visualziar()
    {
        // Código aqui
    }

    public function getBuscar(Request $request)
    {
        // Código aqui
    }

    public function postSalvar(Request $request)
    {
        // Código aqui
    }

    public function putAtualizar(Request $request, string $id)
    {
        // Código aqui
    }

    public function deleteDeletar(string $id)
    {
        // Código aqui
    }
}')

    ->paragrafo('Como visto nos exemplos acima, sempre que você precisar pegar os requests enviados, terá que passar ele no primeiro parâmetro do método.')
    ->paragrafo('Já as partes dinâmicas da URI, devem ser passados nos parametros com o mesmo nome colocado na rota, por exemplo, na rota DELETE /noticia/{id}, deve ser escrito o método public function deleteDeletar(string $id).');

echo $Doc;
