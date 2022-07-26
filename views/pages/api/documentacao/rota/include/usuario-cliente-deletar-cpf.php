<?php

$Doc = new \DocumentacaoConfig\Requisicao;
$Doc
    ->titulo('DELETAR USUÁRIO')
    ->descricao('Requisição para deletar um usuário que já existe via CPF.')
    ->status(204)
    ->scope('usuario_cliente:deletar_cpf')
    ->metodo('post')
    ->uri('/usuario-cliente/deletar')

    ->headerToken()

    ->criptografar(['cpf'])
    ->body('cpf', '01234567890', 'CPF do usuário que deseja deletar.', 'string', obrigatorio: true)

    ->observacao('Por mais que a rota use um POST, o retorno de sucesso é um status 204 vazio como se a rota fosse um DELETE.')

    ->erro(404, 'Usuário que deseja deletar não existe ou URI inválida.')
    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("curl --location --request POST '{{LINK}}/usuario-cliente/deletar' \
--header 'Authorization: Bearer {{TOKEN}}' \
--form 'cpf=\"PJ8rt0CbS9a3mfvUVnXZ7hN9WDU96w==\"'")
    ->preFalha("");

echo $Doc;
