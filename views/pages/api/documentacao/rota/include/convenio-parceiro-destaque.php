<?php

$Doc = new \DocumentacaoConfig\Requisicao;
$Doc
    ->titulo('LISTAR PARCEIROS EM DESTAQUE')
    ->descricao('Requisição para fazer a busca das informações básicas dos parceiros em destaque')
    ->status(200)
    ->scope('convenio-parceiro:destaque')
    ->metodo('get')
    ->uri('/convenio-parceiro/destaque')

    ->headerToken()

    ->parametro('categoria', 'alimentacao', 'Indice da categoria podendo ser: alimentacao, beleza, educacao, eletroeletronico, outros, saude, veiculo ou vestuario', 'string')
    ->parametro('quantidade', '10', 'Quantidade de registros para a busca podendo ser no máximo 20', 'int', obrigatorio: true)
    ->parametro('ordem', 'randomico', 'Ordem em que os registros devem ser mostrados podendo ser: titulo-a-z, titulo-z-a, mais-novo, mais-velho ou randomico', 'string', obrigatorio: true)

    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("curl --location --request GET '{{LINK}}/convenio-parceiro/destaque?categoria=alimentacao&quantidade=20&ordem=titulo-z-a' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {{TOKEN}}'")
    ->preSucesso("{
    \"status\": \"sucesso\",
    \"dado\": {
        \"lista\": [
            {
                \"id\": \"430d03455f8a53fd2f5c3e45e9e06154\",
                \"titulo\": \"Parceiro teste 04\",
                \"url\": \"parceiro-teste-04\",
                \"imagem\": \"http://localhost.com:8000/convenios/parceiro.png\",
                \"categoria\": \"saude\",
                \"desconto\": \"10% de desconto\"
            },
            {
                \"id\": \"86e1b9af92abbbab7a733c3b4bfea5ae\",
                \"titulo\": \"Parceiro teste 03\",
                \"url\": \"parceiro-teste-03\",
                \"imagem\": \"http://localhost.com:8000/convenios/parceiro.png\",
                \"categoria\": \"alimentacao\",
                \"desconto\": \"10% de desconto\"
            }
        ]
    }
}")
    ->preFalha();

echo $Doc;
