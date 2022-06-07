<?php

$Doc = new DocumentacaoConfig\Fw('CRIANDO UMA PÁGINA', 'Agora que temos tudo configurado, vamos criar uma página.');

$Doc
    ->paragrafo('A primeira que devemos fazer é iniciar o gulp, para isso, basta digital gulp no terminal, isso irá subir o sistema e irá abrir o navegador com o liveServer.')
    ->paragrafo('Com o servidor rodando, vamos criar uma rota, para isso, edite/crie o arquivo routes/SiteRoute.php')
    ->codigo('
<?php

use Route\Route;

Route::grupo(function() {
    Route
        ::nome("index")
        ::controller(App\Controllers\Site\IndexController::class)
        ::view("/");
});
    ')
    ->paragrafo('Agora basta criar/editar o arquivo app/Controllers/Site/IndexController.php')
    ->codigo('
<?php

namespace App\Controllers\Site;

use Controller\Controller;

final class IndexController extends Controller
{
    public function index()
    {
        return view("index");
    }
}
    ')
    ->paragrafo('Por fim, vamos criar o arquivo views/pages/site/index/index.view, lembrando que o gulp tem que está rodando.')
    ->codigo('
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Olá mundo!</title>
</head>
<body>
    <h1>Olá mundo!</h1>
</body>
</html>
    ')
    ->paragrafo('Com tudo configurado, basta acessar a raiz do seu projeto que deve ser aberto a view que acabamos de criar.');

echo $Doc;
