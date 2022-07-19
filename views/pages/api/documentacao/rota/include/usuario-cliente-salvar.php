<?php

use App\Classes\UsuarioCliente\Helper;

$Doc = new \DocumentacaoConfig\Requisicao;
$Doc
    ->titulo('SALVAR NOVO USUÁRIO')
    ->descricao('Requisição para salvar um novo usuário.')
    ->status(201)
    ->scope('usuario_cliente:salvar')
    ->metodo('post')
    ->uri('/usuario-cliente')

    ->headerToken()

    ->criptografar(Helper::CRIPTOGRAFAR)

    ->body('nome', 'João Rodrigues', 'Nome e Sobre nome do usuário', 'string', obrigatorio: true)
    ->body('cpf', '01234567890', 'CPF do usuário contendo apenas numeros', 'int', 11, obrigatorio: true)
    ->body('email_trabalho', 'email@dominio.com.br', 'E-mail de trabalho.', 'string', obrigatorio: '-')
    ->body('email_pessoal', 'email@dominio.com.br', 'E-mail pessoal.', 'string', obrigatorio: '-')
    ->body('telefone_trabalho', '61911112222', 'Telefone de trabalho contendo apenas numeros.', 'int')
    ->body('telefone_pessoal', '61933334444', 'Telefone pessoal contendo apenas numeros.', 'int')
    ->body('matricula', '1', 'Matrícula do usuário', 'int')
    ->body('siape', '1', 'SIAPE do usuário', 'int')
    ->body('genero', 'masculino', 'Genero podendo ser: masculino, feminino, outro ou nao-informar.', 'string')
    ->body('estado_civil', 'casado', 'Estado civil podendo ser solteiro, casado, divorciado, viuvo ou separado.', 'string')
    ->body('data_nascimento', '2015-09-17', 'Data de nascimento no formato YYYY-MM-DD.', 'string', 10)
    ->body('endereco_cep', '70000000', 'CEP da residencia.', 'int', 8)
    ->body('endereco_logradouro', 'Rua 22', 'Logradouro do endereço.', 'string')
    ->body('endereco_numero', '10', 'Número do endereço.', 'int')
    ->body('endereco_complemento', 'Casa E', 'Complemento do endereço.', 'string')
    ->body('endereco_bairro', 'Centro', 'Bairro do endereço.', 'string')
    ->body('endereco_cidade', 'Brasília', 'Cidade da residencia.', 'string')
    ->body('endereco_estado', 'DF', 'UF da residencia.', 'string', 2)
    ->body('mudar_senha', 'nao', 'Se o usuário deve mudar a senha podendo ser sim ou nao.', 'string', 3)
    ->body('primeiro_acesso', 'nao', 'Se é o primeiro acesso do usuário podendo ser sim ou nao.', 'string', 3)
    ->body('status', 'ativo', 'Status podendo ser ativo, inativo ou bloqueado.', 'string')

    ->observacao('Você deve passar o e-mail de trabalho ou e-mail pessoal do usuário.')
    ->observacao('Você não precisa enviar todos os campos, pode enviar apenas os campos que deseja salvar, menos os obrigatórios.')

    ->erro400()
    ->erro401()
    ->erro403()

    ->preExemplo("url --location --request POST '{{LINK}}/usuario-cliente' \
--header 'Authorization: Bearer {{TOKEN}}' \
--form 'nome=\"{{hash}}\"' \
--form 'email_trabalho=\"{{hash}}\"' \
--form 'cpf=\"{{hash}}\"' \
--form 'status=\"{{hash}}\"' \
--form 'primeiro_acesso=\"{{hash}}\"'")
    ->preSucesso("")
    ->preFalha("{
    \"status\": \"erro\",
    \"erro\": {
        \"titulo\": \"Campo obrigatório!\",
        \"mensagem\": \"O campo nome é obrigatório.\"
    }
}");

echo $Doc;
