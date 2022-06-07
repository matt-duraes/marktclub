<?php

$Doc = new DocumentacaoConfig\Fw('CSS', 'A utilização do CSS é feita atraves do pré processador Stylus, por isso, ele precisa do gulp rodando.');

$Doc
    ->paragrafo('Nesse ponto não irei ensinar a usar o Stylus, se você chegou aqui é porque deve ter conhecimento para isso, aqui vou ensinar apenas a importar arquivos que é a única diferença que temos para o stylus normal.')
    ->paragrafo('Você pode importar arquivos do sistema, do template, do resources ou do próprio diretório, vamos criar uma estrutura para exemplificar:')
    ->tabela(function () use ($Doc) {
        $Doc
            ->trTitulo(['Diretório', 'Descrição'])
            ->tr(['resources/css/site/variavel.styl', 'Arquivos de variáveis usada pelo stylus no site'])
            ->tr(['views/templates/site/css/layout.styl', 'Arquivo principal do template'])
            ->tr(['views/pages/site/noticia/detalhe/css/layout.styl', 'Arquivo principal da notícia'])
            ->tr(['views/pages/site/noticia/detalhe/css/responsivo.styl', 'Imaginamos que por uma escolha de organização, colocamos o responsivo separado']);
    })
    ->paragrafo('Fora essa estrutura, você pode importar arquivos padrões do sistema que são:')
    ->tabela(function () use ($Doc) {
        $Doc
            ->trTitulo(['Arquivo', 'Descrição'])
            ->tr(['Ajuda', 'CSS do plugin JS de ajuda'])
            ->tr(['Alerta', 'CSS do plugin JS de alerta '])
            ->tr(['Calendario', 'CSS do plugin JS de calendário'])
            ->tr(['Form', 'CSS padrão dos formulários do sistema'])
            ->tr(['Grafico', 'CSS do plugin JS de gráfico'])
            ->tr(['Loading', 'CSS do plugin JS de loading'])
            ->tr(['Pagina', 'CSS do plugin JS de página via popup'])
            ->tr(['Resetar', 'CSS com o reset do CSS'])
            ->tr(['Variavel', 'CSS com várias variáveis']);
    })
    ->paragrafo('A seguir irei listar as variáveis do arquivo do sistema comentado na tabela acima:')
    ->codigo('
/* CORES DE MARCAS CONHECIDAS */
$twitter = #00aced
$whatsapp = #34af23
$facebook = #3b5998
$youtube = #bb0000
$pinterest = #cb2027
$skype = #46a6f7
$instagram = #833dc1
$vimeo = #20b9eb
$twitch = #63b4e4
$messenger = #2b7ef6
$linkedin = #007bb6
$flickr = #ff1981
$tumblr = #385774
$foursquare = #0072b1
$android = #a4c639
$amazon = #ff6600
$dropbox = #007ee5
$word = #0054a6
$excel = #008641
$powerpoint = #f04e23
$google = #4c89e3
$html5 = #e34f26
$lastfm = #c3000d
$rss = #f26522
$spotify = #7ab800
$yahoo = #400191
$xbox = #107b11
$playstation = #0069c1
$window = #00bcf2
$ubuntu = #dd4814
$windowphone = #68217a

/* CORES ESCOLHIDAS COM MAIS CUIDADO */
$corVerde = #169e91
$corVermelho = #FF6C60
$corAzul = #00aced
$corLaranja = #f26522

/* CORES PADRÕES */
$branco = #FFFFFF

/* COR PADRÕES */
$verde = #009900
$verde1 = #99FF99
$verde2 = #66FF99
$verde3 = #33ff33
$verde4 = #00CC00
$verde5 = #009900
$verde6 = #006600
$verde7 = #003300

$rosa = #CC33CC
$rosa1 = #FFCCFF
$rosa2 = #FF99FF
$rosa3 = #CC66CC
$rosa4 = #CC33CC
$rosa5 = #993366
$rosa6 = #663366
$rosa7 = #330033

$azul = #0000FF
$azul1 = #CCFFFF
$azul2 = #66FFFF
$azul3 = #33CCFF
$azul4 = #3366FF
$azul5 = #3333FF
$azul6 = #000099
$azul7 = #000066

$preto = #000
$preto1 = #CCC
$preto2 = #999
$preto3 = #666
$preto4 = #333
$preto5 = #000

$vermelho = #FF0000
$vermelho1 = #FFCCCC
$vermelho2 = #FF6666
$vermelho3 = #FF0000
$vermelho4 = #CC0000
$vermelho5 = #990000
$vermelho6 = #660000
$vermelho7 = #330000

$laranja = #FF6600
$laranja1 = #FFCC99
$laranja2 = #FFCC33
$laranja3 = #FF9900
$laranja4 = #FF6600
$laranja5 = #CC6600
$laranja6 = #993300
$laranja7 = #663300

$amarelo = #FFFF00
$amarelo1 = #FFFFCC
$amarelo2 = #FFFF99
$amarelo3 = #FFFF00
$amarelo4 = #FFCC00
$amarelo5 = #999900
$amarelo6 = #666600
$amarelo7 = #333300

$marrom = #8B4513
$marrom1 = #FF8247
$marrom2 = #EE7942
$marrom3 = #CD6839
$marrom4 = #8B4726
    ')
    ->paragrafo('Agora que temos isso em mente, vamos criar o CSS do template:')
    ->codigo('
@system "Resetar"

body
    background-color: #F6F6F6
    ')
    ->paragrafo('Como pode ser visto, a única coisa que fizemos no template foi importar o reset do system e colocar uma cor bo background do body, qualquer dos arquivos padrões do sistema que foram listados acima, devem ser chamados usando o @system "nome_do_arquivo".')
    ->paragrafo('Com o template pronto, vamos criar o arquivo do detalhe das notícias:')
    ->codigo('
@template "site"
@resources "site/variavel"
@import "responsivo"

#noticia
    width: 100%
    ...
    ')
    ->paragrafo('Aqui fizemos várias importações começando pelo template, depois importamos o arquivo de variavel do resource/site e por fim, o arquivo do mesmo diretório com o código do responsivo. Do mesmo jeito que aconteceu com o system, sempre que desejar importar algo do resource basta chamar @resource "nome_do_arquivo" e para uma importação normal, o @import.');

echo $Doc;
