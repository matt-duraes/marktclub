<?php

$Doc = new \DocumentacaoConfig\Requisicao();
$Doc
    ->titulo('TOKEN')
    ->descricao('Requisição para criar um token, toda rota dessa API deve ter um token para receber permissão de acesso e esse token deve ser criado aqui. Após o token criado, você pode salvá-lo e usar mais de uma vez até seu vencimento.')
    ->status(201)
    ->metodo('post')
    ->uri('/token')
    ->body('client_id', '1234567890', 'Código público', 'string', 22, true)
    ->body('secret_id', '1234567890', 'Código privado', 'string', 22, true)
    ->body('audience', 'web', 'Audiencia do token', 'string', obrigatorio: true)
    ->body('grant_type', 'client_credentials', 'Tipo do token que deseja criar', 'string', obrigatorio: true)
    ->body('scope', 'scope:teste01 scope:teste02', 'Quais scopes esse token terá acesso, se for mais de um, separe por espaço.', 'string')

    ->observacao('Não deixe sua secret_id transitar em ambiente público, com ela, um terceiro pode manipular as informações da sua base.')
    ->observacao('Cada token criado tem uma duração determinada, por isso, você pode usar o mesmo token mais de uma vez nesse intervalo desde que ele tenha permissão para executar a ação desejada.')
    ->observacao('Você não é obrigado a passar o scope, uma vez que não passar, esse token vai funcionar para todos os scopes que ele tem permissão, isso pode facilitar o roubo de informações sensíveis, para uma melhor segurança, sempre passe o scope.')
    ->observacao('Tokens com token_type de valor Bearer devem ser enviados com o tipo antes do token nas requisições ou seja, no header Authorization deve ficar: Bearer token_aqui.')

    ->erro400()
    ->erro(403, 'Você não teve permissão para criar o token, geralmente isso ocorre porque um ou mais parâmetros do body estão incorretos.')

    ->retorno('access_token', 'Token que deve ser usado nas demais rotas.')
    ->retorno('scope', 'Lista de scopes que esse token tem acesso.')
    ->retorno('expires_in', 'Quantidade de segundos que esse token tem de vida.')
    ->retorno('token_type', 'Tipo de token criado.')

    ->preExemplo("curl --location --request POST '{{LINK}}/token' \
--form 'client_id=\"client_id_aqui\"' \
--form 'secret_id=\"secret_id_aqui\"' \
--form 'audience=\"web\"' \
--form 'grant_type=\"client_credentials\"' \
--form 'scope=\"scope:teste01 scope:teste02 scope:teste03\"'")
    ->preSucesso("{
    \"status\": \"sucesso\",
    \"dado\": {
        \"access_token\": \"token_que_deve_ser_usado\",
        \"scope\": \"scopes\",
        \"expires_in\": \"tempo_de_vida\",
        \"token_type\": \"Bearer\"
    }
}")
    ->preFalha();

echo $Doc;
