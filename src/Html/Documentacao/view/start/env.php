<?php

$Doc = new DocumentacaoConfig\Fw('ARQUIVO ENV', 'O sistema lê os arquivos .env para fazer as configurações, você pode ter vários arquivos env e o sistema irá saber qual usar dependendo da URL do projeto.');

$Doc
    ->tabela(function () use ($Doc) {
        $Doc
            ->tr(['ENV', 'Descrição'])
            ->tr(['APP_TITULO', 'Título do sistema'])
            ->tr(['APP_DESCRICAO', 'Descrição do sistema'])
            ->tr(['APP_VERSAO', 'Versão do sistema atual'])
            ->tr(['APP_URL', 'URL do sistema, essa é a URL onde o sistema identifica qual o arquivo irá ser usado'])
            ->tr(['APP_CACHE', 'Cache do sistema'])
            ->tr(['APP_DEBUG', 'Se true, libera o debug do PHP, em produção deixar false'])
            ->tr(['APP_TIMEZONE', 'Timezone do sistema'])
            ->tr(['APP_TIPO', 'Tipo do sistema, podendo ser producao, homologacao ou localhost'])

            ->tr(['DEV_EDITOR', 'Qual editar está usando'])
            ->tr(['DEV_WORKSPACE', 'O diretório do sistema'])
            ->tr(['DEV_LOCAL_IP', 'IP do localhost'])
            ->tr(['DEV_DOCUMENTO', 'CPF do DEV'])

            ->tr(['APP_LOGIN', 'Login para bloquear o sistema'])
            ->tr(['APP_SENHA', 'Senha para bloquear o sistema'])

            ->tr(['PERMISSION_POLICY', 'Valor para o header Permissions-Policy'])
            ->tr(['SECURITY_POLICY', 'Valor para o header Content-Security-Policy'])

            ->tr(['DIRETORIO_VIEW', 'Diretório do public'])
            ->tr(['DIRETORIO_PRIVADO', 'Diretório para os arquivos privados'])
            ->tr(['DIRETORIO_PUBLICO', 'Diretório para os arquivos públicos'])

            ->tr(['CRYPT_HASH', 'Hash para a criptografia'])

            ->tr(['ROTA_PRINCIPAL', 'Rota principal do sistema'])
            ->tr(['ROTA_BLOQUEADA', 'Rotas bloqueadas do sistema'])

            ->tr(['SESSION_DIRETORIO', 'Diretório para as sessões do PHP, deixar vazio para usar a padrão'])
            ->tr(['SESSION_CACHE', 'O tempo em segundos do cache da sessão'])
            ->tr(['SESSION_PRIVACIDADE', 'Tipo da privacidade do session_cache_limiter'])
            ->tr(['SESSION_SAMESITE', 'Valor para o cookie_samesite'])

            ->tr(['INI_LISTA', 'Lista para setar as ini do PHP, enviar um json ind => val'])

            ->tr(['API_LINK', 'Url interna da API, usada apenas em local, geralmente 127.0.0.1'])
            ->tr(['API_CLIENT_ID', 'Client_id do App da api'])
            ->tr(['API_CLIENT_SECRET', 'Client_secret do App da api'])
            ->tr(['API_AUDIENCE', 'Audience do App da api'])

            ->tr(['DB_ESCRITA', 'Host do banco de dados, no local usar mysql'])
            ->tr(['DB_PORTA', 'Porta do Banco de dados, deixar opcional para usar a padrão'])
            ->tr(['DB_BANCO', 'Nome do banco de dados'])
            ->tr(['DB_USUARIO', 'Usuário do banco de dados'])
            ->tr(['DB_SENHA', 'Senha do banco de dados'])

            ->tr(['LINK', 'Link geral'])
            ->tr(['LINK_SITE', 'Link do site'])
            ->tr(['LINK_PADRAO', 'Link padrão do sistema, sem /diretorio'])
            ->tr(['LINK_PAINEL', 'Link do painel'])
            ->tr(['LINK_API', 'Link da API'])
            ->tr(['LINK_ARQUIVO', 'Link do arquivo dentro do diretório public'])
            ->tr(['LINK_ARQUIVO_PRIVADO', 'Link interno para arquivos privados'])
            ->tr(['LINK_ARQUIVO_PUBLICO', 'Link interno para arquivos públicos'])
            ->tr(['LINK_LOCAL', 'Link para local'])

            ->tr(['MAIL_HOST', 'Host do e-mail'])
            ->tr(['MAIL_PORTA', 'Porta do e-mail'])
            ->tr(['MAIL_USUARIO', 'Usuário do e-mail'])
            ->tr(['MAIL_SENHA', 'Senha do e-mail'])
            ->tr(['MAIL_DEBUG', 'Se o debug vai ficar liberado'])
            ->tr(['MAIL_ENVIO', 'Json com nome/email do endereço de envio'])
            ->tr(['MAIL_RESPOSTA', 'Json com nome/email do endereço de resposta'])
            ->tr(['MAIL_EMAIL', 'Json com lista de email do sistema'])
            ->tr(['MAIL_SENDGRID', 'Chave do sendgrid'])

            ->tr(['RECAPTCHA_SCORE', 'Nota para o score do recaptcha'])
            ->tr(['RECAPTCHA_PUBLIC', 'Chave pública do recaptcha'])
            ->tr(['RECAPTCHA_SECRET', 'Chave privada do recaptcha'])

            ->tr(['FACEBOOK_APP_ID', 'App Id do app do Facebook'])
            ->tr(['FACEBOOK_APP_SECRET', 'App Secret do app do Facebook'])

            ->tr(['GOOGLE_CLIENT_ID', 'Client id do app do Google'])
            ->tr(['GOOGLE_API_KEY', 'Api key do app do Google']);
    })

    ->paragrafo('Alguns envs criam defines padrões que são: TITULO, DESCRICAO, CACHE, DIRETORIO_PRIVADO, DIRETORIO_PUBLICO, LINK, LINK_SITE, LINK_PAINEL, LINK_API, LINK_ARQUIVO, LINK_ARQUIVO_PUBLICO, LINK_ARQUIVO_PRIVADO e LINK_LOCAL')
    ->paragrafo('Você pode chamar essa define em qualquer lugar do sistema, também pode usar o coringa {{LINK}} nos envs de LINK, ele irá fazer um replace do link padrão, por exemplo:')
    ->codigo('LINK=https://localhost:433' . PHP_EOL . 'LINK_API={{LINK}}/api')
    ->paragrafo('Essa configuração irá resultar em:')
    ->codigo('echo LINK; // https://localhost:433' . PHP_EOL . 'echo LINK_API; // https://localhost:433/api')
    ->paragrafo('Para pegar o valor de algum env, basta usar a função env:')
    ->codigo('$recaptchaScore = env("RECAPTCHA_SCORE", "valor_padrao");')

    ->paragrafo('Como falado anteriormente, o sistema pode ter vários envs, aqui vou mostrar um exemplo com 2 envs, o primeiro, será o site https://dominio.com.br e o segundo sua api em https://api.dominio.com.br. Cada env será setado pelo APP_URL, lembrando que o env principal (.env) será usado sempre que uma opção no existir no env "local".')

    ->paragrafo('Código do env do site:')
    ->codigo('APP_TITULO=Site de teste
APP_URL=dominio.com.br

ROTA_PRINCIPAL=site
ROTA_BLOQUEADA=api')

    ->paragrafo('Código do env da api:')
    ->codigo('APP_TITULO=Api de teste
APP_URL=api.dominio.com.br

ROTA_PRINCIPAL=api
ROTA_BLOQUEADA=site');

echo $Doc;
