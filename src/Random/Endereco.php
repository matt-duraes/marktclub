<?php

namespace Random;

trait Endereco
{
    public function estado(): string
    {
        return [
            'AC',
            'AL',
            'AP',
            'AM',
            'BA',
            'CE',
            'DF',
            'ES',
            'GO',
            'MA',
            'MT',
            'MS',
            'MG',
            'PA',
            'PB',
            'PR',
            'PE',
            'PI',
            'RJ',
            'RN',
            'RS',
            'RO',
            'RR',
            'SC',
            'SP',
            'SE',
            'TO',
        ][rand(0, 26)] ?? 'DF';
    }

    public function cep(): int
    {
        return rand(10000000, 99999999);
    }

    public function cidade(string $estado = 'SP')
    {
        $ch = curl_init();
        curl_setopt(
            $ch,
            CURLOPT_URL,
            'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' . $estado . '/distritos'
        );
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $retorno = curl_exec($ch);
        $retorno = is_string($retorno) && !empty($retorno) ? json_decode($retorno, true) : [];
        curl_close($ch);

        if (empty($retorno)) {
            return 'Brasília';
        }

        $numero = rand(0, count($retorno) - 1);
        return $retorno[$numero]['nome'] ?? 'Brasília';
    }

    public function bairro(): string
    {
        $bairro = [
            'Park Way',
            'Vila Nova',
            'Centro',
            'Campo Limpo',
            'Chácara Machado',
            'Cidade Jardim',
            'Recanto das Emas',
            'Vila Jonas',
            'Parque Industrial',
            'Vila Brasil',
            'Água Bonita',
            'Jardim das Águas'
        ];
        return $bairro[rand(0, count($bairro) - 1)];
    }

    public function logradouro(): string
    {
        $logradouro = [
            'Quadra 01 Conjunto 10',
            'Quadra 02 Conjunto 03',
            'Quadra 03 Conjunto 03',
            'Quadra 04 Conjunto 03',
            'Avenida Joaquim Aires',
            'Avenida Martins Pena',
            'Avenida Beira Rio',
            'Rua 10',
            'Rua 20',
            'Rua 01',
            'Rua Joaquim Castro',
            'Rua Maria Pera',
            'SIG Quadra 01'
        ];
        return $logradouro[rand(0, count($logradouro) - 1)];
    }

    public function complemento(): string
    {
        $complemento = [
            'Residencial Renascer',
            'Edifício Platinum Office',
            'Residencial Águas Claras',
            'Residencial Dois Irmões',
            'Residencial Ilha Bela',
            'Edifício Multiempresarial',
            'Edifício Impar'
        ];
        return $complemento[rand(0, count($complemento) - 1)];
    }

    public function numero(int $de = 1, int $ate = 9999): int
    {
        return rand($de, $ate);
    }
}
