<?php

$Doc = new DocumentacaoConfig\Fw('APP', 'App são as páginas HTML, CSS, JS e afins, eles sempre devem ser editados com o gulp rodando.');

$Doc
    ->paragrafo('O primeiro a entender é a estrutura de um "APP", nos tratamos cada página como se fosse um APP tendo em vista que ele pode ser 100 independente, por isso, acostume-se com esse termo, cada página tem seu próprio mundo, vamos imaginar uma página de notícias na rota do site, a estrutura ficaria assim:')
    ->tabela(function () use ($Doc) {
        $Doc
            ->trTitulo(['Página', 'Diretório', 'Descrição'])
            ->tr(['template', 'views/templates/site', 'O diretório raiz do template da rota de notícias'])
            ->tr(['', 'views/templates/site/index.view', 'Arquivo .view que é o HTML do template'])
            ->tr(['', 'views/templates/site/css', 'Diretório CSS do template'])
            ->tr(['', 'views/templates/site/css/layout.styl', 'O arquivo principal sempre deve se chamar layout.styl'])
            ->tr(['', 'views/templates/site/js', 'Diretório JS do template'])
            ->tr(['', 'views/templates/site/js/all.js', 'O arquivo principal sempre deve se chamar all.js'])

            ->tr(['notícia', 'views/pages/site/noticia', 'O diretório raiz da notícia, aqui ficariam todos os "APPs"'])

            ->tr(['lista', 'views/pages/site/noticia/lista', 'Diretório com o APP de todas as notícias'])
            ->tr(['', 'views/pages/site/noticia/lista/index.view', 'Arquivo .view que é o HTML do APP'])
            ->tr(['', 'views/pages/site/noticia/lista/css', 'Diretório CSS do APP'])
            ->tr(['', 'views/pages/site/noticia/lista/css/layout.styl', 'O arquivo principal sempre deve se chamar layout.styl'])
            ->tr(['', 'views/pages/site/noticia/lista/js', 'Diretório JS do APP'])
            ->tr(['', 'views/pages/site/noticia/lista/js/all.js', 'O arquivo principal sempre deve se chamar all.js'])

            ->tr(['detalhe', 'views/pages/site/noticia/detalhe', 'Diretório com o APP de detalhes, seria a página quando clicar em uma notícia da lista'])
            ->tr(['', 'views/pages/site/noticia/detalhe/index.view', 'Arquivo .view que é o HTML da página, como da para perceber, ele sempre será chamado de index.view'])
            ->tr(['', 'views/pages/site/noticia/detalhe/css', 'Diretório CSS do APP'])
            ->tr(['', 'views/pages/site/noticia/detalhe/css/layout.styl', 'O arquivo principal sempre deve se chamar layout.styl'])
            ->tr(['', 'views/pages/site/noticia/detalhe/js', 'Diretório JS do APP'])
            ->tr(['', 'views/pages/site/noticia/detalhe/js/all.js', 'O arquivo principal sempre deve se chamar all.js']);
    })
    ->paragrafo('Como da para ver, cada página pode ter seu universo, com seu próprio css, js e até suas rotas, controllers, models e afins se desejar, isso foi feito pensando na facilidade de mover um APP de um projeto para outro.')
    ->paragrafo('Aqui temos alguns pontos que devem ser olhados. Primeiro, se um APP tem um template mas não possui um CSS e/ou JS, ele automaticamente adiciona o CSS e/ou JS do template, caso uma página por exemplo não tiver JS, não precisa criar um arquivo apenas para setar o JS do template. Segundo, caso o arquivo tenha CSS e/ou JS, vc é obrigado a importar o template para esses arquivos caso queira que os scripts gerais do template funcionem.');
echo $Doc;
