<?php

namespace Random;

trait Contato
{
    public function email(): string
    {
        $dominio = [
            'ficticioemail.com',
            'mailficticio.net',
            'emailcorp.org',
            'cybermail.biz',
            'techmailpro.com',
            'fictiocommunications.net',
            'virtualmailgroup.org',
            'imaginemail.net',
            'digitalmailtech.com',
            'futuremailco.com',
            'unicorptechemail.net',
            'innovativemail.org',
            'dreamemailinc.com',
            'virtualtechmail.net',
            'techsolutionsmail.org',
            'cybercommmail.com',
            'cloudmailpro.net',
            'quantumemailtech.com',
            'infomailgroup.org',
            'wizardmail.net',
            'ecomailpro.com',
            'imaginatemail.org',
            'digitalworldmail.net',
            'futuramailtech.com',
            'mailgeniustech.net',
            'innovativemailinc.org',
            'dreamtechemail.com',
            'virtualmailsolutions.net',
            'techwavecorp.org',
            'futuremailtech.net',
            'unicorpsolutionsmail.com',
            'innovativetechmail.net',
            'dreammailco.org',
            'virtualtechsolutions.net',
            'technowizardmail.com',
            'cloudgeniusemail.net',
            'infomailtech.org',
            'quantummailpro.net',
            'ecomailtech.net',
            'virtualimagemail.org',
            'techfuturamail.com',
            'digitaldreammail.net',
            'cloudtechsolutions.org',
            'innovativemailgenius.net',
            'wizardmailcorp.net',
            'dreammailsolutions.com',
            'futuretechmail.org',
            'virtualmailwizard.net',
            'techgeniuscorp.com',
            'imaginativemailtech.net'
        ];

        return $this->pegarNomeAleatorio(rand(1, 2)) . '.' . rand(1930, date('Y')) . '@' . $dominio[rand(
            0,
            count($dominio) - 1
        )];
    }

    private function pegarNomeAleatorio($quantidade): string
    {
        $nome = [
            'Alice',
            'Ethan',
            'Olivia',
            'Noah',
            'Ava',
            'Liam',
            'Mia',
            'Lucas',
            'Sophia',
            'Liam',
            'Isabella',
            'Oliver',
            'Emma',
            'Elijah',
            'Charlotte',
            'James',
            'Amelia',
            'Benjamin',
            'Evelyn',
            'William',
            'Abigail',
            'Henry',
            'Elizabeth',
            'Samuel',
            'Sofia',
            'Alexander',
            'Scarlett',
            'Michael',
            'Mila',
            'Daniel',
            'Avery',
            'Matthew',
            'Ella',
            'Jackson',
            'Grace',
            'Sebastian',
            'Aria',
            'David',
            'Luna',
            'Joseph',
            'Lily',
            'Carter',
            'Chloe',
            'Owen',
            'Penelope',
            'Wyatt',
            'Eleanor',
            'John',
            'Hazel',
            'Johnson',
            'Davis',
            'Smith',
            'Martinez',
            'Wilson',
            'Anderson',
            'Taylor',
            'Brown',
            'Lee',
            'Rodriguez',
            'Garcia',
            'Lopez',
            'Hernandez',
            'Davis',
            'Johnson',
            'Rodriguez',
            'Smith',
            'Williams',
            'Smith',
            'Taylor',
            'Davis',
            'Johnson',
            'Lee',
            'Wilson',
            'Anderson',
            'Taylor',
            'Wilson',
            'Anderson',
            'Davis',
            'Thomas',
            'Martinez',
            'Jones',
            'Hernandez',
            'Moore',
            'Wilson',
            'Harris',
            'Davis',
            'Rodriguez',
            'Anderson',
            'Smith',
            'Brown',
            'Martinez',
            'White',
            'Taylor',
            'Johnson',
            'Clark',
            'Wilson',
            'Thomas'
        ];
        $quantidadeNome = count($nome) - 1;

        $nomeFinal = [];
        for ($i = 0; $i < $quantidade; $i++) {
            $nomeFinal[] = strtolower($nome[rand(0, $quantidadeNome)]);
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
