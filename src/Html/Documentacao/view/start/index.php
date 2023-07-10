<?php

$Doc = new DocumentacaoConfig\Fw('GET STARTED', 'Bem vindo a documentação do nosso sistema! Estamos muito <strong>felizes</strong> de você querer aprender um pouco mais sobre essa plataforma que desenvolvemos com tanto <strong>carinho</strong>. Aqui você encontrará tudo o que precisa para começar a utilizar o sistema. Iremos mostrar desde a configuração inicial até as funcionalidades que foram criadas para <strong>facilitar sua vida</strong>.');

$Doc
    ->titulo('Dependencias')
    ->paragrafo('Para o sistema funcionar, o primeiro ponto é instalar as dependências que são:')

    ->subtitulo('GIT')
    ->paragrafo('O projeto usa o GIT como gerênciador de versão, para instalar o git, acesse a página oficinal e siga o passo-a-passo do seu sistema:')
    ->link('Download do GIT', 'https://git-scm.com/downloads', false)

    ->subtitulo('NODE')
    ->paragrafo('Usamos o GULP como automatizador de tarefas e para ele funcionar, precisamos do NODE instalado, para isso, basta acessar a página oficial do NODE e seguir o passo-a-passo do seu sistema operacional clicando no botão abaixo:')
    ->link('Download do Node', 'https://nodejs.org/en/download/', false)
    ->paragrafo('Com o NODE instalado, é a vez de instalar os modules que iremos usar que são o já citado GULP e o pré-processador CSS Stylus.')
    ->codigo('npm install gulp -g')
    ->codigo('npm install stylus -g')

    ->subtitulo('COMPOSER')
    ->paragrafo('Para o gerênciamento das depêndencias do PHP, usamos o composer, mais uma vez, basta seguir o passo-a-passo do site oficial clicando aqui:')
    ->link('Download do Composer', 'https://getcomposer.org/download', false)

    ->subtitulo('DOCKER')
    ->paragrafo('Para o ambiente de desenvolvimento, usamos o docker para gerênciar os conteiner da aplicação, para instalar, basta clicar no botão abaixo:')
    ->link('Download do Docker', 'https://www.docker.com', false)
    ->paragrafo('Você não precisa subir os conteiner, que cuida disso é o GULP, mas caso queira, basta executar:')
    ->codigo('docker-compose up -d')
    ->paragrafo('Para parar a execução, você precisa baixar os conteiner, isso o GULP não faz, então caso cancele o GULP, os container irão continuar executando.')
    ->codigo('docker-compose down');

echo $Doc;
