<?php

$Doc = new DocumentacaoConfig\Fw('PLUGINS', 'Para usar o sistema de forma mais eficiente, vou listar uma serie de extensões do VSCode.');

$Doc
    ->tabela(function () use ($Doc) {
        $Doc
            ->tr(['Editor', 'Terminal', 'Terminal integrado ao VSCode', 'https://marketplace.visualstudio.com/items?itemName=formulahendry.terminal'])
            ->tr(['Editor', 'Backspace++', 'Remove 4 espaços a cada backspace apertado', 'https://marketplace.visualstudio.com/items?itemName=jrieken.backspace-plusplus'])
            ->tr(['Editor', 'TabSanity', 'Pula de 4 em 4 espaços ao aperta as teclas para a esquerda e direita', 'https://marketplace.visualstudio.com/items?itemName=jedmao.tabsanity'])
            ->tr(['Editor', 'indent-rainbow', 'Colore os tabs para facilitar a leitura', 'https://marketplace.visualstudio.com/items?itemName=oderwat.indent-rainbow'])
            ->tr(['Editor', 'Beautify', 'Formata os códigos de várias linguagens', 'https://marketplace.visualstudio.com/items?itemName=HookyQR.beautify'])
            ->tr(['HTML', 'Auto Close Tag', 'Fecha as tags html de forma automática', 'https://marketplace.visualstudio.com/items?itemName=formulahendry.auto-close-tag'])
            ->tr(['HTML', 'Auto Rename Tag', 'Renomeia o fechamento da tag html ao renomear a abertura', 'https://marketplace.visualstudio.com/items?itemName=formulahendry.auto-rename-tag'])
            ->tr(['Docker', 'Docker', 'Ajuda a manipular o docker direto no editor', 'https://marketplace.visualstudio.com/items?itemName=ms-azuretools.vscode-docker'])
            ->tr(['JS', 'ESLint', 'Valida seu código JS', 'https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint'])
            ->tr(['JS', 'Prettier - Code formatter', 'Padrão de escrita do JS', 'https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode'])
            ->tr(['CSS', 'Sass', 'Formata o arquivos styls com o mesmo padrão do Sass', 'https://marketplace.visualstudio.com/items?itemName=Syler.sass-indented'])
            ->tr(['GIT', 'GitLens', 'Cria um histórico em tempo real linha por linha', 'https://marketplace.visualstudio.com/items?itemName=eamodio.gitlens'])
            ->tr(['Mysql', 'MySQL', 'Gerencia o MySql direto no VSCode', 'https://marketplace.visualstudio.com/items?itemName=cweijan.vscode-mysql-client2'])
            ->tr(['PHP', 'PHP Intelephense', 'Ajuda no autocomplete do PHP', 'https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client'])
            ->tr(['PHP', 'PHP IntelliSense', 'Ajuda no autocomplete do PHP', 'https://marketplace.visualstudio.com/items?itemName=zobo.php-intellisense'])
            ->tr(['PHP', 'PHP Namespace Resolver', 'Ajuda a resolver os namespaces', 'https://marketplace.visualstudio.com/items?itemName=MehediDracula.php-namespace-resolver'])
            ->tr(['Plus', 'Rainbow Brackets', 'Colore os parentes que abrem e fecham uma condição com a mesma cor', 'https://marketplace.visualstudio.com/items?itemName=2gua.rainbow-brackets'])
            ->tr(['Plus', 'file-icons', 'Conjunto de ícones', 'https://marketplace.visualstudio.com/items?itemName=file-icons.file-icons'])
            ->tr(['Plus', 'Dracula Official', 'Um dos temas mais populares do VsCode', 'https://marketplace.visualstudio.com/items?itemName=dracula-theme.theme-dracula']);
    });

echo $Doc;
