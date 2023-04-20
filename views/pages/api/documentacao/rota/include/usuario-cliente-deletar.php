<?php

$Doc = new \DocumentacaoConfig\Requisicao();
$Doc
    ->titulo('DELETAR USUÁRIO')
    ->descricao('Requisição para deletar um usuário que já existe.')
    ->status(204)
    ->scope('usuario_cliente:deletar')
    ->metodo('delete')
    ->uri('/usuario-cliente/:id')

    ->headerToken()

    ->observacao('O CPF da URI deve ser criptografado.')

    ->erro(404, 'Usuário que deseja deletar não existe ou URI inválida.')
    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("curl --location --request DELETE '{{LINK}}/usuario-cliente/:id' \
--header 'Authorization: Bearer {{TOKEN}}'")
    ->preFalha("");

echo $Doc;
