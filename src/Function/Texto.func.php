<?php

if (!function_exists('strCaixa')) {
    // doc
    // exemplo
    // echo strCaixa olá_mundo,A
    // echo strCaixa Olá_Mundo,a
    // echo strCaixa olá_mundo,Aa
    // echo strCaixa olá_mundo,Aa_Aa
    /**
     * Converte a string para a Caixa selecionada
     *
     * @param  null|string $string String a ser convertida
     * @param  string      $tipo   Seta o tipo de caixa: "A": Caixa alta; "a": caixa baixa;
     *                             "Aa": Caixa alta na primeira letra; "Aa Aa": Caixa alta na primeira
     *                             letra de cada palavra
     * @return string      String convertida
     */
    function strCaixa(?string $string, string $tipo): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->caixa($tipo)->r();
    }
}

if (!function_exists('strCaixaAlta')) {
    // doc
    // exemplo
    // echo strCaixaAlta olá_mundo
    /**
     * Converte a string para a Caixa Alta
     *
     * @param  null|string $string String a ser convertida
     * @return string      String convertida
     */
    function strCaixaAlta(?string $string): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->caixa('A')->r();
    }
}
if (!function_exists('strCaixaAltaAlta')) {
    // doc
    // exemplo
    // echo strCaixaAltaAlta olá_mundo
    /**
     * Converte a string para a Caixa Alta para cada início de palavra
     *
     * @param  null|string $string String a ser convertida
     * @return string      String convertida
     */
    function strCaixaAltaAlta(?string $string): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->caixa('Aa Aa')->r();
    }
}
/*
|--------------------------------------------------------------------------
| MUDAR TEXTO PARA CAIXA BAIXA
|--------------------------------------------------------------------------
| Muda string enviada para caixa baixa
|
 */
if (!function_exists('strCaixaBaixa')) {
    // doc
    // exemplo
    // echo strCaixaBaixa OLÁ_Mundo
    /**
     * Converte a string para a Caixa baixa
     *
     * @param  null|string $string String a ser convertida
     * @return string      String convertida
     */
    function strCaixaBaixa(?string $string): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->caixa('a')->r();
    }
}

if (!function_exists('strDocumento')) {
    // doc
    // exemplo
    // echo strDocumento 01234567890
    // echo strDocumento 00491210000120
    /**
     * Converte um CPF ou CNPJ para o padrão com pontos
     *
     * @param  null|string $string String a ser convertida
     * @return string      String convertida
     */
    function strDocumento(?string $string): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->documento()->r();
    }
}

/*
|--------------------------------------------------------------------------
| CONVERTER O CPF PARA VERSÃO COM PONTO
|--------------------------------------------------------------------------
|
| Coloca os pontos (.) e traço (-) no CPF
|
 */
if (!function_exists('strCpf')) {
    // doc
    // exemplo
    // echo strCpf 01234567890
    /**
     * Converte um CPF para o padrão com pontos
     *
     * @param  null|string $string String a ser convertida
     * @return string      String convertida
     */
    function strCpf(?string $string): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->cpf()->r();
    }
}

if (!function_exists('strCnpj')) {
    // doc
    // exemplo
    // echo strCnpj 00491210000120
    /**
     * Converte um CNPJ para o padrão com pontos
     *
     * @param  null|string $string String a ser convertida
     * @return string      String convertida
     */
    function strCnpj(?string $string): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->cnpj()->r();
    }
}

if (!function_exists('strSlug')) {
    // doc
    // exemplo
    // echo strSlug Título_para_ser_convertido_para_SLUG
    /**
     * Converte uma string para um slug
     *
     * @param  string $string String a ser convertida
     * @param  string $slug   Caracter que será usado no lugar do espaço, - por padrão
     * @param  bool   $espaco Troca espaços por +
     * @return string String convertida
     */
    function strSlug(string $string, string $slug = '-', bool $espaco = false): string
    {
        return (new \Helpers\TextoHelper())->valor($string)->slug($slug, $espaco)->r();
    }
}

if (!function_exists('strTelefone')) {
    // doc
    // exemplo
    // echo strTelefone 6133331234
    // echo strTelefone 61988881234
    // echo strTelefone 08001231234
    // echo strTelefone 40041234
    // echo strTelefone 30031234
    // echo strTelefone 556133334444,+xx_(xx)_xxxx-xxxx
    /**
     * Converte uma string para um telefone
     *
     * @param  null|string $string String a ser convertida
     * @param  string      $padrao Padrão quer será retornado o telefone usando x para fazer o replace dos numeros
     * @return string      String convertida
     */
    function strTelefone(?string $string, string $padrao = ''): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->telefone($padrao)->r();
    }
}

if (!function_exists('strDinheiro')) {
    // doc
    // exemplo
    // echo strDinheiro 1000.00
    // echo strDinheiro 1.000.00,$
    /**
     * Converte a string para o padrão de dinheiro
     *
     * @param  null|string $string String a ser convertida
     * @param  string      $moeda  Tipo de moeda será convertido podendo ser: "R$": Real (1.000,00);
     *                             "$": Dolar (1000.00); R$ por padrão
     * @return string      String convertida
     */
    function strDinheiro(?string $string, string $moeda = 'R$'): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->dinheiro($moeda)->r();
    }
}

/*/
|--------------------------------------------------------------------------
| CONVERTE UMA STRING EM CEP
|--------------------------------------------------------------------------
|
| Converte a string para o CEP com modelo brasileiro (70.000-000)
|
/*/
if (!function_exists('strCep')) {
    // doc
    // exemplo
    // echo strCep 70610440
    /**
     * Converte a string para o padrão de CEP
     *
     * @param  null|string $string String a ser convertida
     * @return string      String convertida
     */
    function strCep(?string $string): string
    {
        if (is_null($string)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($string)->cep()->r();
    }
}

/*/
|--------------------------------------------------------------------------
| CONVERTE STRING PARA JSON
|--------------------------------------------------------------------------
|
| Pega um json e transforma em um JSON
|
/*/
if (!function_exists('strJson')) {
    // doc
    /**
     * Converte uma string JSON para array
     *
     * @param  null|string $string String a ser convertida
     * @return string      String convertida
     */
    function strJson(?string $string): string
    {
        if (is_null($string)) {
            return [];
        }
        return (new \Helpers\TextoHelper())->valor($string)->json()->r();
    }
}
if (!function_exists('strImplodeVirgula')) {
    // doc
    /**
     * Retorna uma string separada por virgule e um "e" no último implode
     *
     * @param  null|array $array Array que deseja converter
     * @return string     String convertida
     */
    function strImplodeVirgula(?array $array): string
    {
        if (is_null($array) || empty($array)) {
            return '';
        }
        return (new \Helpers\TextoHelper())->valor($array)->implodeVirgula()->r();
    }
}

if (!function_exists('strCortar')) {
    // doc
    // exemplo
    // echo strCortar Olá_mundo_cortado,11,...
    // echo strCortar Olá_mundo_cortado,6,...,true
    /**
     * Corta uma string
     *
     * @param  int    $tamanho Quantidade de caracter que deve ter a string
     * @param  string $simbolo Simbolo que ficaram no final da string cortada
     * @param  bool   $forca   Força o corte da string mesmo sem terminar a palavra
     * @return string Retorna a string cortada
     */
    function strCortar(string $string, int $tamanho, string $simbolo = '...', bool $forca = false)
    {
        return (new \Helpers\TextoHelper($string))->cortar($tamanho, $simbolo, $forca)->r();
    }
}

if (!function_exists('strCodigo')) {
    // doc
    // exemplo
    // echo strCodigo 20
    // echo strCodigo 10,true,true,true,true
    // echo strCodigo 10,false,false,false,false,ABCDEF0987654321
    /**
     * Gera um código aleatório
     *
     * @param  int    $tamanho   Quantidade de caracteres terá o código
     * @param  bool   $minusculo Se terá caracteres minusculos no código
     * @param  bool   $maiusculo Se terá caracteres maiusculos no código
     * @param  bool   $numero    Se terá números no código
     * @param  bool   $simbolo   Se terá caracteres especiais no código (!@#$%*-)
     * @param  string $outro     Outros caracteres que deseja usar
     * @return string String convertida
     */
    function strCodigo(
        int $tamanho = 8,
        bool $minusculo = true,
        bool $maiusculo = true,
        bool $numero = true,
        bool $simbolo = false,
        string $outro = ''
    ): string {
        $retorno = '';

        $caractere = $minusculo ? 'abcdefghijklmnopqrstuvwxyz' : '';
        $caractere .= $maiusculo ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '';
        $caractere .= $numero ? '1234567890' : '';
        $caractere .= $simbolo ? '!@#$%*-' : '';
        if (!empty($outro)) {
            $caractere .= $outro;
        }

        $caractere_tamanho = strlen($caractere);

        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $caractere_tamanho);
            $retorno .= $caractere[$rand - 1];
        }

        return $retorno;
    }
}

if (!function_exists('strNull')) {
    // doc
    // exemplo
    // echo strNull Olá_Mundo
    // echo strNull null,Teste
    /**
     * Retorna vazio ou um padrão definido quando for null
     *
     * @param  null|string $valor  Valor que deseja converter
     * @param  string      $padrao Padrão que deseja retornar quando for null, vazio por padrão
     * @return string      String convertida
     */
    function strNull($valor, $padrao = '')
    {
        if (is_null($valor)) {
            return $padrao;
        }
        return $valor;
    }
}
if (!function_exists('strInt')) {
    // doc
    // exemplo
    // echo strInt 1
    // echo strInt teste
    /**
     * Retorna o valor se for inteiro ou null se não for inteiro
     *
     * @param  mixed    $inteiro Valor que deseja validar
     * @return null|int Valor inteiro em caso positivo ou null em caso falso
     */
    function strInt($inteiro)
    {
        return preg_match('/^\-{0,1}[1-9]{1}[0-9]{0,}$/', $inteiro) ? $inteiro : null;
    }
}
if (!function_exists('strEmail')) {
    // doc
    // exemplo
    /**
     * Converter um e-mail para caixa baixa
     *
     * @param  string $email E-mail que deseja converter
     * @return string String convertida
     */
    function strEmail($email)
    {
        if (is_null($email)) {
            return '';
        }
        return strCaixaBaixa($email);
    }
}
if (!function_exists('strConverterTextareaEmParagrafo')) {
    // doc
    /**
     * Converte um texto de textarea para paragrafo colocar <p></p> em cada quebra de linha
     *
     * @param  null|string $texto String a ser convertida
     * @return string      String convertida
     */
    function strConverterTextareaEmParagrafo(?string $texto = null)
    {
        if (empty($texto)) {
            return '';
        }
        return '<p>' . implode('</p><p>', explode(PHP_EOL, $texto)) . '</p>';
    }
}
if (!function_exists('strDominio')) {
    // doc
    /**
     * Converter um URL para seu domínio
     *
     * @param  null|string $texto String a ser convertida
     * @return string      String convertida
     */
    function strDominio(?string $texto = null, bool $www = true, bool $porta = true)
    {
        if (empty($texto)) {
            return '';
        }
        $dominio = explode('/', preg_replace('/^http(s){0,1}\:\/\//', '', $texto))[0] ?? '';
        $dominio = $porta ? $dominio : explode(':', $dominio)[0];
        return $www ? $dominio : preg_replace('/^www\./', '', $dominio);
    }
}
