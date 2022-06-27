<?php

if (!function_exists('exiteErro')) {
    // doc
    // exemplo
    // echo existeErro valor:id=>1|nome=>Andre,campo:id
    // echo existeErro valor:id=>1|nome=>Andre,campo:nao_existe
    /**
     * Verifica se o valor não contem os campos indicados ou se o status é de erro
     *
     * @param stdClass|array    $valor  Valor a ser conferido
     * @param array|string      $Campo  Campo para validar se existe, pode ser uma string "campo" ou uma lista em um array ["campo_1", "campo_2"]
     * @return  bool        Retorna true caso o valor seja válido                     Caso tenha algum erro retorna true
     */
    function existeErro(stdClass|array $valor, string|array $campo = ''): bool
    {
        if (!is_array($valor) && !is_object($valor)) {
            return true;
        }
        if (is_object($valor)) {
            $valor = jsonDecode(json_encode($valor), true);
        }
        if (array_key_exists('status', $valor) && 'erro' === $valor['status']) {
            return true;
        } elseif (!empty($campo) && is_string($campo) && !array_key_exists($campo, $valor)) {
            return true;
        } elseif (!empty($campo) && is_string($campo) && array_key_exists($campo, $valor)) {
            return false;
        } elseif (!empty($campo) && is_array($campo)) {
            foreach ($campo as $val) {
                if (!array_key_exists($val, $valor)) {
                    return true;
                }
            }
            return false;
        } elseif (array_key_exists('status', $valor) && 'sucesso' === $valor['status']) {
            return false;
        }
        return true;
    }
}

/*
|--------------------------------------------------------------------------
| VALIDA CONTATOS
|--------------------------------------------------------------------------
*/
if (!function_exists('ValidarTelefone')) {
    // doc
    // exemplo
    // echo validarTelefone 61912341234
    // echo validarTelefone (61)_91234-1234
    // echo validarTelefone (61)_1234-1234
    // echo validarTelefone (61)_9123-1234
    // echo validarTelefone (061)_91234-1234
    /**
     * Valida se é um telefone
     *
     * @param null|string $telefone Telefone a ser validado
     * @return  bool        Retorna true caso o valor seja válido
     */
    function ValidarTelefone(?string $telefone): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($telefone)->vazio()->telefone();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
if (!function_exists('validarEmail')) {
    // doc
    // exemplo
    // echo validarEmail nome@dominio.com.br
    // echo validarEmail nome@dominio.com.
    // echo validarEmail nome@dominio
    // echo validarEmail @dominio.com
    /**
     * Valida se é um e-mail
     *
     * @param null|string $email E-mail a ser validado
     * @return  bool        Retorna true caso o valor seja válido
     */
    function validarEmail(?string $email): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($email)->vazio()->email();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('validarJson')) {
    /**
     * Valida se string é um json
     *
     * @param   null|string $json   Json a ser validado
     * @return  bool                Retorna true caso o valor seja válido
     */
    function validarJson(?string $json): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($json)->vazio()->json();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('validarUrl')) {
    /**
     * Valida se é uma URL
     *
     * @param   null|string $url    Url a ser validada
     * @return  bool                Retorna true caso o valor seja válido
     */
    function validarUrl(?string $url): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($url)->vazio()->url();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
if (!function_exists('validarDecimal')) {
    // doc
    // exemplo
    // echo validarDecimal 1000.2
    // echo validarDecimal 1000.02
    // echo validarDecimal 1000.111
    // echo validarDecimal 1.000.00
    /**
     * Valida se é um valor decimal
     *
     * @param null|string $decimal Valor decimal a ser validado
     * @return  bool        Retorna true caso o valor seja válido
     */
    function validarDecimal(?string $decimal): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($decimal)->vazio()->decimal();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

/*
|--------------------------------------------------------------------------
| VALIDA DATAS
|--------------------------------------------------------------------------
*/
if (!function_exists('validarDate')) {
    // doc
    // exemplo
    // echo validarDate 2022-01-01
    // echo validarDate 01/01/2022
    // echo validarDate 01/01/2022_10:00:10
    // echo validarDate 2022-01-01_10:00:10
    // echo validarDate 2022-13-01
    // echo validarDate 2022-02-30
    // echo validarDate 31/02/2022
    /**
     * Valida uma data no formato date
     *
     * @param null|string|\Modules\Data $data   Data que deseja validar
     * @return  bool                            Retorna true caso o valor seja válido
     */
    function validarDate(null|string|\Modules\Data $data): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($data)->vazio()->date();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('validarData')) {
    // doc
    // exemplo
    // echo validarData 01/01/2022
    // echo validarData 2022-01-01
    // echo validarData 01/01/2022_10:00:10
    // echo validarData 2022-01-01_10:00:10
    // echo validarData 2022-13-01
    // echo validarData 2022-02-30
    // echo validarData 31/02/2022
    /**
     * Valida uma data no formato data
     *
     * @param null|string|\Modules\Data $data   Data que deseja validar
     * @return  bool                            Retorna true caso o valor seja válido
     */
    function validarData(null|string|\Modules\Data $data): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($data)->vazio()->data();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('validarDataDate')) {
    // doc
    // exemplo
    // echo validarDataDate 01/01/2022
    // echo validarDataDate 2022-01-01
    // echo validarDataDate 01/01/2022_10:00:10
    // echo validarDataDate 2022-01-01_10:00:10
    // echo validarDataDate 2022-13-01
    // echo validarDataDate 2022-02-30
    // echo validarDataDate 31/02/2022
    /**
     * Valida uma data no formato data ou date
     *
     * @param null|string|\Modules\Data $data   Data que deseja validar
     * @return  bool                                Retorna true caso o valor seja válido
     */
    function validarDataDate(null|string|\Modules\Data $data): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($data)->vazio()->dataDate();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('validarDateTime')) {
    // doc
    // exemplo
    // echo validarDateTime 2022-01-01_00:10:00
    // echo validarDateTime 01/01/2022_00:10:00
    // echo validarDateTime 2022-13-01_00:10:00
    // echo validarDateTime 2022-02-30_00:10:00
    // echo validarDateTime 31/02/2022_00:10:00
    // echo validarDateTime 01/01/2022
    // echo validarDateTime 2022-01-01
    /**
     * Valida uma data no formato dateTime
     *
     * @param   null|string|\Modules\DataHora   $data   Data que deseja validar
     * @return  bool                                    Retorna true caso o valor seja válido
     */
    function validarDateTime(null|string|\Modules\DataHora $data): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($data)->vazio()->dateTime();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('validarDataHora')) {
    // doc
    // exemplo
    // echo validarDataHora 01/01/2022_00:10:00
    // echo validarDataHora 2022-01-01_00:10:00
    // echo validarDataHora 2022-13-01_00:10:00
    // echo validarDataHora 2022-02-30_00:10:00
    // echo validarDataHora 31/02/2022_00:10:00
    // echo validarDataHora 01/01/2022
    // echo validarDataHora 2022-01-01
    /**
     * Valida uma data no formato dataHora
     *
     * @param   null|string|\Modules\DataHora $data     Data que deseja validar
     * @return  bool                                    Retorna true caso o valor seja válido
     */
    function validarDataHora(null|string|\Modules\DataHora $data): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($data)->vazio()->dataHora();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}
if (!function_exists('validarDataDateTime')) {
    // doc
    // exemplo
    // echo validarDataDateTime 2022-01-01_00:10:00
    // echo validarDataDateTime 01/01/2022_00:10:00
    // echo validarDataDateTime 2022-13-01_00:10:00
    // echo validarDataDateTime 2022-02-30_00:10:00
    // echo validarDataDateTime 31/02/2022_00:10:00
    // echo validarDataDateTime 01/01/2022
    // echo validarDataDateTime 2022-01-01
    /**
     * Valida uma data no formato dateTime
     *
     * @param null|string|\Modules\DataHora     $data   Data que deseja validar
     * @return  bool                                    Retorna true caso o valor seja válido
     */
    function validarDataDateTime(null|string|\Modules\DataHora $data): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($data)->vazio()->dataDateTime();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('validarCpf')) {
    // doc
    // exemplo
    // echo validarCpf 600.450.870-59
    // echo validarCpf 111.111.111-11
    /**
     * Valida CPF
     *
     * @param null|string $cpf CPF a ser validado
     * @return  bool        Retorna true caso o valor seja válido
     */
    function validarCpf(?string $cpf): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($cpf)->vazio()->cpf();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('validarCnpj')) {
    // doc
    // exemplo
    // echo validarCnpj 37.759.885/0001-17
    // echo validarCnpj 11.111.111/0001-00
    /**
     * Valida um CNPJ
     *
     * @param null|string $cnpj CNPJ a ser validado
     * @return  bool        Retorna true caso o valor seja válido
     */
    function validarCnpj(?string $cnpj): bool
    {
        try {
            (new \Helpers\ValidarHelper)->valor($cnpj)->vazio()->cnpj();
            return true;
        } catch (\Throwable) {
            return false;
        }
    }
}

if (!function_exists('validarUuid')) {
    // doc
    // exemplo
    // echo validarUuid fe35cded-c4f7-4c51-99a0-bd811033add4
    // echo validarUuid 1,false
    /**
     * Valida se uma string é um uuid
     *
     * @param   mixed $uuid     Valor a ser validado
     * @param   bool  $erro     Caso false, retorna uma excecao, true como padrão
     * @return  bool            Retorna true caso o valor seja válido
     * @throws  Erro\Excecao    Retonar um Erro\Exececao caso $erro for true e a validação falhe
     */
    function validarUuid($uuid, bool $erro = true): bool
    {
        $uuidValido = is_string($uuid) && preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $uuid);

        if (!$erro) {
            return $uuidValido;
        } else if (!$uuidValido) {
            mensagemStatus(404);
        }

        return true;
    }
}
if (!function_exists('validarPagina')) {
    // doc
    // exemplo
    // echo validarPagina 1
    // echo validarPagina false
    /**
     * Valida se o valor é uma número de página válido
     *
     * @param   string|int $pagina Número da página que deseja validar
     * @return  bool
     */
    function validarPagina(string|int $pagina): bool
    {
        return preg_match('/^[1-9]{1}[0-9]*$/', $pagina);
    }
}
