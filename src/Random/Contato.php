<?php

namespace Random;

trait Contato
{
    public function email(): string
    {
        $dominio = [
            'gmail.com',
            'outlook.com',
            'marktclub.com.br',
            'zip.com.br',
            'adp.com.br',
            'uol.com.br',
            'gzip.com',
            'meuemail.com.br',
            'godaddy.com',
            'max.com.br',
            'globo.com',
            'temmais.com.br',
            'convenio.com.br'
        ];

        return $this->pegarNomeAleatorio(rand(1, 2)) . '.' . rand(1930, date('Y')) . '@' . $dominio[rand(
            0,
            count($dominio) - 1
        )];
    }

    private function pegarNomeAleatorio($quantidade): string
    {
        $nome = [
            'andre',
            'rodrigues',
            'gasto',
            'financeiro',
            'pereira',
            'rodrigues',
            'santos',
            'geraldo',
            'luiz',
            'luis',
            'marcos',
            'mario',
            'roberto',
            'karina',
            'paulo',
            'matheus',
            'breno',
            'alencar',
            'antonio',
            'alberto',
            'ademar',
            'carlos',
            'dado',
            'data',
            'comercial',
            'ti',
            'mais',
            'tiao',
            'palmeiras',
            'arno',
            'carderno',
            'camera',
            'tia',
            'irmao',
            'pai',
            'sr',
            'master',
            'web',
            'contato'
        ];
        $quantidadeNome = count($nome) - 1;

        $nomeFinal = [];
        for ($i = 0; $i < $quantidade; $i++) {
            $nomeFinal[] = $nome[rand(0, $quantidadeNome)];
        }
        return implode('.', $nomeFinal);
    }

    public function telefone(): string
    {
        $numero = rand(0, 1);
        if ($numero == 1) {
            return $this->telefoneCelular();
        }
        return $this->telefoneFixo();
    }

    public function telefoneCelular(): string
    {
        return $this->ddd() . '9' . rand(10000000, 99999999);
    }

    public function ddd(): int
    {
        $ddd = [
            61,
            62,
            64,
            65,
            66,
            67,
            82,
            71,
            73,
            74,
            75,
            77,
            85,
            88,
            98,
            99,
            83,
            81,
            87,
            86,
            89,
            84,
            79,
            68,
            96,
            92,
            97,
            91,
            93,
            94,
            69,
            95,
            63,
            27,
            28,
            31,
            32,
            33,
            34,
            35,
            37,
            38,
            21,
            22,
            24,
            11,
            12,
            13,
            14,
            15,
            16,
            17,
            18,
            19,
            41,
            42,
            43,
            44,
            45,
            46,
            51,
            53,
            54,
            55,
            47,
            48,
            49
        ];
        return $ddd[rand(0, count($ddd) - 1)];
    }

    public function telefoneFixo(): string
    {
        return $this->ddd() . rand(3200, 3399) . rand(1000, 9999);
    }

    public function ddi(): int
    {
        return rand(1, 998);
    }
}
