<?php

$Doc = new \DocumentacaoConfig\Requisicao;
$Doc
    ->titulo('ANALYTICS LISTAR')
    ->descricao('Requisição feita para listar os acessos de um intervalo entre determinada datas')
    ->status(200)
    ->scope('relatorio_analytics:listar')
    ->metodo('get')
    ->uri('/relatorio/analytics')

    ->headerToken()

    ->parametro('de', date('Y-m-d'), 'Data de começo da busca', 'string', obrigatorio: true)
    ->parametro('ate', date('Y-m-d'), 'Data final da busca', 'string', obrigatorio: true)

    ->retorno('usuario_tipo', 'Tipo do usuário podendo ser titular ou dependente')
    ->retorno('cpf', 'CPF do usuário criptografado')
    ->retorno('dispositivo', 'Tipo de dispositivo podendo ser Desktop, Mobile Phone ou APP')
    ->retorno('os', 'Sistema operacional usado')
    ->retorno('browser', 'Browser usado para acessar')
    ->retorno('versao', 'Versão do navegador')
    ->retorno('mobile', 'Se o dispositivo é um celular')
    ->retorno('tablet', 'Se o dispositivo é um tablet')
    ->retorno('url', 'URL acessada')

    ->observacao('A diferença entre as datas da busca deve ser de no máximo 7 dias')

    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("curl --location --request GET '{{LINK}}/relatorio/analytics?de=2022-07-11&ate=2022-07-18' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {{TOKEN}}'")
    ->preSucesso("{
    \"status\": \"sucesso\",
    \"dado\": [
        {
            \"id\": \"21e420a7-4570-4e11-bfd3-3d72d1670c0d\",
            \"usuario_tipo\": \"titular\",
            \"cpf\": \"KSTPtlCfv1AhBkLf6I4cPC0w==\",
            \"dispositivo\": \"Mobile Phone\",
            \"os\": \"IOS\",
            \"browser\": \"Chrome\",
            \"versao\": \"100.0\",
            \"mobile\": true,
            \"tablet\": false,
            \"data\": \"2022-07-11 00:00:00\",
            \"url\": \"/convenios/fisk\"
        },
        {
            \"id\": \"f654a7e0-aa26-43c8-b048-c9338521fde2\",
            \"usuario_tipo\": \"dependente\",
            \"cpf\": \"U97jpqCknqRnNwlfz1hdiXENCAS+daGtg==\",
            \"dispositivo\": \"Desktop\",
            \"os\": \"Linux\",
            \"browser\": \"Firefox\",
            \"versao\": \"102.3\",
            \"mobile\": false,
            \"tablet\": false,
            \"data\": \"2022-07-11 00:00:00\",
            \"url\": \"/promocoes\"
        }
    ]
}")
    ->preFalha();

echo $Doc;
