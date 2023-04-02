<?php

if (!function_exists('cpfAleatorio')) {
    // doc
    // exemplo
    // echo cpfAleatorio
    /**
     * Gera um CPF aleatório
     *
     * @return string Retonar um cpf com apenas número
     */
    function cpfAleatorio(): string
    {
        return (new \Random\Random())->cpf();
    }
}
if (!function_exists('cnpjAleatorio')) {
    // doc
    // exemplo
    // echo cnpjAleatorio
    /**
     * Gera um CNPJ aleatório
     *
     * @return string Retorna um CNPJ com apenas número
     */
    function cnpjAleatorio(): string
    {
        return (new \Random\Random())->cnpj();
    }
}
if (!function_exists('rgAleatorio')) {
    // doc
    // exemplo
    // echo rgAleatorio
    /**
     * Gera um RG aleatório
     *
     * @return int
     */
    function rgAleatorio(): string
    {
        return (new \Random\Random())->rg();
    }
}
if (!function_exists('emailAleatorio')) {
    // doc
    // exemplo
    // echo emailAleatorio
    /**
     * Gera um e-mail aleatório
     *
     * @return string
     */
    function emailAleatorio(): string
    {
        return (new \Random\Random())->email();
    }
}
if (!function_exists('telefoneAleatorio')) {
    // doc
    // exemplo
    // echo telefoneAleatorio
    /**
     * Gera um telefone fixo ou celular aleatório
     *
     * @return int
     */
    function telefoneAleatorio(): int
    {
        return (new \Random\Random())->telefone();
    }
}
if (!function_exists('telefoneCelularAleatorio')) {
    // doc
    // exemplo
    // echo telefoneCelularAleatorio
    /**
     * Gera um telefone celular aleatório
     *
     * @return int
     */
    function telefoneCelularAleatorio(): int
    {
        return (new \Random\Random())->telefoneCelular();
    }
}
if (!function_exists('telefoneFixoAleatorio')) {
    // doc
    // exemplo
    // echo telefoneCelularAleatorio
    /**
     * Gera um telefone fixo aleatório
     *
     * @return int
     */
    function telefoneFixoAleatorio(): int
    {
        return (new \Random\Random())->telefoneFixo();
    }
}
if (!function_exists('dddAleatorio')) {
    // doc
    // exemplo
    // echo dddAleatorio
    /**
     * Gera um DDD aleatório
     *
     * @return int
     */
    function dddAleatorio(): int
    {
        return (new \Random\Random())->ddd();
    }
}
if (!function_exists('ddiAleatorio')) {
    // doc
    // exemplo
    // echo ddiAleatorio
    /**
     * Gera um DDI aleatório
     *
     * @return int
     */
    function ddiAleatorio(): int
    {
        return (new \Random\Random())->ddi();
    }
}
if (!function_exists('dataPassadaAleatorio')) {
    // doc
    // exemplo
    // echo dataPassadaAleatorio
    /**
     * Gera uma data passada aleatória
     *
     * @return string
     */
    function dataPassadaAleatorio(): string
    {
        return (new \Random\Random())->dataPassada();
    }
}
if (!function_exists('dataFuturaAleatorio')) {
    // doc
    // exemplo
    // echo dataFuturaAleatorio
    /**
     * Gera uma data Futura aleatória
     *
     * @return string
     */
    function dataFuturaAleatorio(): string
    {
        return (new \Random\Random())->dataFutura();
    }
}
if (!function_exists('cepAleatorio')) {
    // doc
    // exemplo
    // echo cepAleatorio
    /**
     * Gera um CEP aleatório
     *
     * @return int
     */
    function cepAleatorio(): int
    {
        return (new \Random\Random())->cep();
    }
}
if (!function_exists('logradouroAleatorio')) {
    // doc
    // exemplo
    // echo logradouroAleatorio
    /**
     * Gera um logradouro aleatório
     *
     * @return string
     */
    function logradouroAleatorio(): string
    {
        return (new \Random\Random())->logradouro();
    }
}
if (!function_exists('numeroAleatorio')) {
    // doc
    // exemplo
    // echo numeroAleatorio
    // echo numeroAleatorio 400,500
    /**
     * Gera um numero aleatorio
     *
     * @param int $de   Numero de, padrão 1
     * @param int $ate  Numero até, padrão 999
     * @return int
     */
    function numeroAleatorio(int $de = 1, int $ate = 999): int
    {
        return (new \Random\Random())->numero($de, $ate);
    }
}
if (!function_exists('complementoAleatorio')) {
    // doc
    // exemplo
    // echo complementoAleatorio
    /**
     * Gera um complemento aleatório
     *
     * @return string
     */
    function complementoAleatorio(): string
    {
        return (new \Random\Random())->complemento();
    }
}
if (!function_exists('bairroAleatorio')) {
    // doc
    // exemplo
    // echo bairroAleatorio
    /**
     * Gera um bairro aleatório
     *
     * @return string
     */
    function bairroAleatorio(): string
    {
        return (new \Random\Random())->bairro();
    }
}
if (!function_exists('cidadeAleatorio')) {
    // doc
    // exemplo
    // echo cidadeAleatorio
    // echo cidadeAleatorio TO
    /**
     * Gera uma cidade aleatória
     *
     * @param string $estado    Estado que deseja buscar a cidade
     * @return string
     */
    function cidadeAleatorio(string $estado = 'SP'): string
    {
        return (new \Random\Random())->cidade($estado);
    }
}
if (!function_exists('estadoAleatorio')) {
    // doc
    // exemplo
    // echo estadoAleatorio
    /**
     * Gera um estado aleatório
     *
     * @return string
     */
    function estadoAleatorio(): string
    {
        return (new \Random\Random())->estado();
    }
}
if (!function_exists('simNaoAleatorio')) {
    // doc
    // exemplo
    // echo simNaoAleatorio
    /**
     * Gera uma string com sim ou nao aleatoriamente
     *
     * @return string
     */
    function simNaoAleatorio(): string
    {
        return (new \Random\Random())->simNao();
    }
}
if (!function_exists('valorAleatorio')) {
    // doc
    // exemplo
    // echo valorAleatorio varlo_1|valor_2
    /**
     * Gera um valor aleatório pelo array passado
     *
     * @param array $array Array com os valores que deseja pegar
     * @return string
     */
    function valorAleatorio(array $array): string
    {
        return (new \Random\Random())->random($array);
    }
}
if (!function_exists('nomeAleatorio')) {
    // doc
    // exemplo
    // echo nomeAleatorio
    /**
     * Gera um nome aleatório
     *
     * @return string
     */
    function nomeAleatorio(): string
    {
        return (new \Random\Random())->nome();
    }
}
if (!function_exists('sobreNomeAleatorio')) {
    // doc
    // exemplo
    // echo sobreNomeAleatorio
    /**
     * Gera um sobre nome aleatório
     *
     * @return string
     */
    function sobreNomeAleatorio(): string
    {
        return (new \Random\Random())->sobreNome();
    }
}
if (!function_exists('nomeCompletoAleatorio')) {
    // doc
    // exemplo
    // echo nomeCompletoAleatorio
    /**
     * Gera um nome e sobrenome aleatório
     *
     * @return string
     */
    function nomeCompletoAleatorio(): string
    {
        return (new \Random\Random())->nomeCompleto();
    }
}
if (!function_exists('generoAleatorio')) {
    // doc
    // exemplo
    // echo generoAleatorio
    /**
     * Gera um gênero aleatório
     *
     * @return string
     */
    function generoAleatorio(): string
    {
        return (new \Random\Random())->genero();
    }
}
if (!function_exists('estadoCivilAleatorio')) {
    // doc
    // exemplo
    // echo estadoCivilAleatorio
    /**
     * Gera um estado civil aleatório
     *
     * @return string
     */
    function estadoCivilAleatorio(): string
    {
        return (new \Random\Random())->estadoCivil();
    }
}
if (!function_exists('situacaoAleatorio')) {
    // doc
    // exemplo
    // echo situacaoAleatorio
    /**
     * Gera um situação aleatória
     *
     * @return string
     */
    function situacaoAleatorio(): string
    {
        return (new \Random\Random())->situacao();
    }
}
if (!function_exists('senhaAleatorio')) {
    // doc
    // exemplo
    // echo senhaAleatorio
    /**
     * Gera uma senha aleatória
     *
     * @return string
     */
    function senhaAleatorio(): string
    {
        return (new \Random\Random())->senha();
    }
}
