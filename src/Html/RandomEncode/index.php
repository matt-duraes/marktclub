<?php

use Helpers\CryptHelper;

$chave = jsonDecode(file_get_contents("php://input"), retorno: true)['chave'] ?? '';

$Crypt = new CryptHelper(chavePublica: $chave);

echo jsonEncode([
    'nome' => $Crypt->encode(env('POSTMAN_NOME', nomeAleatorio())),
    'sobreNome' => $Crypt->encode(env('POSTMAN_SOBRENOME', sobreNomeAleatorio())),
    'nomeCompleto' => $Crypt->encode(env('POSTMAN_NOME_COMPLETO', nomeCompletoAleatorio())),
    'numero' => $Crypt->encode(env('POSTMAN_NUMERO', numeroAleatorio())),
    'telefone' => $Crypt->encode(env('POSTMAN_TELEFONE', telefoneAleatorio())),
    'email' => $Crypt->encode(env('POSTMAN_EMAIL', emailAleatorio())),
    'data' => $Crypt->encode(env('POSTMAN_DATA', date('Y-m-d'))),
    'data_passada' => $Crypt->encode(dataPassadaAleatorio()),
    'data_futura' => $Crypt->encode(dataFuturaAleatorio()),
    'cpf' => $Crypt->encode(env('POSTMAN_CPF', cpfAleatorio())),
    'cnpj' => $Crypt->encode(env('POSTMAN_CNPJ', cnpjAleatorio())),
    'rg' => $Crypt->encode(env('POSTMAN_RG', rgAleatorio())),
    'login' => $Crypt->encode(env('POSTMAN_LOGIN', '01234567890')),
    'senha' => $Crypt->encode(env('POSTMAN_SENHA', 'Teste@1324')),
]);
