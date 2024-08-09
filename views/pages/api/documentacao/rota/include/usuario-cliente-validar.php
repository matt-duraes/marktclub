<?php

$Doc = new \DocumentacaoConfig\Requisicao();
$Doc
    ->titulo('VALIDAR SE CPF EXISTE')
    ->descricao('Requisição para validar se um CPF existe e está apto no sistema.')
    ->status(200)
    ->scope('usuario_cliente:validar')
    ->metodo('post')
    ->uri('/usuario-cliente/validar')

    ->headerToken()

    ->body('cpf', '01234567890', 'CPF do usuário contendo apenas numeros', 'int', 11, obrigatorio: true)

    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("curl --location --request POST '{{LINK}}/usuario-cliente/validar' \
--header 'Authorization: Bearer {{TOKEN}}' \
--form 'cpf=\"01234567890\"'")
    ->pre('Usuário existe', '{
    "status": "sucesso",
    "dado": {
        "empresa": "e75ad922-649c-4263-a643-35755ede1ee0",
        "cpf": "01234567890",
        "status": "LIBERADO"
    }
}')
    ->pre('Usuário não existe:', '{
    "status": "erro",
    "erro": {
        "empresa": "",
        "cpf": "01234567890",
        "status": "RECUSADO"
    }
}')
    ->preFalha('{
    "status": "erro",
    "erro": {
        "titulo": "Campo inválido!",
        "mensagem": "O campo CPF está inválido."
    }
}');

echo $Doc;
