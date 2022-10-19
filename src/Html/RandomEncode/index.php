<?php

use Helpers\CryptHelper;

$chave = jsonDecode(file_get_contents("php://input"), retorno: true)['chave'] ?? '';

$Crypt = new CryptHelper(chavePublica: $chave);

echo jsonEncode([
    'nome' => $Crypt->encode(nomeAleatorio()),
    'sobreNome' => $Crypt->encode(sobreNomeAleatorio()),
    'nomeCompleto' => $Crypt->encode(nomeCompletoAleatorio()),
    'telefone' => $Crypt->encode(telefoneAleatorio()),
    'numero' => $Crypt->encode(numeroAleatorio()),
    'telefone_pessoal' => $Crypt->encode(telefoneAleatorio()),
    'telefone_trabalho' => $Crypt->encode(telefoneAleatorio()),
    'email' => $Crypt->encode(emailAleatorio()),
    'email_pessoal' => $Crypt->encode(emailAleatorio()),
    'email_trabalho' => $Crypt->encode(emailAleatorio()),
    'data_passada' => $Crypt->encode(dataPassadaAleatorio()),
    'data_futura' => $Crypt->encode(dataFuturaAleatorio()),
    'cpf' => $Crypt->encode(cpfAleatorio()),
    'cnpj' => $Crypt->encode(cnpjAleatorio()),
    'rg' => $Crypt->encode(rgAleatorio()),
    'login' => $Crypt->encode('01234567890'),
    'senha' => $Crypt->encode('Teste@1324'),
]);
