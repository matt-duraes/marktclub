<?php

$Doc = new \DocumentacaoConfig\Requisicao();
$Doc
    ->titulo('ANALYTICS DOWNLOAD')
    ->descricao('Requisição para fazer o download do dump inicial de um analytics')
    ->status(200)
    ->scope('relatorio_analytics:download')
    ->metodo('post')
    ->uri('/relatorio/analytics-download')

    ->headerToken()

    ->observacao('Seguir descritivo para analisar os dados do dump')

    ->retorno('cpf', 'CPF do usuário com 0 a esquerda para fechar os 11 digitos')
    ->retorno('usuario_tipo', '1 para titular ou 2 para dependente')
    ->retorno('dispositivo', 'Tipo de dispositivo podendo ser Desktop, Mobile Phone ou APP')
    ->retorno('os', 'Sistema operacional usado')
    ->retorno('browser', 'Browser usado para acessar')
    ->retorno('versao', 'Versão do navegador')
    ->retorno('mobile', '1 caso o dispositivo seja um celular')
    ->retorno('tablet', '1 caso o dispositivo seja um tablet')
    ->retorno('data_criacao', 'Data que o registro foi criado')
    ->retorno('url', 'URL acessada')

    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("curl --location --request POST '{{LINK}}/relatorio/analytics-download' \
--header 'Authorization: Bearer {{TOKEN}}'")
    ->preFalha();

echo $Doc;
