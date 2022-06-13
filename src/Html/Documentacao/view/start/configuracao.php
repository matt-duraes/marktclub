<?php

$Doc = new DocumentacaoConfig\Fw('CONFIGURAÇÕES', 'Agora que temos todas as depêndencias instaladas, vamos a configuração em sim.');

$Doc
    ->paragrafo('Para começar, precisamos fazer um FORK do projeto, acesse o reposótio, faça o fork e após isso faça o clone do projeto:')
    ->codigo('git clone LINK_DO_SEU_FORK DIRETORIO')
    ->paragrafo('Isso irá clona o projeto para sua máquina.')
    ->paragrafo('Depois de fazer o clone do projeto, você deve instalar as depêndencias do NODE.')
    ->codigo('npm install' . PHP_EOL . 'composer install')
    ->paragrafo('Agora que tem instalamos a depêndencias, vamos configurar o sistema executando:')
    ->codigo('gulp install')
    ->paragrafo('Assim que executar o gulp install, o sistema irá te perguntar algumas informações:')
    ->tabela(function () use ($Doc) {
        $Doc
            ->trTitulo(['Ação', 'Descrição'])
            ->tr(['Título do sistema', 'O título que será usado no sistema'])
            ->tr(['Diretório público', 'O diretório onde os arquivos públicos ficaram, por padrão ficam no public'])
            ->tr(['Url local:', 'Url que será usado no local, por padrão, localhost'])
            ->tr(['Porta HTTP', 'Porta que será usada no servidor HTTP, por padrão, porta 80'])
            ->tr(['Porta HTTPS', 'Porta que será usada no servidor HTTPS, por padrão, porta 443'])
            ->tr(['Porta do MySql', 'Porta que será usada no servidor MySql, por padrão, porta 3306'])
            ->tr(['Porta do PhpMyAdmin', 'Porta que será usada no servidor do PhpMyAdmin, por padrão, porta 8080'])
            ->tr(['Porta do LiveServer', 'Porta que será usada para o LiveServer, por padrão, porta 3000'])
            ->tr(['Nome do Banco de Dados', 'Nome para o banco de dados'])
            ->tr(['Senha do Banco de Dados', 'Senha para o banco de dados'])
            ->tr(['Chave do virus total', 'Chave opcional caso queira usar o virustotal.com para validar os uploads de arquivos'])
            ->tr(['Chave do Google Safe Browsing', 'Chave opcional caso queira usar o Google Safe Browsing para validar os uploads de arquivos'])
            ->tr(['GIT origin', 'A Url do seu Fork no GIT'])
            ->tr(['GIT upstream', 'A Url do projeto original (upstream) no GIT']);
    })
    ->paragrafo('Depois de responder todas as perguntas, basta digitar y pra aceitar ou N para recusar.')
    ->paragrafo('Quando a instalação terminar, basta executar o comando para buildar o sistema.')
    ->codigo('gulp build')
    ->paragrafo('O sistema vai copiar os arquivos HTML, buildar os arquivos Stylus para CSS e os arquivos JS.')
    ->paragrafo('Depois de tudo configurado, basta sempre executar o gulp antes de editar alguma coisa, para isso, basta executar o gulp no terminal:')
    ->codigo('gulp')
    ->paragrafo('Depois do sistema estiver configurado, toda vez que você for fazer alguma alteração, certifique que seu projeto está atualizado com o upstrem:')
    ->codigo('git fetch')
    ->paragrafo('Isso irá baixar todos os branches do upstream e depois basta você fazer um merge, como por exemplo:')
    ->codigo('git merge origin upstream/master');

echo $Doc;
