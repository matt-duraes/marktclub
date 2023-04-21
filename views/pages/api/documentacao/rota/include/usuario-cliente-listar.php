<?php

use App\Classes\UsuarioCliente\Helper;

$Doc = new \DocumentacaoConfig\Requisicao();
$Doc
    ->titulo('LISTAR USUÁRIOS')
    ->descricao('Requisição para buscar uma lista de usuários.')
    ->status(200)
    ->scope('usuario_cliente:listar')
    ->metodo('get')
    ->uri('/usuario-cliente')

    ->headerToken()
    ->headerJson()

    ->criptografar(Helper::CRIPTOGRAFAR)

    ->raw('pagina', '1', 'Número da página que deseja buscar', 'int', obrigatorio: true)
    ->raw('ordem', 'nome-a-z', 'Ordem que deseja colocar os resultados. Valores padrões: nome-a-z, nome-a-z, mais-novo ou mais-velho', 'string')
    ->raw('nome', 'Nome do Usuário', 'Nome do usuário que deseja buscar', 'string')
    ->raw('email', 'email@dominio.com.br', 'E-mail do usuário que deseja buscar', 'string')
    ->raw('cpf', '01234567890', 'CPF do usuário que deseja buscar', 'int')

    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("curl --location --request GET '{{LINK}}/usuario-cliente' \
--header 'Content-Type: application/json' \
--header 'Authorization: Bearer {{TOKEN}}' \
--data-raw '{
    \"pagina\": 1,
    \"ordem\": \"nome-a-z\"
}'")
    ->preSucesso("{
    \"status\": \"sucesso\",
    \"dado\": {
        \"lista\": [
            {
                \"id\": \"7f10e270-d695-418e-bc21-a2dfa3d24352\",
                \"nome\": \"det4I78ZqeI1ilwC0EqFQ1404w81SRknJfJf2el0MXhfdLjwI9GefBjfbg==\",
                \"cpf\": \"u4zI78ZqeI1ilwC0EqFQ1404w81SRknJfJf2ZSU+p+el0MXhfdLjwI9GefBjfbg==\",
                \"email\": \"det4I78ZqeI1ilwC0EqFQ1404w81SRknJfJf2el0MXhfdLjwI9GefBjfbg==\",
                \"data_criacao\": \"2018-09-28 15:45:27\",
                \"status\": \"ativo\"
            }
        ]
    }
}")
    ->preFalha("");

echo $Doc;
