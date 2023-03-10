<?php

use Helpers\CryptHelper;

$chave = jsonDecode(file_get_contents("php://input"), retorno: true)['chave'] ?? '';

$Crypt = new CryptHelper(chavePublica: $chave);

$nome = env('POSTMAN_NOME', '');
$sobreNome = env('POSTMAN_SOBRENOME', '');
$nomeCompleto = env('POSTMAN_NOME_COMPLETO', '');
$numero = env('POSTMAN_NUMERO', '');
$telefone = env('POSTMAN_TELEFONE', '');
$email = env('POSTMAN_EMAIL', '');
$data = env('POSTMAN_DATA', '');
$cpf = env('POSTMAN_CPF', '');
$cnpj = env('POSTMAN_CNPJ', '');
$rg = env('POSTMAN_RG', '');
$login = env('POSTMAN_LOGIN', '');
$senha = env('POSTMAN_SENHA', '');

echo jsonEncode([
    'nome' => $Crypt->encode(!empty($nome) ? $nome : nomeAleatorio()),
    'sobreNome' => $Crypt->encode(!empty($sobreNome) ? $sobreNome : sobreNomeAleatorio()),
    'nomeCompleto' => $Crypt->encode(!empty($nomeCompleto) ? $nomeCompleto : nomeCompletoAleatorio()),
    'numero' => $Crypt->encode(!empty($numero) ? $numero : numeroAleatorio()),
    'telefone' => $Crypt->encode(!empty($telefone) ? $telefone : telefoneAleatorio()),
    'email' => $Crypt->encode(!empty($email) ? $email : emailAleatorio()),
    'data' => $Crypt->encode(!empty($data) ? $data : date('Y-m-d')),
    'data_passada' => $Crypt->encode(dataPassadaAleatorio()),
    'data_futura' => $Crypt->encode(dataFuturaAleatorio()),
    'cpf' => $Crypt->encode(!empty($cpf) ? $cpf : cpfAleatorio()),
    'cnpj' => $Crypt->encode(!empty($cnpj) ? $cnpj : cnpjAleatorio()),
    'rg' => $Crypt->encode(!empty($rg) ? $rg : rgAleatorio()),
    'login' => $Crypt->encode(!empty($login) ? $login : '01234567890'),
    'senha' => $Crypt->encode(!empty($senha) ? $senha : 'Teste@1324'),
    'api_link' => env('POSTMAN_API_LINK', ''),
    'api_client_id' => env('POSTMAN_API_CLIENT_ID', ''),
    'api_secret_id' => env('POSTMAN_API_SECRET_ID', ''),
    'api_audience' => env('POSTMAN_API_AUDIENCE', ''),
]);
