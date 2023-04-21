<?php

$Doc = new \DocumentacaoConfig\Requisicao();
$Doc
    ->titulo('LOGIN VIA API')
    ->descricao('Requisição para fazer login no clube via API, todos os dados dessa requisição devem ser criptografados, para saber mais, acesse o menu de chaves da API')
    ->status(201)
    ->scope('login:api')
    ->metodo('post')
    ->uri('/login/api')

    ->headerToken()

    ->criptografar(true)
    ->body('nome', 'João Rodrigues', 'Nome e Sobre nome do usuário', 'string', obrigatorio: true)
    ->body('cpf', '01234567890', 'CPF do usuário contendo apenas numeros', 'int', 11, obrigatorio: true)
    ->body('email_trabalho', 'email@dominio.com.br', 'E-mail de trabalho.', 'string', obrigatorio: '-')
    ->body('email_pessoal', 'email@dominio.com.br', 'E-mail pessoal.', 'string', obrigatorio: '-')
    ->body('matricula', '1', 'Matrícula do usuário', 'int')
    ->body('siape', '1', 'SIAPE do usuário', 'int')
    ->body('genero', 'masculino', 'Genero podendo ser: masculino, feminino, outro ou nao-informar.', 'string')
    ->body('estado_civil', 'casado', 'Estado civil podendo ser solteiro, casado, divorciado, viuvo ou separado.', 'string')
    ->body('data_nascimento', '2015-09-17', 'Data de nascimento no formato YYYY-MM-DD.', 'string', 10)
    ->body('telefone_trabalho', '61933334444', 'Telefone de trabalho contendo apenas numeros.', 'int')
    ->body('telefone_pessoal', '61911112222', 'Telefone pessoal contendo apenas numeros.', 'int')
    ->body('endereco_estado', 'DF', 'UF da residencia.', 'string', 2)
    ->body('endereco_cidade', 'Brasília', 'Cidade da residencia.', 'string')
    ->body('grupo', '', 'Campo livre para identificar o grupo do usuário.', 'string')

    ->observacao('Você deve passar o e-mail de trabalho ou e-mail pessoal do usuário.')
    ->observacao('Em caso de sucesso, basta você redirecionar o usuário para o link informado na resposta no índice "dado.link" que o usuário irá cair logado no clube.')

    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("curl --location --request POST '{{LINK}}/login/api' \
--header 'Authorization: Bearer {{TOKEN}}' \
--form 'nome=\"{{hash}}\"' \
--form 'cpf=\"{{hash}}\"' \
--form 'data_nascimento=\"{{hash}}\"' \
--form 'email_pessoal=\"{{hash}}\"' \
--form 'telefone_pessoal=\"{{hash}}\"'")
    ->preSucesso("{
    \"status\": \"sucesso\",
    \"dado\": {
        \"link\": \"https://link_do_clube.com.br/login/api/b6590c38-3ad3-43af-98d7-f514a1b73c85\"
    }
}")
    ->preFalha();

echo $Doc;
