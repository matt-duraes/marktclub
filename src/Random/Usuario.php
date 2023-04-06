<?php

namespace Random;

trait Usuario
{
    public function genero(): string
    {
        return ['masculino', 'feminino'][rand(0, 1)];
    }

    public function estadoCivil(): string
    {
        return ['solteiro', 'casado', 'divorciado', 'viuvo'][rand(0, 3)];
    }

    public function situacao(): string
    {
        return ['ativo', 'aposentado'][rand(0, 1)];
    }

    public function nomeCompleto(): string
    {
        return $this->nome() . ' ' . $this->sobreNome();
    }

    public function nome(): string
    {
        $nome = [
            'André',
            'Milena',
            'João',
            'Luis',
            'Felipe',
            'Mario',
            'Tereza',
            'Joaqium',
            'Antônio',
            'Maria',
            'Marcia',
            'Daniele',
            'Danilo',
            'Patrique',
            'Serio',
            'Mario',
            'Ricardo',
            'Ana',
            'Leandro',
            'Leonardo',
            'Zarati',
            'Thiago',
            'Tiago',
            'Marcos',
            'Marcus',
            'Hugo',
            'Hiego',
            'Higor',
            'Bruno',
            'Brenno',
            'Cássio',
            'Maiara',
            'Tarcísio',
            'Geraldo',
            'Miguel',
            'Murilo'
        ];
        return $nome[rand(0, count($nome) - 1)];
    }

    public function sobreNome(): string
    {
        $sobreNome = [
            'Pereira',
            'Rodrigues',
            'Teixeira',
            'Medes',
            'Souza',
            'Pinto',
            'Santos',
            'Oliveira',
            'Lima',
            'Silva',
            'Ferreira',
            'Costa',
            'Almeida',
            'Nascimento',
            'Alves',
            'Carvalho',
            'Araújo',
            'Ribeiro'
        ];
        $primeiroSobreNome = $sobreNome[rand(0, count($sobreNome) - 1)];
        $key = array_search($primeiroSobreNome, $sobreNome);
        if ($key) {
            unset($sobreNome[$key]);
            $sobreNome = array_values($sobreNome);
        }
        return $primeiroSobreNome . ' ' . $sobreNome[rand(0, count($sobreNome) - 1)];
    }

    public function senha(): string
    {
        return 'Teste@' . rand(100, 99999);
    }
}
