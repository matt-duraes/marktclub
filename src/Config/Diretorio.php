<?php

/*
|--------------------------------------------------------------------------
| GIT
|--------------------------------------------------------------------------
*/
if (!file_exists(__DIR__ . '/../../.git') && SISTEMA == 'LOCALHOST') {
    throw new \Erro\Erro(
        mensagem: 'GIT não foi iniciado',
        titulo: 'Erro na aplicação',
        texto: 'Inicie o seu repositorio GIT para usar o Framework',
        sugestao: [
            'git init'
        ]
    );
} elseif (!file_exists(__DIR__ . '/../../.git/hooks/pre-commit') && SISTEMA == 'LOCALHOST') {
    throw new \Erro\Erro(
        mensagem: 'GIT não configurado',
        titulo: 'Erro na aplicação',
        texto: 'Copie o Hook do diretorio files/git/ para .git/hooks',
        sugestao: [
            'cp files/git/pre-commit .git/hooks'
        ]
    );
}

/*
|--------------------------------------------------------------------------
| COMPOSER
|--------------------------------------------------------------------------
*/
if (!is_dir(__DIR__ . '/../../vendor')) {
    throw new \Erro\Erro(
        mensagem: 'Composer não foi iniciado',
        titulo: 'Erro na aplicação',
        texto: 'Você deve iniciar o composer',
        sugestao: [
            'composer install'
        ]
    );
}

if (!is_writable(__DIR__ . '/../../vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer')) {
    throw new \Erro\Erro(
        mensagem: 'Sem permissão no diretório vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer',
        titulo: 'Erro de permissão',
        texto: 'Você deve dar permissão de escrita no diretório vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer',
        sugestao: [
            'chmod -R 775 vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer',
            'chown -R www-data:root vendor/ezyang/htmlpurifier/library/HTMLPurifier/DefinitionCache/Serializer'
        ]
    );
}
/*
|--------------------------------------------------------------------------
| NODE
|--------------------------------------------------------------------------
*/
if (!is_dir(__DIR__ . '/../../node_modules')) {
    throw new \Erro\Erro(
        mensagem: 'Node não iniciado',
        titulo: 'Erro na aplicação',
        texto: 'Execute npm install para configurar os modulos do node',
        sugestao: [
            'npm install'
        ]
    );
}
if (!file_exists(__DIR__ . '/../../files/config/.config')) {
    throw new \Erro\Erro(
        mensagem: 'Framework não iniciado',
        titulo: 'Erro na aplicação',
        texto: 'Execute gulp install --config para configurar o framework',
        sugestao: [
            'gulp install --config'
        ]
    );
}

/*
|--------------------------------------------------------------------------
| PHPMUSSEL
|--------------------------------------------------------------------------
*/
if (!file_exists(__DIR__ . '/../../phpmussel.yml')) {
    throw new \Erro\Erro(
        mensagem: 'O arquivo phpmussel.yml não existe',
        titulo: 'Arquivo não existe',
        texto: 'Você precisa criar o arquivo de configuração phpmussel.yml',
        sugestao: [
            'cp src/Files/phpmussel.yml phpmussel.yml'
        ]
    );
}
if (!is_dir(__DIR__ . '/../../files/phpmussel')) {
    throw new \Erro\Erro(
        mensagem: 'Diretório files/phpmussel não existe',
        titulo: 'Diretório não existe',
        texto: 'Você precisa criar o diretório phpmussel em files',
        sugestao: [
            'mkdir files/phpmussel',
            'mkdir files/phpmussel/assinatura',
            'mkdir files/phpmussel/cache',
            'mkdir files/phpmussel/quarentena',
            'chmod -R 775 files/phpmussel/cache',
            'chown -R www-data:root files/phpmussel/cache',
            'chmod -R 775 files/phpmussel/quarentena',
            'chown -R www-data:root files/phpmussel/quarentena'
        ]
    );
}
if (!is_dir(__DIR__ . '/../../files/phpmussel/assinatura')) {
    throw new \Erro\Erro(
        mensagem: 'Diretório files/phpmussel/assinatura não existe',
        titulo: 'Diretório não existe',
        texto: 'Você precisa criar o diretório assinatura em files/phpmussel',
        sugestao: [
            'mkdir files/phpmussel/assinatura',
            'chmod -R 775 files/phpmussel/assinatura',
            'chown -R www-data:root files/phpmussel/assinatura'
        ]
    );
}
if (!is_dir(__DIR__ . '/../../files/phpmussel/cache')) {
    throw new \Erro\Erro(
        mensagem: 'Diretório files/phpmussel/cache não existe',
        titulo: 'Diretório não existe',
        texto: 'Você precisa criar o diretório cache em files/phpmussel',
        sugestao: [
            'mkdir files/phpmussel/cache',
            'chmod -R 775 files/phpmussel/cache',
            'chown -R www-data:root files/phpmussel/cache'
        ]
    );
}
if (!is_writable(__DIR__ . '/../../files/phpmussel/cache')) {
    throw new \Erro\Erro(
        mensagem: 'Sem permissão no diretório files/phpmussel/cache/',
        titulo: 'Erro de permissão',
        texto: 'Você deve dar permissão de escrita no diretório files/phpmussel/cache/',
        sugestao: [
            'chmod -R 775 files/phpmussel/cache',
            'chown -R www-data:root files/phpmussel/cache'
        ]
    );
}
if (!is_dir(__DIR__ . '/../../files/phpmussel/quarentena')) {
    throw new \Erro\Erro(
        mensagem: 'Diretório files/phpmussel/quarentena não existe',
        titulo: 'Diretório não existe',
        texto: 'Você precisa criar o diretório quarentena em files/phpmussel',
        sugestao: [
            'mkdir files/phpmussel/quarentena',
            'chmod -R 775 files/phpmussel/quarentena',
            'chown -R www-data:root files/phpmussel/quarentena'
        ]
    );
}
if (!is_writable(__DIR__ . '/../../files/phpmussel/quarentena')) {
    throw new \Erro\Erro(
        mensagem: 'Sem permissão no diretório files/phpmussel/quarentena',
        titulo: 'Erro de permissão',
        texto: 'Você deve dar permissão de escrita no diretório files/phpmussel/quarentena',
        sugestao: [
            'chmod -R 775 files/phpmussel/quarentena',
            'chown -R www-data:root files/phpmussel/quarentena'
        ]
    );
}
